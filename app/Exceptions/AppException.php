<?php
	/**
	 * Created by PhpStorm.
	 * User: Tosh
	 * Date: 23/12/2016
	 * Time: 10:24
	 */
	
	namespace App\Exceptions;
	
	
	use App\Delivery;
	
	class AppException extends \Exception
	{
		
		/**
		 * @var string
		 */
		private $errorMessage;
		/**
		 * @var string
		 */
		private $errorCode;
		/**
		 * @var array
		 */
		private $errorData;
		
		function __construct(string $message = 'An error occurred', string $code = ErrorCodes::BAD_REQUEST, array $data = array())
		{
			
			$this->errorMessage = $message;
			$this->errorCode = $code;
			$this->errorData = $data;
		}
		
		/**
		 * @return string
		 */
		public function getErrorMessage(): string
		{
			return $this->errorMessage;
		}
		
		/**
		 * @return string
		 */
		public function getErrorCode(): string
		{
			return $this->errorCode;
		}
		
		/**
		 * @return array
		 */
		public function getErrorData(): array
		{
			return $this->errorData;
		}
		
		
	}
