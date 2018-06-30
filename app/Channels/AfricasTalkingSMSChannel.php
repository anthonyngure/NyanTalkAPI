<?php
    /**
     * Created by PhpStorm.
     * User: Tosh
     * Date: 18/05/2018
     * Time: 14:18
     */
    
    namespace App\Channels;
    
    
    use App\Sms\AfricasTalkingGateway;
    use App\Sms\AfricasTalkingGatewayException;
    use App\SmsMessage;
    use Illuminate\Notifications\Notification;
    use Log;
    
    class AfricasTalkingSMSChannel
    {
        /**
         * Send the given notification.
         *
         * @param  mixed | \App\User                      $notifiable
         * @param  \Illuminate\Notifications\Notification $notification
         * @return void
         */
        public function send($notifiable, Notification $notification)
        {
            $message = $notification->toSMS($notifiable);
            
            // Send notification to the $notifiable instance...
            // Create a new instance of our awesome gateway class
            $gateway = new AfricasTalkingGateway(config('services.africastalking.username'),
                config('services.africastalking.key'));
            // Any gateway error will be captured by our custom Exception class below,
            // so wrap the call in a try-catch block
            try {
                // Thats it, hit send and we'll take care of the rest.
                
                $results = $gateway->sendMessage($notifiable->routeNotificationForSMS(), $message);
                foreach ($results as $result) {
                    SmsMessage::create([
                        'number'      => $result->number,
                        'status'      => $result->status,
                        'status_code' => $result->statusCode,
                        'message_id'  => $result->messageId,
                        'cost'        => $result->cost,
                    ]);
                }
                
            } catch (AfricasTalkingGatewayException $e) {
                //echo "Encountered an error while sending: " . $e->getMessage();
                Log::error($e->getMessage());
            }
        }
        
        /**
         * @param array $phoneNumbers
         * @param       $message
         */
        public static function sendBulk(array $phoneNumbers, $message)
        {
            $gateway = new AfricasTalkingGateway(config('services.africastalking.username'),
                config('services.africastalking.key'));
            // Any gateway error will be captured by our custom Exception class below,
            // so wrap the call in a try-catch block
            try {
                // Thats it, hit send and we'll take care of the rest.
                
                $results = $gateway->sendMessage(implode(',', $phoneNumbers), $message);
                Log::info($results);
                foreach ($results as $result) {
                    SmsMessage::create([
                        'number'      => $result->number,
                        'status'      => $result->status,
                        'status_code' => $result->statusCode,
                        'message_id'  => $result->messageId,
                        'cost'        => $result->cost,
                    ]);
                }
                
            } catch (AfricasTalkingGatewayException $e) {
                //echo "Encountered an error while sending: " . $e->getMessage();
                Log::error($e->getMessage());
            }
        }
    }
