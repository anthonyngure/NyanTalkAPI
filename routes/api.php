<?php
    
    use Illuminate\Http\Request;
    
    /*
	|--------------------------------------------------------------------------
	| API Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register API routes for your application. These
	| routes are loaded by the RouteServiceProvider within a group which
	| is assigned the "api" middleware group. Enjoy building your API!
	|
	*/
    
    
    //sleep(1);
    
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
    
    
    Route::group(['namespace' => 'API', 'prefix' => 'v1', 'guard' => 'api'], function () {
        
        Route::group(['prefix' => 'auth'], function () {
            Route::post('signUp', 'AuthController@signUp');
            Route::post('signIn', 'AuthController@signIn');
            Route::post('facebook', 'AuthController@facebook');
            Route::post('verification', 'AuthController@verification');
            Route::any('code', 'AuthController@resendVerificationCode');
            Route::group(['prefix' => 'password'], function () {
                Route::post('recover', 'AuthController@recoverPassword');
                Route::post('reset', 'AuthController@resetPassword');
            });
        });
        Route::apiResource('subcounties', 'SubCountyController');
        Route::apiResource('subcounties.wards', 'WardController');
        Route::apiResource('wards', 'WardController2');
        
        Route::get('topics/create', 'TopicController@create');
        Route::apiResource('forums', 'ForumController');
        Route::get('noAuthTopics', 'TopicController@noAuthTopics');
        Route::get('topics/{id}', 'TopicController@show');
        Route::get('topics/{id}/contributions', 'TopicContributionController@index');
        Route::get('topics/{topicId}/contributions/{contributionId}', 'TopicContributionController@show');
        
        Route::group(['middleware' => 'auth:api'], function () {
            
            Route::post('auth/password/change', 'AuthController@changePassword');
            //Reports
            Route::group(['prefix' => 'auth'], function () {
                Route::get('/', 'AuthController@user');
                Route::put('/', 'AuthController@update');
                Route::any('signOut', 'AuthController@signOut');
                Route::delete('/', 'AuthController@delete');
            });
            
            Route::get('tickets/summary', 'TicketController@summary');
            Route::post('tickets/{id}/start', 'TicketController@start')->middleware('userType:OFFICIAL');
            Route::post('tickets/{ticketId}/assign/{officialId}', 'TicketController@assign')
                ->middleware('userType:ADMIN,DEPARTMENT_ADMIN');
            Route::post('tickets/{id}/complete', 'TicketController@complete')
                ->middleware('userType:OFFICIAL');
            Route::post('tickets/{id}/share', 'TicketController@share')
                ->middleware('userType:OFFICIAL');
            Route::post('tickets/{id}/approveShare', 'TicketController@approveShare')
                ->middleware('userType:ADMIN,DEPARTMENT_ADMIN');
            
            Route::resource('tickets', 'TicketController');
            Route::apiResource('tickets.responses', 'TicketResponseController');
            Route::apiResource('tickets.ratings', 'TicketRatingController');
            
            Route::get('topics', 'TopicController@index');
            Route::post('topics', 'TopicController@store');
            Route::put('topics', 'TopicController@store');
            Route::any('topics/{id}/reject', 'TopicController@reject')
                ->middleware('userType:ADMIN,DEPARTMENT_ADMIN,OFFICIAL');
            Route::any('topics/{id}/approve', 'TopicController@approve')
                ->middleware('userType:ADMIN,DEPARTMENT_ADMIN,OFFICIAL');
            Route::delete('topics', 'TopicController@store');
            
            Route::post('topics/{id}/contributions', 'TopicContributionController@store');
            
            Route::apiResource('users', 'UserController');
            Route::apiResource('bulkSms', 'BulkSmsController')
                ->middleware('userType:ADMIN,DEPARTMENT_ADMIN');
            
            Route::apiResource('categories', 'CategoryController');
            Route::apiResource('departments', 'DepartmentController');
            
            
        });
    });
