<?php
    
    namespace App\Exceptions;
    
    use Encore\Admin\Reporter\Reporter;
    use Exception;
    use Illuminate\Auth\AuthenticationException;
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
    use Illuminate\Validation\ValidationException;
    use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
    
    class Handler extends ExceptionHandler
    {
        /**
         * A list of the exception types that should not be reported.
         *
         * @var array
         */
        protected $dontReport = [
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Symfony\Component\HttpKernel\Exception\HttpException::class,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            \Illuminate\Session\TokenMismatchException::class,
            \Illuminate\Validation\ValidationException::class,
        ];
        
        /**
         * Report or log an exception.
         *
         * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
         *
         * @param  \Exception $exception
         * @return void
         * @throws \Exception
         */
        public function report(Exception $exception)
        {
            parent::report($exception);
        }
        
        /**
         * Render an exception into an HTTP response.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  \Exception               $exception
         * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
         */
        public function render($request, Exception $exception)
        {
            if (($exception instanceof InvalidIncludeQuery && $request->expectsJson())) {
                $meta = ['message' => $exception->getMessage(), 'code' => ErrorCodes::BAD_REQUEST];
                $response = array('meta' => $meta, "data" => []);
                
                return response()->json($response, 400);
            } else if (($exception instanceof ModelNotFoundException)
                && $request->expectsJson()) {
                $message = str_replace('model', '', $exception->getMessage());
                $message = str_replace('App', '', $message);
                $message = str_replace('query', '', $message);
                $message = str_replace('[', '', $message);
                $message = str_replace(']', '', $message);
                $message = str_replace('\\', '', $message);
                $message = preg_replace('/[0-9]+/', '', $message);
                $meta = ['message' => $message, 'code' => ErrorCodes::NOT_FOUND];
                $response = array('meta' => $meta, "data" => ((object)array()));
                
                return response()->json($response, 404);
            } elseif ($exception instanceof AppException && $request->expectsJson()) {
                $meta = array('code' => $exception->getErrorCode(), 'message' => $exception->getErrorMessage());
                if (empty($exception->getErrorData())) {
                    $data = (object)array();
                } else {
                    $data = $exception->getErrorData();
                }
                
                return response()->json(['meta' => $meta, 'data' => $data], 400);
                
            } elseif ($exception instanceof ValidationException && $request->expectsJson()) {
                $meta = ['message' => 'Validation error!', 'code' => ErrorCodes::VALIDATION];
                $response = array('meta' => $meta, "data" => $exception->validator->getMessageBag()->toArray());
                
                return response()->json($response, 400);
                
            } elseif ($exception instanceof AuthenticationException && $request->expectsJson()) {
                $meta = array('message' => $exception->getMessage(), 'code' => ErrorCodes::UNAUTHORIZED);
                
                return response()->json(['meta' => $meta, 'data' => (object)array()], 401);
                
            } else {
                //$data = array('message' => $exception->getMessage(), 'file' => $exception->getFile(), 'line' => $exception->getLine());
                //$data = parent::render($request, $exception);
                //return response()->json($data, 500);
                return parent::render($request, $exception);
            }
        }
        
        /**
         * Convert an authentication exception into an unauthenticated response.
         *
         * @param  \Illuminate\Http\Request                 $request
         * @param  \Illuminate\Auth\AuthenticationException $exception
         * @return \Illuminate\Http\Response
         */
        protected function unauthenticated($request, AuthenticationException $exception)
        {
    
            //$meta = array('message' => $exception->getMessage(), 'code' => ErrorCodes::UNAUTHORIZED);
            //return response()->json(['data' => (object)array(), 'meta' => $meta], 401);
            
            if ($request->expectsJson()) {
                //return response()->json(['error' => 'Unauthenticated.'], 401);
                $meta = array('message' => $exception->getMessage(), 'code' => ErrorCodes::UNAUTHORIZED);
                
                return response()->json(['data' => (object)array(), 'meta' => $meta], 401);
            } else {
                return redirect()->guest(route('login'));
            }
        }
    }
