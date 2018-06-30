<?php
	
	namespace App\Rules;
	
	use Illuminate\Contracts\Validation\Rule;
	
	class AllowedIncludes implements Rule
	{
		/**
		 * @var array
		 */
		private $includes;
		
		/**
		 * Create a new rule instance.
		 *
		 * @param array $includes
		 */
		public function __construct(array $includes)
		{
			//
			$this->includes = $includes;
		}
		
		/**
		 * Determine if the validation rule passes.
		 *
		 * @param  string $attribute
		 * @param  mixed  $value
		 * @return bool
		 */
		public function passes($attribute, $value)
		{
			//
			$giveIncludes = explode(',', $value);
			foreach ($giveIncludes as $include) {
				if (!in_array($include, $this->includes)) {
					return false;
				}
			}
			
			return true;
		}
		
		/**
		 * Get the validation error message.
		 *
		 * @return string
		 */
		public function message()
		{
			return 'The :attribute has an invalid value.';
		}
	}
