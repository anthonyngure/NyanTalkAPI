<?php

namespace App\Notifications;

use App\Channels\AfricasTalkingSMSChannel;
use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCompletedNotification extends Notification
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
     * @param  mixed  $notifiable
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
     * @param  mixed  $notifiable
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
        return 'Hi ' . $notifiable->name . ', Your complain NO. '.$this->ticket->number.' has been solved. Please visit '
            .config('app.url').' to see responses and give us your feedback!';
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
