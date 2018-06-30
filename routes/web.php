<?php
    
    /*
	|--------------------------------------------------------------------------
	| Web Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register web routes for your application. These
	| routes are loaded by the RouteServiceProvider within a group which
	| contains the "web" middleware group. Now create something great!
	|
	*/
    
    use App\Sms\AfricasTalkingGateway;
    use App\Sms\AfricasTalkingGatewayException;
    use App\SmsMessage;
    use App\User;
    
    Route::get('/', function (\Illuminate\Http\Request $request) {
        
        
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:cache');
        //Artisan::call('session:clear');
        
        return view('welcome');
    });
    
    Route::get('sms', function (){
        // Send notification to the $notifiable instance...
        // Create a new instance of our awesome gateway class
        $gateway = new AfricasTalkingGateway(config('services.africastalking.username'),
            config('services.africastalking.key'));
        // Any gateway error will be captured by our custom Exception class below,
        // so wrap the call in a try-catch block
        try {
            // Thats it, hit send and we'll take care of the rest.
        
            //$results = $gateway->sendMessage('+254740665211', 'Test Sms');
            $results = $gateway->sendMessage('254740665211', 'Test Sms');
            foreach ($results as $result) {
                SmsMessage::create([
                    'number'      => $result->number,
                    'status'      => $result->status,
                    'status_code' => $result->statusCode,
                    'message_id'  => $result->messageId,
                    'cost'        => $result->cost,
                ]);
            }
            
            return $results;
        
        } catch (AfricasTalkingGatewayException $e) {
            echo "Encountered an error while sending: " . $e->getMessage();
            //Log::error($e->getMessage());
        }
    });
    
    Request::get('image', function () {
        $img = Image::make('foo.jpg')->resize(300, 200);
        
        return $img->response('jpg');
        
    });
    
    Route::get('test', function () {
        return 'Hello world';
    });
    
    //Route::get('stats', 'PragmaRX\Tracker\Vendor\Laravel\Controllers\Stats@index');
    
    //USSD
    Route::any('ussd', 'UssdSessionController@index');
    
    Auth::routes();
    
    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::get('/setup', function () {
        /** @var \App\User $user */
        $user = User::withTrashed()->whereName('Super Admin')->first();
        if (!$user) {
            User::create([
                'name'                    => 'Super Admin',
                'password'                => Hash::make('superadmin2018'),
                'email'                   => 'superadmin',
                'notifiable'              => false,
                'deletable'               => false,
                'phone_verified'          => true,
                'type'                    => User::TYPE_ADMIN,
                'phone_verification_code' => 9999,
            ]);
        } else if ($user->deleted_at) {
            $user->restore();
            $user->type = User::TYPE_ADMIN;
            $user->save();
        }
        
        return $user;
    })->name('setup');
    
    Route::group(['middleware' => 'auth:web', 'guard' => 'api'], function () {
    
    });
