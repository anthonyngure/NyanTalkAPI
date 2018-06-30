<?php
    
    namespace App\Notifications;
    
    use App\Channels\AfricasTalkingSMSChannel;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    
    class AutoRegisteredNotification extends Notification implements ShouldQueue
    {
        use Queueable;
        
        private $password;
        
        /**
         * Create a new notification instance.
         *
         * @param $password
         */
        public function __construct($password)
        {
            //
            $this->password = $password;
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
            return 'Hi ' . $notifiable->name . ', We have created your account for '
                . config('app.name') . '. Your password is ' . $this->password .
                ' Please visit ' . config('app.url') . ' to login and use code '
                . $notifiable->phone_verification_code . ' to verify your number.';
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
