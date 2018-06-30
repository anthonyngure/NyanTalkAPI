<?php
    
    namespace App\Http\Controllers\API;
    
    use App\Department;
    use App\Http\Controllers\Controller;
    use App\Notifications\AutoRegisteredNotification;
    use App\Notifications\NewTicketNotification;
    use App\Notifications\TicketCompletedNotification;
    use App\Rules\Phone;
    use App\SubCounty;
    use App\Ticket;
    use App\User;
    use App\Utils;
    use App\Ward;
    use Auth;
    use Hash;
    use Illuminate\Http\Request;
    use Spatie\QueryBuilder\QueryBuilder;
    
    class TicketController extends Controller
    {
        
        
        /**
         * UserController constructor.
         */
        public function __construct()
        {
            $this->middleware('userType:OFFICIAL,CITIZEN')->only('store');
            
        }
        
        
        /**
         * Display a listing of the resource.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request)
        {
            $this->validate($request, [
                'filter' => 'nullable|in:latest',
            ]);
            
            
            $user = Auth::user();
            if ($user->isCitizen()) {
                $tickets = QueryBuilder::for (Ticket::class)
                    ->allowedIncludes(Ticket::ALLOWED_INCLUDES)
                    ->where('citizen_id', $user->getKey());
            } else if ($user->isOfficial()) {
                $tickets = QueryBuilder::for (Ticket::class)
                    ->allowedIncludes(Ticket::ALLOWED_INCLUDES)
                    ->where('assigned_official_id', $user->getKey());
            } else {
                $tickets = QueryBuilder::for (Ticket::class)
                    ->allowedIncludes(Ticket::ALLOWED_INCLUDES);
            }
            
            if ($request->input('filter') == 'latest') {
                $tickets = $tickets->latest()->limit(5);
            }
            
            $data = $tickets->orderByDesc('created_at')
                ->withCount(Ticket::COUNTS);
            
            return $this->paginate($request, $data);
        }
        
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $departments = Department::all();
            $wards = Ward::all();
            $subCounties = SubCounty::with('wards')->get();
            $data = [
                'departments' => $departments,
                'subCounties' => $subCounties,
                'wards'       => $wards,
            ];
            
            
            return $this->arrayResponse($data);
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            $user = Auth::user();
            $this->validate($request, [
                'departmentId' => 'required|exists:departments,id',
                'subCountyId'  => 'required|exists:sub_counties,id',
                'wardId'       => 'required|exists:wards,id',
                'subject'      => 'required|string|max:255',
                'details'      => 'required|string',
                'image'        => 'nullable|image|max:6144',
            ]);
            
            if (!$user->isCitizen()) {
                $this->validate($request, [
                    'phone'     => ['required', 'numeric', new Phone()],
                    'name'      => 'required|max:50',
                    'inputMode' => 'required|in:' . Ticket::INPUT_MODE_WALK_IN, Ticket::INPUT_MODE_PHONE_CALL,
                ]);
                /** @var \App\User $citizen */
                $citizen = User::withTrashed()->wherePhone(Utils::phoneWithCode($request->input('phone')))->first();
                if (is_null($citizen)) {
                    //Register the user and send an sms to them
                    //Generate password
                    $password = random_int(1000, 9999);
                    $citizen = User::create([
                        'phone'    => $request->input('phone'),
                        'name'     => $request->input('name'),
                        'password' => Hash::make($password),
                    ]);
                    
                    $citizen->phone_verification_code = Utils::generateVerificationCode();
                    $citizen->save();
                    
                    $citizen->notify(new AutoRegisteredNotification($password));
                }
            } else {
                $citizen = $user;
            }
            
            $ticket = Ticket::create([
                'citizen_id'    => $citizen->getKey(),
                'department_id' => $request->input('departmentId'),
                'ward_id'       => $request->input('wardId'),
                'subject'       => $request->input('subject'),
                'details'       => $request->input('details'),
                'input_mode'    => $request->input('inputMode'),
            ]);
            
            
            User::whereType(User::TYPE_ADMIN)->get()->where('email_notifiable', true)
                ->each(function (User $user) use ($ticket) {
                    $user->notify(new NewTicketNotification($ticket));
                });
            
            return $this->show($ticket->id);
            
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            $ticket = QueryBuilder::for (Ticket::class)
                ->allowedIncludes(Ticket::ALLOWED_INCLUDES)
                ->withCount(Ticket::COUNTS)
                ->findOrFail($id);
            
            return $this->itemResponse($ticket);
        }
        
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  int                      $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            //
        }
        
        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            //
        }
        
        /**
         * @param $ticketId
         * @param $officialId
         * @return \Illuminate\Http\Response
         */
        public function assign($ticketId, $officialId)
        {
            /** @var \App\Ticket $ticket */
            $ticket = Ticket::findOrFail($ticketId);
            /** @var \App\User $official */
            $official = User::findOrFail($officialId);
            
            $ticket->status = Ticket::STATUS_ASSIGNED;
            $ticket->assigned_official_at = now()->toDateTimeString();
            $ticket->assigned_official_id = $official->getKey();
            $ticket->assigned_by = Auth::user()->getKey();
            
            
            $ticket->save();
            
            return $this->show($ticket->getKey());
        }
        
        /**
         * @param $id
         * @return \Illuminate\Http\Response
         */
        public function start($id)
        {
            /** @var \App\Ticket $ticket */
            $ticket = Ticket::findOrFail($id);
            $ticket->status = Ticket::STATUS_STARTED;
            $ticket->started_at = now()->toDateTimeString();
            $ticket->started_by = Auth::user()->id;
            $ticket->save();
            
            return $this->show($ticket->getKey());
        }
        
        /**
         * @param $id
         * @return \Illuminate\Http\Response
         */
        public function complete($id)
        {
            /** @var \App\Ticket $ticket */
            $ticket = Ticket::with('citizen')->findOrFail($id);
            $ticket->status = Ticket::STATUS_COMPLETED;
            $ticket->completed_by = Auth::user()->id;
            $ticket->completed_at = now()->toDateTimeString();
            $ticket->save();
            
            $ticket->citizen->notify(new TicketCompletedNotification($ticket));
            
            return $this->show($ticket->getKey());
        }
        
        /**
         * @param \Illuminate\Http\Request $request
         * @param                          $id
         * @return \Illuminate\Http\Response
         */
        public function share(Request $request, $id)
        {
            $this->validate($request, [
                'reason' => 'required|string',
            ]);
            /** @var \App\Ticket $ticket */
            $ticket = Ticket::findOrFail($id);
            $ticket->status = Ticket::STATUS_PENDING_SHARE_APPROVAL;
            $ticket->share_reason = $request->input('reason');
            $ticket->share_requested_at = now()->toDateTimeString();
            $ticket->save();
            
            return $this->show($ticket->getKey());
        }
        
        /**
         * @param $id
         * @return \Illuminate\Http\Response
         */
        public function approveShare($id)
        {
            /** @var \App\Ticket $ticket */
            $ticket = Ticket::findOrFail($id);
            $ticket->status = Ticket::STATUS_ASSIGNED_DEPARTMENT;
            $ticket->share_approved_by = Auth::user()->id;
            $ticket->share_approved_at = now()->toDateTimeString();
            $ticket->save();
            
            return $this->show($ticket->getKey());
        }
        
        public function summary(Request $request)
        {
            $this->validate($request, [
                'filter'      => 'nullable|in:subCounty,ward',
                'wardId'      => 'required_if:filter,ward',
                'subCountyId' => 'required_if:filter,subCounty',
            ]);
            
            $allTickets = Ticket::withoutTrashed()->count();
            $queuedTickets = Ticket::whereStatus(Ticket::STATUS_STARTED)->count();
            $pendingTickets = Ticket::whereStatus(Ticket::STATUS_PENDING_ASSIGNMENT)
                ->orWhere('status', Ticket::STATUS_PENDING_SHARE_APPROVAL)
                ->count();
            $closedTickets = Ticket::whereStatus(Ticket::STATUS_COMPLETED)->count();
            $totalToday = Ticket::whereKey(0)->count();
            $totalThisWeek = Ticket::whereKey(0)->count();
            $totalThisMonth = Ticket::whereKey(0)->count();
            $totalThisYear = Ticket::whereKey(0)->count();
            
            $data = [
                'allTickets'     => $allTickets,
                'queuedTickets'  => $queuedTickets,
                'pendingTickets' => $pendingTickets,
                'closedTickets'  => $closedTickets,
                'totalToday'     => $totalToday,
                'totalThisWeek'  => $totalThisWeek,
                'totalThisMonth' => $totalThisMonth,
                'totalThisYear'  => $totalThisYear,
            ];
            
            return $this->arrayResponse($data);
        }
    }
