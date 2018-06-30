<?php
    
    namespace App\Traits;
    use Uuid;


    /**
     * Class Uuid.
     *
     * Manages the usage of creating UUID values for primary keys. Drop into your models as
     * per normal to use this functionality. Works right out of the box.
     *
     * Taken from: http://garrettstjohn.com/entry/using-uuids-laravel-eloquent-orm/
     */
    trait HasUuid
    {
        /**
         * The "booting" method of the model.
         */
        protected static function boot()
        {
            parent::boot();
            /**
             * Attach to the 'creating' Model Event to provide a UUID
             * for the `id` field (provided by $model->getKeyName()).
             */
            static::creating(function ($model) {
                $model->{$model->getKeyName()} = Uuid::generate()->string;
                
                return true;
            });
        }
    }
