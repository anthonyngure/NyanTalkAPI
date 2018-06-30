<?php
    
    namespace App\Notifications;
    
    use App\Channels\AfricasTalkingSMSChannel;
    use App\Ticket;
    use Illuminate\Bus\Queueable;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    
    class TicketAssignedNotification extends Notification
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
            return ['mail'];
        }
        
        /**
         * Get the mail representation of the notification.
         *
         * @param  mixed $notifiable |\App\User
         * @return \Illuminate\Notifications\Messages\MailMessage
         */
        public function toMail($notifiable)
        {
            return (new MailMessage)
                ->greeting('Hi ' . $notifiable->name)
                ->subject('Assigned New Ticket')
                ->action('View Ticket', '/')
                ->line('You have been assigned a new ticket.');
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
                //
            ];
        }
    }
