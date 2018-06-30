<?php
    /**
     * Created by PhpStorm.
     * User: antho
     * Date: 6/22/2018
     * Time: 11:14 AM
     */
    
    namespace App;
    
    
    use App\Notifications\TicketsAssignmentReminderNotification;

    class ScheduleManager
    {
        public static function notifyDelayedTickets()
        {
            //Get tickets not assigned
            $tickets = Ticket::whereStatus(Ticket::STATUS_PENDING_ASSIGNMENT)
                ->get();
            
            //Remind all the admins to Assign the tickets if they have not been assigned 1hr since they were created
            User::whereType(User::TYPE_ADMIN)->get()->each(function (User $user) use ($tickets) {
                $user->notify(new TicketsAssignmentReminderNotification($tickets));
            });
            
        }
    }
