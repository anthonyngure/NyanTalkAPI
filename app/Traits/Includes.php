<?php
    /**
     * Created by PhpStorm.
     * User: antho
     * Date: 6/15/2018
     * Time: 3:14 PM
     */
    
    namespace App\Traits;
    
    
    use Illuminate\Http\Request;
    
    trait Includes
    {
        public static function withIncludes(Request $request, string $key = 'include')
        {
            if (!empty($request->input($key))) {
                return (new static)->newQuery()->with(
                    is_string($request->input($key)) ? func_get_args() : $request->input($key)
                );
            } else {
                return (new static)->newQuery();
            }
        }
    }
