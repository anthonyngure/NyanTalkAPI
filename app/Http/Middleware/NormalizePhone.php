<?php
    
    namespace App\Http\Middleware;
    
    use App\Utils;
    use Closure;
    
    class NormalizePhone
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  \Closure                 $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
            if ($request->has('phone')) {
                $data = $request->all();
                $data['phone'] = Utils::phoneWithCode($request->input('phone'));
                $request->replace($data);
            }
            
            return $next($request);
        }
    }
