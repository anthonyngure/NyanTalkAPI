<?php
    /**
     * Created by PhpStorm.
     * User: Tosh
     * Date: 17/01/2017
     * Time: 20:10
     */
    
    namespace App\Http\Controllers\API;
    
    use App\Exceptions\AppException;
    use App\Exceptions\ErrorCodes;
    use App\Http\Controllers\Controller;
    use App\Notifications\PasswordRecoveryCodeNotification;
    use App\Notifications\VerificationCodeNotification;
    use App\Rules\Phone;
    use App\User;
    use App\Utils;
    use Auth;
    use Carbon\Carbon;
    use Hash;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;
    use JWTAuth;
    
    class AuthController extends Controller
    {
        
        /**
         * @param string     $userId
         * @param array|null $meta
         * @return \Illuminate\Http\Response
         */
        private function authenticateUser(string $userId, array $meta = null)
        {
            /** @var User $user */
            $user = User::with(['ward.subCounty'])->withTrashed()->withCount(User::COUNTS)->findOrFail($userId);
            $token = JWTAuth::fromUser($user);
            
            //Restore the user
            if ($user->deleted_at) {
                $user->restore();
            }
            
            $user->token = $token;
            
            return $this->itemResponse($user, $meta == null ? array() : $meta)
                ->header('Authorization', $token);
        }
        
        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function facebook(Request $request)
        {
            //Make sure facebook id is availed first
            $this->validate($request, [
                'facebookId' => 'required|numeric',
            ]);
            
            $user = User::withTrashed()->whereFacebookId($request->input('facebookId'))
                ->orWhere('email', $request->input('email'))
                ->first();
            
            
            //We cannot use update or create because the user could have signed up with his email,
            // so we authenticate the user who owns the email
            
            if (is_null($user)) {
                $this->validate($request, [
                    'email'      => 'nullable|unique:users',
                    'gender'     => 'nullable|in:male,female',
                    'facebookId' => 'required|numeric',
                    'name'       => 'required',
                ]);
                
                /** @var User $user */
                $user = User::create([
                    'email'                => $request->input('email'),
                    'facebook_id'          => $request->input('facebookId'),
                    'name'                 => $request->input('name'),
                    'facebook_picture_url' => $request->input('photoUrl'),
                    'gender'               => $request->input('gender'),
                ]);
            }
            
            
            return $this->authenticateUser($user->id, ['message' => 'Signed in successfully!']);
        }
        
        
        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         * @throws \App\Exceptions\AppException
         */
        public function signUp(Request $request)
        {
            $this->validate($request, [
                'phone'                   => ['required', 'numeric', new Phone()],
                'subCountyId'             => 'nullable|exists:sub_counties,id',
                'wardId'                  => 'nullable|exists:wards,id',
                'name'                    => 'required|string|max:50',
                'password'                => 'required|string',
                'password_confirmation'   => 'required|string|same:password',
                'smsNotificationsEnabled' => 'required|boolean',
            ]);
            
            
            $user = User::withTrashed()->wherePhone(Utils::phoneWithCode($request->input('phone')))->first();
            if (!is_null($user) && ($user->phone_verified == false)) {
                $message = $request->input('phone') . ' is not verified!';
                $data = [
                    'phone' => Utils::phoneWithoutCode($request->input('phone')),
                ];
                throw new AppException($message, ErrorCodes::PHONE_NOT_VERIFIED, $data);
            } else if (!is_null($user) && $user->phone_verified == true) {
                $message = Utils::phoneWithoutCode($request->input('phone')) . ' has already been taken, If you did not Sign Up please contact us for help.!';
                $data = [
                    'phone' => Utils::phoneWithoutCode($request->input('phone')),
                ];
                throw new AppException($message, ErrorCodes::BAD_REQUEST, $data);
            }
            
            /** @var \App\User $user */
            $user = User::create([
                'phone'          => Utils::phoneWithCode($request->input('phone')),
                'name'           => $request->input('name'),
                'ward_id'        => $request->input('wardId'),
                'password'       => Hash::make($request->input('password')),
                'sms_notifiable' => $request->input('smsNotificationsEnabled'),
            ]);
            
            
            $user->phone_verification_code = Utils::generateVerificationCode();
            $user->save();
            
            //Send a code to verify the phone
            $user->notify(new VerificationCodeNotification());
            
            $data = [
                'phone' => Utils::phoneWithoutCode($user->phone),
            ];
            
            return $this->arrayResponse($data, ['message' => 'Pending phone number verification.']);
            
        }
        
        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         * @throws \App\Exceptions\AppException
         */
        public function signIn(Request $request)
        {
            
            $this->validate($request, [
                'password' => 'required',
                'signInId' => 'required', //Can be email or password
            ], [
                'signInId.required' => 'Phone or email address is required',
            ]);
            
            $user = User::withTrashed()->wherePhone(Utils::phoneWithCode($request->input('signInId')))
                ->orWhere('email', $request->input('signInId'))->first();
            
            if (is_null($user)) {
                throw new AppException($request->input('signInId') . ' is not a registered phone number or email address!');
            }
            
            //Check if user phone is verified
            //This is applicable when the sign in id provided is the phone number of this user
            if (!$user->phone_verified && ($user->phone == Utils::phoneWithCode($request->input('signInId')))) {
                $message = $request->input('signInId') . ' is not verified!';
                $data = [
                    'phone' => $request->input('signInId'),
                ];
                throw new AppException($message, ErrorCodes::PHONE_NOT_VERIFIED, $data);
            }
            
            //Check if password is correct
            if (!Hash::check($request->input('password'), $user->password)) {
                
                if (!empty($user->facebook_id) && $request->input('signInId') == $user->email) {
                    
                    //The user is Signed In with facebook and tried to Sign In with the email
                    
                    $message = $request->input('signInId') . ' is linked through Facebook ' .
                        'and has no password for  , please login with facebook to access your and set a ' .
                        'password to be able to Sign In with ' . $request->input('signInId') . ' and password!';
                } else {
                    $message = "You entered a wrong password for " . $request->input('signInId');
                }
                
                throw new AppException($message);
            }
            
            
            return $this->authenticateUser($user->id, ['message' => 'Signed in successfully!']);
        }
        
        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         * @throws \App\Exceptions\AppException
         */
        public function verification(Request $request)
        {
            $this->validate($request, [
                'phone' => ['required', 'numeric', new Phone()],
                'code'  => 'required|digits:4',
            ]);
            
            /** @var User $user */
            $user = User::withTrashed()->wherePhone($request->input('phone'))->first();
            
            if ($user == null) {
                $errorMessage = $request->input('phone') . ' is not a registered phone number,'
                    . ' Please use your correct phone number or create an account!';
                throw new AppException($errorMessage);
            }
            if ($user->phone_verification_code != $request->input('code')) {
                $message = 'The verification code entered was wrong!';
                throw new AppException($message);
            }
            
            $user->phone_verified = true;
            
            $user->save();
            
            return $this->authenticateUser($user->id, ['message' => 'Signed successfully.']);
            
        }
        
        public function user()
        {
            $user = User::with(['ward.subCounty'])->withTrashed()->withCount(User::COUNTS)->findOrFail(Auth::user()->id);
            
            return $this->itemResponse($user);
            
        }
        
        /**
         * @return \Illuminate\Http\Response
         * @throws \Exception
         */
        public function delete()
        {
            $user = Auth::user();
            $user->delete();
            
            return $this->itemResponse($user);
        }
        
        /**
         * @return \Illuminate\Http\Response
         */
        public function signOut()
        {
            $user = Auth::user();
            Auth::logout();
            
            return $this->itemResponse($user);
        }
        
        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         * @throws \App\Exceptions\AppException
         */
        public function resendVerificationCode(Request $request)
        {
            
            
            $this->validate($request, [
                'phone' => ['required', 'numeric', new Phone()],
            ]);
            
            //Try to find the user with the given phone
            $user = User::withTrashed()->wherePhone($request->input('phone'))->first();
            if (empty($user)) {
                //User was not found with the phone
                $message = $request->input('phone') . ' is not a registered phone number.' .
                    ' Enter the email you registered with or use your phone number to Sign Up for  !';
                throw new AppException($message);
            }
            
            if (!empty($user->phone_verification_code_sent_at)) {
                if (now()->diffInMinutes(Carbon::createFromTimeString($user->phone_verification_code_sent_at)) < 10) {
                    $message = 'Please wait at least 10 minutes to request to resend your code!';
                    throw new AppException($message);
                }
            }
            
            
            $user->phone_verified = false;
            $user->phone_verification_code_sent_at = now()->toDateTimeString();
            
            $user->save();
            
            $user->notify(new VerificationCodeNotification());
            
            $data = [
                'phone' => Utils::phoneWithoutCode($user->phone),
            ];
            
            return $this->arrayResponse($data);
            
        }
        
        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         * @throws \App\Exceptions\AppException
         */
        public function recoverPassword(Request $request)
        {
            $this->validate($request, [
                'phone' => ['required', 'numeric', new Phone()],
            ]);
            
            //Try to find the user with the given phone
            $user = User::withTrashed()->wherePhone($request->input('phone'))->first();
            if (empty($user)) {
                //User was not found with the phone
                $message = $request->input('phone') . ' is not a registered phone number.' .
                    ' Enter the email you registered with or use your phone number to Sign Up for  !';
                throw new AppException($message);
            }
            $user->password_recovery_code = Utils::generateVerificationCode();
            $user->save();
            
            //We found the user with the phone
            $user->notify(new PasswordRecoveryCodeNotification());
            
            $data = [
                'phone' => Utils::phoneWithoutCode($user->phone),
            ];
            
            return $this->arrayResponse($data);
            
        }
        
        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         * @throws \App\Exceptions\AppException
         */
        public function resetPassword(Request $request)
        {
            $this->validate($request, [
                'phone'                 => ['required', 'exists:users', 'numeric', new Phone()],
                'code'                  => 'required|digits:4',
                'password'              => 'required|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);
            
            /** @var User $user */
            $user = User::withTrashed()->wherePhone($request->input('phone'))->firstOrFail();
            if ($user->password_recovery_code != $request->input('code')) {
                $message = 'The verification code entered was wrong!';
                throw new AppException($message);
            }
            
            $user->password = Hash::make($request->input('password'));
            $user->save();
            
            return $this->authenticateUser($user->getKey(), ['message' => 'Password reset successfully!']);
            
            
        }
        
        
        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         * @throws \App\Exceptions\AppException
         */
        public function changePassword(Request $request)
        {
            
            //dd($request->all());
            
            $this->validate($request, [
                'currentPassword'          => 'required',
                'newPassword'              => 'required|string|confirmed',
                'newPassword_confirmation' => 'required|same:newPassword',
            ]);
            
            $user = Auth::user();
            
            if (!Hash::check($request->input('currentPassword'), $user->password)) {
                $message = 'Unable to change your password because the provided current password is not correct';
                throw new AppException($message);
            }
            
            $user->password = Hash::make($request->input('newPassword'));
            $user->save();
            
            
            return $this->user();
            
        }
        
        public function update(Request $request)
        {
            $user = Auth::user();
            $this->validate($request, [
                'phone'                   => ['required', 'numeric', Rule::unique('users')->ignore($user->getKey()), new Phone()],
                'name'                    => 'required|max:50',
                'smsNotificationsEnabled' => 'required|boolean',
                'email'                   => ['nullable', 'email', Rule::unique('users')->ignore($user->getKey())],
            ]);
            
            $user->phone = $request->input('phone');
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->sms_notifiable = $request->input('smsNotificationsEnabled');
            $user->save();
            
            return $this->user();
            
        }
        
    }
