<?php
    /**
     * Created by PhpStorm.
     * User: Tosh
     * Date: 21/04/2017
     * Time: 09:47
     */
    
    namespace App;
    

    use Uuid;

    class Utils
    {
        
        
        /**
         * @param $phone
         * @return string
         */
        public static function phoneWithCode($phone)
        {
            if (empty($phone)) {
                return '';
            }
            
            if (strlen($phone) < 9) {
                return '';
            }
            
            return '254' . substr($phone, (strlen($phone) - 9));
        }
        
        public static function phoneWithoutCode($phone)
        {
            if (empty($phone)) {
                return '';
            }
            
            //254740665211
            if (strlen($phone) != 12) {
                return '';
            }
            
            return '0' . substr($phone, 3);
            
            
        }
    
        /**
         * @return string
         */
        public static function generateUUID()
        {
            return Uuid::generate()->string;
        }
        
        
        /**
         * @return int
         */
        public static function generateVerificationCode()
        {
            //return 9999;
            return random_int(1000, 9999);
        }
        
    }
