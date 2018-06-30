<?php
    
    namespace App\Http\Middleware;
    
    use App\Exceptions\AppException;
    use App\User;
    use Auth;
    use Closure;
    
    class CheckUserType
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  \Closure                 $next
         * @param array                     $types
         * @return mixed
         * @throws \App\Exceptions\AppException
         */
        public function handle($request, Closure $next, ...$types)
        {
            //$roles = array_except(func_get_args(), [0,1]);
            //dd($roles);
            /** @var \App\User $user */
            $user = User::findOrFail(Auth::user()->id);
            
            if (in_array($user->type, $types)) {
                return $next($request);
            } else {
                throw new AppException("You are not authorized to perform the requested action");
            }
        }
    }
