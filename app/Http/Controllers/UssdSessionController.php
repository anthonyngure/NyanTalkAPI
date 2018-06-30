<?php
    
    namespace App\Http\Controllers;
    
    use App\Department;
    use App\Notifications\AutoRegisteredNotification;
    use App\SubCounty;
    use App\Ticket;
    use App\User;
    use App\UssdSession;
    use App\Utils;
    use App\Ward;
    use Hash;
    use Illuminate\Http\Request;
    
    class UssdSessionController extends Controller
    {
        
        /**
         * @var \App\UssdSession
         */
        private $session;
        private $text;
        /**
         * @var \App\User
         */
        private $user;
        
        /**
         * UssdSessionController constructor.
         */
        public function __construct()
        {
        }
        
        public function index(Request $request)
        {
            // Reads the variables sent via POST from gateway
            $sessionId = $request->input('sessionId');
            $serviceCode = $request->input('serviceCode');
            $phoneNumber = empty($request->input('phoneNumber')) ? ''
                : Utils::phoneWithCode($request->input('phoneNumber'));
            
            if (!empty($request->input('text'))) {
                $texts = explode('*', $request->input('text'));
                $this->text = end($texts);
            } else {
                $this->text = '';
            }
            
            $text = $this->text;
            
            //dd($phoneNumber);
            
            /** @var \App\User $user */
            $this->user = User::wherePhone(Utils::phoneWithCode($phoneNumber))->first();
            
            /** @var \App\UssdSession $session */
            $this->session = UssdSession::firstOrCreate([
                'id' => $sessionId,
            ], [
                'code'    => $serviceCode,
                'phone'   => $phoneNumber,
                'user_id' => is_null($this->user) ? null : $this->user->getKey(),
            ]);
            
            $session = $this->session;
            
            //dd($session->status);
            
            //dd($session->isAtSelectSubCounty());
            
            
            if (empty($text) && empty($session->status)) {
                if ($this->user) {
                    return $this->selectDepartment();
                } else {
                    return $this->requestName();
                }
            } else if ($session->isTypingName()) {
                return $this->selectDepartment();
            } else if ($session->isSelectingDepartment()) {
                return $this->selectSubCounty();
            } else if ($session->isSelectingSubCounty()) {
                return $this->selectWard();
            } else if ($session->isSelectingWard()) {
                return $this->requestSubject();
            } else if ($session->isTypingSubject()) {
                return $this->requestTicket();
            } else if ($session->isTypingTicket()) {
                return $this->ticketReceived();
            } else {
                $response = 'END Thank you!';
                
                return $this->response($response);
            }
            
            
        }
        
        private function requestName(string $error = null)
        {
            if (!empty($error)) {
                $response = "CON Nyandarua County Complains \n";
                $response .= "$error\n";
            } else {
                $response = "CON Nyandarua County Complains \n";
                $response .= "Please enter your name \n";
            }
            $this->session->status = UssdSession::STATUS_TYPING_NAME;
            
            return $this->response($response);
        }
        
        
        private function selectDepartment(string $error = null)
        {
            //Validate name
            if ($this->session->isTypingName() && empty($this->text)) {
                return $this->requestName("You must enter a valid name!");
            } else {
                $departments = Department::all(['id', 'name']);
                if (!empty($error)) {
                    $response = "CON " . $error . " \n";
                } else if ($this->user) {
                    $response = "CON Nyandarua County Complains \n";
                    $response .= 'Hi ' . $this->user->name . "\n";
                } else {
                    $response = "CON Please select department of interest \n";
                }
                foreach ($departments as $department) {
                    $response .= $department->id . '. ' . $department->name . "\n";
                }
                $this->session->selected_name = $this->text;
                $this->session->status = UssdSession::STATUS_SELECTING_DEPARTMENT;
                
                return $this->response($response);
            }
        }
        
        
        private function selectSubCounty(string $error = null)
        {
            //Validate department
            //Have to validate subcounty
            if (!is_numeric($this->text)) {
                return $this->selectDepartment('Invalid DEPARTMENT selected!');
            } else {
                //Load the department
                $department = Department::whereId((int)$this->text)->first();
                if (is_null($department)) {
                    return $this->selectDepartment('Invalid DEPARTMENT selected!');
                }
            }
            
            
            $subCounties = SubCounty::all(['id', 'name']);
            if (!empty($error)) {
                $response = "CON " . $error . "\n";
            } else {
                $response = "CON Please select you sub county\n";
            }
            foreach ($subCounties as $subCounty) {
                $response .= $subCounty->id . '. ' . $subCounty->name . "\n";
            }
            
            $this->session->selected_department_id = $this->text;
            
            $this->session->status = UssdSession::STATUS_SELECTING_SUB_COUNTY;
            
            return $this->response($response);
        }
        
        private function selectWard(string $error = null)
        {
            if ($this->session->isSelectingSubCounty()) {
                //Have to validate subcounty
                if (!is_numeric($this->text)) {
                    return $this->selectSubCounty('Invalid SUB COUNTY selected!');
                } else {
                    //Load the subCounty
                    $subCounty = SubCounty::with('wards')->whereId((int)$this->text)->first();
                    if (is_null($subCounty)) {
                        return $this->selectSubCounty('Invalid SUB COUNTY selected!');
                    } else {
                        if (!empty($error)) {
                            $response = "CON " . $error . "\n";
                        } else {
                            $response = "CON Please select your ward \n";
                        }
                        foreach ($subCounty->wards as $ward) {
                            $response .= $ward->id . '. ' . $ward->name . "\n";
                        }
                        
                        $this->session->selected_sub_county_id = $subCounty->getKey();
                        
                        $this->session->status = UssdSession::STATUS_SELECTING_WARD;
                        
                        return $this->response($response);
                    }
                }
            } else if ($this->session->isSelectingWard()) {
                //Will not validate sub county
                //Get sub county from session
                $subCounty = SubCounty::whereId($this->session->selected_sub_county_id)->first();
                if (is_null($subCounty)) {
                    $response = 'END Sorry un able to process your request!' . "\n";
                    
                    return $this->response($response);
                }
                if (!empty($error)) {
                    $response = "CON " . $error . "\n";
                } else {
                    $response = "CON Select your ward \n";
                }
                foreach ($subCounty->wards as $ward) {
                    $response .= $ward->id . '. ' . $ward->name . "\n";
                }
                
                $this->session->status = UssdSession::STATUS_SELECTING_WARD;
                
                return $this->response($response);
            } else {
                $response = 'END Sorry un able to process your request!' . "\n";
                
                return $this->response($response);
            }
            
        }
        
        private function requestSubject(string $error = null)
        {
            if ($this->session->isTypingSubject() && !empty($error)) {
                //Text should no be empty
                $response = "CON Enter subject of your complain ? \n";
                $response .= $error . "\n";
                $this->session->status = UssdSession::STATUS_TYPING_SUBJECT;
                
                return $this->response($response);
            } else {
                //Is selecting ward
                //Text should be a ward id
                if (!is_numeric($this->text)) {
                    return $this->selectWard('Invalid WARD selected!-' . $this->text);
                } else {
                    //Load the subCounty
                    $ward = Ward::whereId((int)$this->text)->first();
                    if (is_null($ward)) {
                        return $this->selectWard('Invalid WARD selected!-' . $this->text);
                    } else {
                        $response = "CON Enter subject of your complain? \n";
                        
                        $this->session->selected_ward_id = $ward->id;
                        $this->session->status = UssdSession::STATUS_TYPING_SUBJECT;
                        
                        return $this->response($response);
                    }
                }
            }
            
        }
        
        
        private function requestTicket(string $error = null)
        {
            if ($this->session->isTypingTicket() && !empty($error)) {
                //Text should no be empty
                $response = "CON Enter details of your complain ? \n";
                $response .= $error . "\n";
                $this->session->status = UssdSession::STATUS_TYPING_TICKET;
                
                return $this->response($response);
            } else {
                $response = "CON Enter details of your complain ? \n";
                
                $this->session->selected_subject = $this->text;
                
                $this->session->status = UssdSession::STATUS_TYPING_TICKET;
                
                return $this->response($response);
            }
            
        }
        
        private function ticketReceived()
        {
            if (empty($this->text)) {
                return $this->requestTicket("You did not write anything!");
            } else {
                //Create the user if necessary
                //Register the user and send an sms to them
                //Generate password
                
                if (is_null($this->user)) {
                    $password = random_int(1000, 9999);
                    /** @var \App\User $citizen */
                    $citizen = User::create([
                        'phone'    => Utils::phoneWithCode($this->session->phone),
                        'name'     => $this->session->selected_name,
                        'password' => Hash::make($password),
                    ]);
                    $citizen->phone_verification_code = Utils::generateVerificationCode();
                    $citizen->save();
                    
                    $citizen->notify(new AutoRegisteredNotification($password));
                }
                
                //Create the ticket
                
                Ticket::create([
                    'citizen_id'    => is_null($this->user) ? $citizen->getKey() : $this->user->getKey(),
                    'department_id' => $this->session->selected_department_id,
                    'ward_id'       => $this->session->selected_ward_id,
                    'subject'       => $this->session->selected_subject,
                    'details'       => $this->text,
                ]);
                
                
                $response = 'END Thank you for sending your complain!' . "\n";
                
                return $this->response($response);
                
            }
        }
        
        private function response($response)
        {
            $this->session->save();
            
            //$response .= $this->session->status . "\n";
            
            // Print the response onto the page so that our gateway can read it
            return response($response, 200, ['Content-type: text/plain']);
            // DONE!!!
        }
    }
