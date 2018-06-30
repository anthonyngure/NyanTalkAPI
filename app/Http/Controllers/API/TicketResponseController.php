<?php
    
    namespace App\Http\Controllers\API;
    
    use App\Http\Controllers\Controller;
    use App\Notifications\TicketResponseNotification;
    use App\Response;
    use App\Ticket;
    use Auth;
    use Illuminate\Http\Request;
    use Spatie\QueryBuilder\QueryBuilder;
    
    class TicketResponseController extends Controller
    {
        /**
         * TicketResponseController constructor.
         */
        public function __construct()
        {
        }
        
        
        /**
         * Display a listing of the resource.
         *
         * @param $ticketId
         * @return \Illuminate\Http\Response
         */
        public function index($ticketId)
        {
            //$ticket = Ticket::findOrFail($ticketId);
            
            $responses = QueryBuilder::for (Response::class)
                ->allowedIncludes(Response::ALLOWED_INCLUDES)
                ->where('ticket_id', $ticketId)
                ->get();
            
            return $this->collectionResponse($responses);
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param                           $ticketId
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request, $ticketId)
        {
            $this->validate($request, [
                'details' => 'required|string',
            ]);
            /** @var \App\Ticket $ticket */
            $ticket = Ticket::with(['citizen'])->findOrFail($ticketId);
            
            $user = Auth::user();
            /** @var \App\Response $response */
            $response = $ticket->responses()->create([
                'user_id' => $user->getKey(),
                'details' => $request->input('details'),
            ]);
    
            if ($user->isOfficial()) {
                $ticket->citizen->notify(new TicketResponseNotification($ticket, $response, $user));
            }
            
            return $this->show($response->id);
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //dd(\Request::all());
            $response = QueryBuilder::for (Response::class)
                ->allowedIncludes(Response::ALLOWED_INCLUDES)
                ->findOrFail($id);
            
            return $this->itemResponse($response);
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
    }
