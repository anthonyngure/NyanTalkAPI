<?php
    
    namespace App\Exceptions;
    
    class ErrorCodes
    {
        
        const BAD_REQUEST = 'BAD_REQUEST';
        const UNAUTHORIZED = 'UNAUTHORIZED';
        const TOKEN_MISSING = 'TOKEN_MISSING';
        const TOKEN_EXPIRED = 'TOKEN_EXPIRED';
        const TOKEN_INVALID = 'TOKEN_INVALID';
        const OBSOLETE_APP = 'OBSOLETE_APP';
        const SERVER = "SERVER";
        const VALIDATION = 'VALIDATION';
        const NOT_FOUND = 'NOT_FOUND';
        const PHONE_NOT_VERIFIED = 'PHONE_NOT_VERIFIED';
        
    }
