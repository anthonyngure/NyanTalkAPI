<?php
    
    namespace App\Http\Controllers\API;
    
    use App\Http\Controllers\Controller;
    use App\Notifications\AutoRegisteredNotification;
    use App\Rules\Phone;
    use App\User;
    use App\Utils;
    use Auth;
    use Hash;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;
    use Spatie\QueryBuilder\QueryBuilder;
    
    class UserController extends Controller
    {
        /**
         * UserController constructor.
         */
        public function __construct()
        {
            $this->middleware('userType:ADMIN,OFFICIAL,DEPARTMENT_ADMIN');
            
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
                'type' => 'nullable|in:OFFICIAL,ADMIN,CITIZEN',
            ]);
            
            $users = QueryBuilder::for (User::class)
                ->allowedIncludes(User::ALLOWED_INCLUDES);
            
            if (!empty($request->input('type'))) {
                $users = $users->where('type', $request->input('type'));
            }
            
            $user = Auth::user();
            if (Auth::user()->isDepartmentAdmin()) {
                $users = $users->where('department_id', $user->department_id)
                    ->where('type', User::TYPE_OFFICIAL)
                    ->whereNotIn('id', [$user->id]);
            }
            
            return $this->paginate($request, $users);
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            $this->validate($request, [
                'phone'                   => ['required', 'numeric', 'unique:users', new Phone()],
                'name'                    => 'required|max:50',
                'type'                    => 'required|in:CITIZEN,OFFICIAL,CS,DEPARTMENT_ADMIN',
                'email'                   => 'required_unless:type,CITIZEN|email|unique:users',
                'departmentId'            => 'required_if:type,OFFICIAL,DEPARTMENT_ADMIN,CS|exists:departments,id',
                'smsNotificationsEnabled' => 'required|boolean',
            ]);
            
            
            //Register the user and send an sms to them
            //Generate password
            $password = random_int(1000, 9999);
            /** @var \App\User $citizen */
            $citizen = User::create([
                'phone'          => $request->input('phone'),
                'name'           => $request->input('name'),
                'email'          => $request->input('email'),
                'department_id'  => $request->input('departmentId'),
                'type'           => strtoupper($request->input('type')),
                'password'       => Hash::make($password),
                'sms_notifiable' => $request->input('smsNotificationsEnabled'),
            ]);
            
            $citizen->phone_verification_code = Utils::generateVerificationCode();
            $citizen->created_by_id = \Auth::user()->id;
            $citizen->save();
            
            $citizen->notify(new AutoRegisteredNotification($password));
            
            return $this->itemCreatedResponse($citizen);
            
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            
            $user = QueryBuilder::for (User::class)
                ->withCount(User::COUNTS)
                ->allowedIncludes(User::ALLOWED_INCLUDES)
                ->findOrFail($id);
            
            return $this->itemResponse($user);
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
            
            
            /** @var \App\User $user */
            $user = User::findOrFail($id);
            $this->validate($request, [
                'phone'                   => ['required', 'numeric', Rule::unique('users')->ignore($id), new Phone()],
                'name'                    => 'required|max:50',
                'type'                    => 'required|in:CITIZEN,OFFICIAL,CS,DEPARTMENT_ADMIN',
                'email'                   => ['nullable', 'required_unless:type,CITIZEN', 'email', Rule::unique('users')->ignore($id)],
                'departmentId'            => 'required_if:type,OFFICIAL,DEPARTMENT_ADMIN,CS|exists:departments,id',
                'smsNotificationsEnabled' => 'required|boolean',
            ]);
            
            $user->phone = $request->input('phone');
            $user->name = $request->input('name');
            $user->type = strtoupper($request->input('type'));
            $user->email = $request->input('email');
            $user->department_id = $request->input('departmentId');
            $user->sms_notifiable = $request->input('smsNotificationsEnabled');
            $user->save();
            
            return $this->show($user->id);
        }
        
        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         * @throws \Exception
         */
        public function destroy($id)
        {
            /** @var \App\User $user */
            $user = User::findOrFail($id);
            $deleted = $user->delete();
            if ($deleted) {
                $user->deleted_by_id = Auth::user()->getKey();
                $user->save();
            }
            
            return $this->itemResponse($user);
        }
    }
