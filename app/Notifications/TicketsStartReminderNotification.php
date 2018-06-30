<?php
    
    namespace App\Notifications;
    
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    
    class TicketsStartReminderNotification extends Notification implements ShouldQueue
    {
        use Queueable;
        /**
         * @var \Illuminate\Database\Eloquent\Collection
         */
        private $tickets;
        
        /**
         * Create a new notification instance.
         * @param \Illuminate\Database\Eloquent\Collection $tickets
         */
        public function __construct(Collection $tickets)
        {
            
            $this->tickets = $tickets;
        }
        
        /**
         * Get the notification's delivery channels.
         *
         * @param  mixed $notifiable
         * @return array
         */
        public function via($notifiable)
        {
            return ['mail', 'database'];
        }
        
        /**
         * Get the mail representation of the notification.
         *
         * @param  mixed $notifiable
         * @return \Illuminate\Notifications\Messages\MailMessage
         */
        public function toMail($notifiable)
        {
            return (new MailMessage)
                ->greeting('Hi ' . $notifiable->name)
                ->subject('Tickets Assignment Reminder')
                ->line('This is to remind you there are ' . $this->tickets->count() . " pending to be started")
                ->action('View Tickets', '/');
        }
        
        /**
         * Get the array representation of the notification.
         *
         * @param  mixed|\App\User $notifiable
         * @return array
         */
        public function toArray($notifiable)
        {
            return [
                'ticketsCount' => $this->tickets->count(),
                'message'      => $this->tickets->count() . ' tickets pending to be started',
            ];
        }
    }
