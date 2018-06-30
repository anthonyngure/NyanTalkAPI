<?php
    
    namespace App\Providers;
    
    use App\Department;
    use App\Observers\DepartmentObserver;
    use App\Observers\ResponseObserver;
    use App\Observers\TicketObserver;
    use App\Observers\UserObserver;
    use App\Response;
    use App\Ticket;
    use App\User;
    use Illuminate\Support\ServiceProvider;
    
    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot()
        {
            User::observe(UserObserver::class);
            Ticket::observe(TicketObserver::class);
            Department::observe(DepartmentObserver::class);
            Response::observe(ResponseObserver::class);
            
            \Schema::defaultStringLength(255);
        }
        
        /**
         * Register any application services.
         *
         * @return void
         */
        public function register()
        {
            //
        }
    }
