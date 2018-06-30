<?php
    
    namespace App\Observers;
    
    use App\Department;
    use App\User;
    use App\Utils;
    
    class DepartmentObserver
    {
        
        /**
         * Listen to the Department creating event.
         *
         * @param  Department $department
         * @return void
         * @throws \Exception
         */
        public function creating(Department $department)
        {
        }
        
        /**
         * Listen to the Department created event.
         *
         * @param  Department $department
         * @return void
         */
        public function created(Department $department)
        {
            //code...
            
            
            //dd($department);
        }
        
        /**
         * Listen to the Department updating event.
         *
         * @param  Department $department
         * @return void
         */
        public function updating(Department $department)
        {
            //code...
        }
        
        /**
         * Listen to the Department updated event.
         *
         * @param  Department $department
         * @return void
         */
        public function updated(Department $department)
        {
            //code...
        }
        
        /**
         * Listen to the Department saving event.
         *
         * @param  Department $department
         * @return void
         */
        public function saving(Department $department)
        {
            //code...
        }
        
        /**
         * Listen to the Department saved event.
         *
         * @param  Department $department
         * @return void
         */
        public function saved(Department $department)
        {
            //code...
        }
        
        /**
         * Listen to the Department deleting event.
         *
         * @param  Department $department
         * @return void
         */
        public function deleting(Department $department)
        {
            //code...
        }
        
        /**
         * Listen to the Department deleted event.
         *
         * @param  Department $department
         * @return void
         */
        public function deleted(Department $department)
        {
            //code...
            $department->tickets()->delete();
        }
        
        /**
         * Listen to the Department restoring event.
         *
         * @param  Department $department
         * @return void
         */
        public function restoring(Department $department)
        {
            //code...
        }
        
        /**
         * Listen to the Department restored event.
         *
         * @param  Department $department
         * @return void
         */
        public function restored(Department $department)
        {
            //code...
        }
    }
