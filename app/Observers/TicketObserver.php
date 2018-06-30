<?php
    
    namespace App\Observers;
    
    use App\Notifications\TicketAssignedNotification;
    use App\Ticket;
    use App\User;
    use App\Utils;
    
    class TicketObserver
    {
        
        private function generateUniqueNumber()
        {
            do {
                //Generate a unique number that is not used
                $dateTimeString = now()->toDateTimeString();
                $dateTimeStringWithoutHyphens = str_replace('-', '', $dateTimeString);
                $dateTimeStringWithoutColons = str_replace(':', '', $dateTimeStringWithoutHyphens);
                $dateTimeStringWithoutSpaces = str_replace(' ', '', $dateTimeStringWithoutColons);
                $number = strtoupper(str_random(4)) . '_' . $dateTimeStringWithoutSpaces;
                $ticket = Ticket::whereNumber($number)->first();
            } while (!is_null($ticket));
            
            return $number;
        }
        
        /**
         * @param $departmentId
         * @return \Illuminate\Database\Eloquent\Model|null|\App\User
         */
        private function getOfficialToAssign($departmentId)
        {
            return User::whereType(User::TYPE_OFFICIAL)
                ->where('department_id', $departmentId)
                ->inRandomOrder()->first();
        }
        
        /**
         * Listen to the Ticket creating event.
         *
         * @param  Ticket $ticket
         * @return void
         * @throws \Exception
         */
        public function creating(Ticket $ticket)
        {
            $official = $this->getOfficialToAssign($ticket->department_id);
            $ticket->assigned_official_id = is_null($official) ? null : $official->id;
            $ticket->assigned_official_at = is_null($official) ? null : now()->toDateTimeString();
            $ticket->status = is_null($official) ? Ticket::STATUS_PENDING_ASSIGNMENT : Ticket::STATUS_ASSIGNED;
            $ticket->number = $this->generateUniqueNumber();
            //$ticket->id = Utils::generateUUID();
            
            if (!is_null($official)) {
                //Send assignment notification to the official
                //$official->notify(new TicketAssignedNotification($ticket));
            }
        }
        
        /**
         * Listen to the Ticket created event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function created(Ticket $ticket)
        {
            //code...
            
            
            //dd($ticket);
        }
        
        /**
         * Listen to the Ticket updating event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function updating(Ticket $ticket)
        {
            //code...
        }
        
        /**
         * Listen to the Ticket updated event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function updated(Ticket $ticket)
        {
            //code...
        }
        
        /**
         * Listen to the Ticket saving event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function saving(Ticket $ticket)
        {
            //code...
        }
        
        /**
         * Listen to the Ticket saved event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function saved(Ticket $ticket)
        {
            //code...
        }
        
        /**
         * Listen to the Ticket deleting event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function deleting(Ticket $ticket)
        {
            //code...
        }
        
        /**
         * Listen to the Ticket deleted event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function deleted(Ticket $ticket)
        {
            //code...
            $ticket->responses()->delete();
        }
        
        /**
         * Listen to the Ticket restoring event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function restoring(Ticket $ticket)
        {
            //code...
        }
        
        /**
         * Listen to the Ticket restored event.
         *
         * @param  Ticket $ticket
         * @return void
         */
        public function restored(Ticket $ticket)
        {
            //code...
        }
    }
