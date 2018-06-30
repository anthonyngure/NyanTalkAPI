<?php
    
    namespace App\Observers;
    
    use App\Notifications\TicketResponseNotification;
    use App\Response;
    use App\User;
    use App\Utils;
    use Auth;

    class ResponseObserver
    {
        
        /**
         * Listen to the Response creating event.
         *
         * @param  Response $response
         * @return void
         * @throws \Exception
         */
        public function creating(Response $response)
        {
        }
        
        /**
         * Listen to the Response created event.
         *
         * @param  Response $response
         * @return void
         */
        public function created(Response $response)
        {
        
        }
        
        /**
         * Listen to the Response updating event.
         *
         * @param  Response $response
         * @return void
         */
        public function updating(Response $response)
        {
            //code...
        }
        
        /**
         * Listen to the Response updated event.
         *
         * @param  Response $response
         * @return void
         */
        public function updated(Response $response)
        {
            //code...
        }
        
        /**
         * Listen to the Response saving event.
         *
         * @param  Response $response
         * @return void
         */
        public function saving(Response $response)
        {
            //code...
        }
        
        /**
         * Listen to the Response saved event.
         *
         * @param  Response $response
         * @return void
         */
        public function saved(Response $response)
        {
            //code...
        }
        
        /**
         * Listen to the Response deleting event.
         *
         * @param  Response $response
         * @return void
         */
        public function deleting(Response $response)
        {
            //code...
        }
        
        /**
         * Listen to the Response deleted event.
         *
         * @param  Response $response
         * @return void
         */
        public function deleted(Response $response)
        {
            //code...
            $response->tickets()->delete();
        }
        
        /**
         * Listen to the Response restoring event.
         *
         * @param  Response $response
         * @return void
         */
        public function restoring(Response $response)
        {
            //code...
        }
        
        /**
         * Listen to the Response restored event.
         *
         * @param  Response $response
         * @return void
         */
        public function restored(Response $response)
        {
            //code...
        }
    }
