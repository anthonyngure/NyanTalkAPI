<?php
    
    namespace App\Http\Controllers\API;
    
    use App\Http\Controllers\Controller;
    use App\Ticket;
    use Auth;
    use Illuminate\Http\Request;
    
    class TicketRatingController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            //
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request, $ticketId)
        {
            //
            
            $this->validate($request, [
                'text'  => 'required',
                'stars' => 'required|numeric|max:5|min:1',
            ]);
            
            /** @var \App\Ticket $ticket */
            $ticket = Ticket::findOrFail($ticketId);
            
            $user = Auth::user();
            
            $rating = $ticket->rating()->create([
                'user_id' => $user->id,
                'stars'   => $request->input('stars'),
                'text'    => $request->input('text'),
            ]);
            
            return $this->itemResponse($rating);
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //
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
