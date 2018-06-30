<?php
	
	namespace App\Rules;
	
	use Illuminate\Contracts\Validation\Rule;
	
	class CommaSeparatedIds implements Rule
	{
		/**
		 * @var string
		 */
		private $model;
		/**
		 * @var string
		 */
		private $idColumnName;
		private $errorMessage;
		
		/**
		 * Create a new rule instance.
		 *
		 * @param string $model
		 * @param string $idColumnName
		 */
		public function __construct(string $model, string $idColumnName = 'id')
		{
			//
			$this->model = $model;
			$this->idColumnName = $idColumnName;
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
			if (empty($value)) {
				return true;
			}
			$ids = explode(',', $value);
			
			$collection = collect($ids);
			$unique = $collection->unique();
			
			if (count($unique->values()->all()) != count($ids)) {
				$this->errorMessage = "There are duplicated ids";
				
				return false;
			}
			
			foreach ($ids as $id) {
				if (!$this->model::where($this->idColumnName, $id)->exists()) {
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
			return empty($this->errorMessage) ? 'The :attribute has invalid ids.' : $this->errorMessage;
		}
	}
