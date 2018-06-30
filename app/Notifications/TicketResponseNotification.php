<?php
    
    namespace App\Notifications;
    
    use App\Channels\AfricasTalkingSMSChannel;
    use App\Response;
    use App\Ticket;
    use App\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    
    class TicketResponseNotification extends Notification
    {
        use Queueable;
        /**
         * @var \App\Response
         */
        private $response;
        /**
         * @var \App\User
         */
        private $official;
        /**
         * @var \App\Ticket
         */
        private $ticket;
        
        
        /**
         * Create a new notification instance.
         * @param \App\Ticket   $ticket
         * @param \App\Response $response
         * @param \App\User     $official
         */
        public function __construct(Ticket $ticket, Response $response, User $official)
        {
            $this->response = $response;
            $this->official = $official;
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
            return ['database', AfricasTalkingSMSChannel::class];
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
                ->line('The introduction to the notification.')
                ->action('Notification Action', url('/'))
                ->line('Thank you for using our application!');
        }
        
        /**
         * Get the mail representation of the notification.
         *
         * @param  mixed|\App\User $notifiable
         * @return string
         */
        public function toSMS($notifiable)
        {
            $message = 'Hi ';
            $message .= $notifiable->name;
            $message .= ', ';
            $message .= $this->official->name;
            $message .= ' Replied: ';
            $message .= $this->response->details;
            
            return $message;
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
                'response_id' => $this->response->id,
                'ticket_id'   => $this->ticket->id,
            ];
        }
    }
