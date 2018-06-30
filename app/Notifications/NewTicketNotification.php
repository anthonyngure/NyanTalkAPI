<?php
    
    namespace App\Notifications;
    
    use App\Ticket;
    use Illuminate\Bus\Queueable;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    
    class NewTicketNotification extends Notification
    {
        use Queueable;
        /**
         * @var \App\Ticket
         */
        private $ticket;
        
        /**
         * Create a new notification instance.
         *
         * @param \App\Ticket $ticket
         */
        public function __construct(Ticket $ticket)
        {
            //
            $this->ticket = $ticket;
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
         * @param  mixed|\App\User $notifiable
         * @return \Illuminate\Notifications\Messages\MailMessage
         */
        public function toMail($notifiable)
        {
            return (new MailMessage)
                ->greeting('Hi ' . $notifiable->name . ',')
                ->line('A new ticket has been added.')
                ->action('View Now', url('/'));
        }
        
        /**
         * Get the array representation of the notification.
         *
         * @param  mixed $notifiable
         * @return array
         */
        public function toArray($notifiable)
        {
            return [
                'ticket_id' => $this->ticket->id,
            ];
        }
    }
