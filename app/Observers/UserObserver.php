<?php
    
    namespace App\Observers;
    
    use App\User;
    use Avatar;
    
    class UserObserver
    {
        
        /**
         * Listen to the User creating event.
         *
         * @param  User $user
         * @return void
         * @throws \Exception
         */
        public function creating(User $user)
        {
            //code...
        }
        
        /**
         * Listen to the User created event.
         *
         * @param  User $user
         * @return void
         */
        public function created(User $user)
        {
            //code...
            //Avatar::create($user->name)->save('/storage/public/app/avatars/'.$user->id.'.jpg', 100); // quality = 100
            $path = storage_path('app/public/avatars/') . $user->id . '.jpg';
            Avatar::create($user->name)->save($path, 100); // quality = 100
            $user->avatar = 'storage/avatars/' . $user->id . '.jpg';
            $user->save();
        }
        
        /**
         * Listen to the User updating event.
         *
         * @param  User $user
         * @return void
         */
        public function updating(User $user)
        {
            //code...
        }
        
        /**
         * Listen to the User updated event.
         *
         * @param  User $user
         * @return void
         */
        public function updated(User $user)
        {
            //code...
        }
        
        /**
         * Listen to the User saving event.
         *
         * @param  User $user
         * @return void
         */
        public function saving(User $user)
        {
            //code...
        }
        
        /**
         * Listen to the User saved event.
         *
         * @param  User $user
         * @return void
         */
        public function saved(User $user)
        {
            //code...
        }
        
        /**
         * Listen to the User deleting event.
         *
         * @param  User $user
         * @return void
         */
        public function deleting(User $user)
        {
            //code...
        }
        
        /**
         * Listen to the User deleted event.
         *
         * @param  User $user
         * @return void
         */
        public function deleted(User $user)
        {
            //code...
            
            $user->tickets()->delete();
            $user->responses()->delete();
        }
        
        /**
         * Listen to the User restoring event.
         *
         * @param  User $user
         * @return void
         */
        public function restoring(User $user)
        {
            //code...
        }
        
        /**
         * Listen to the User restored event.
         *
         * @param  User $user
         * @return void
         */
        public function restored(User $user)
        {
            //code...
        }
    }
