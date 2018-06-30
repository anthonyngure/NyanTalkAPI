<?php
    
    namespace App\Notifications;
    
    use App\Channels\AfricasTalkingSMSChannel;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    
    class VerificationCodeNotification extends Notification implements ShouldQueue
    {
        use Queueable;
        
        /**
         * Create a new notification instance.
         *
         * @return void
         */
        public function __construct()
        {
            //
        }
        
        /**
         * Get the notification's delivery channels.
         *
         * @param  mixed $notifiable
         * @return array
         */
        public function via($notifiable)
        {
            //return ['mail'];
            return [AfricasTalkingSMSChannel::class];
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
         * @param mixed | \App\User $notifiable
         * @return string
         */
        public function toSMS($notifiable)
        {
            return $notifiable->phone_verification_code . ' is your verification code';
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
