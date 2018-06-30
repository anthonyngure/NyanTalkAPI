<?php
	/**
	 * Created by PhpStorm.
	 * User: Tosh
	 * Date: 18/01/2017
	 * Time: 17:44
	 */
	
	namespace App\Traits;
	
	
	use Illuminate\Database\Eloquent\Collection;
	use Illuminate\Http\Request;
	
	trait Paginates
	{
		/**
		 * @var int
		 */
		private $before;
		
		/**
		 * @var int
		 */
		private $after;
		
		/**
		 * @var int
		 */
		private $perPage;
		
		/**
		 * @var bool
		 */
		private $recent;
		
		
		/**
		 * @param \Illuminate\Http\Request                 $request
		 * @param                                          $builder
		 * @return \Illuminate\Http\Response
		 */
		protected function paginate(Request $request, $builder)
		{
			$this->readCursors($request);
			$this->addCursors($builder);
			
			$data = $builder->take($this->perPage)->get();
			
			$nextCursors = $this->buildNextCursors($data);
			
			
			$data = $data->toArray();
			
			
			return response()->json(array('meta'    => ['message' => 'Request successful'],
			                              'cursors' => $nextCursors, 'data' => $data));
			
		}
		
		private function readCursors(Request $request)
		{
			$this->validate($request, [
				'before'  => 'required|numeric',
				'after'   => 'required|numeric',
				'perPage' => 'required|numeric',
				'recent'  => 'required|in:true,false',
			]);
			
			$this->before = (int)$request->before;
			$this->after = (int)$request->after;
			$this->recent = ($request->recent == 'true');
			$this->perPage = (int)$request->perPage;
			
		}
		
		/**
		 * @param $builder
		 */
		private function addCursors($builder)
		{
			/*
			 * Loading data that is added after what the user is seeing
			 **/
			if ($this->recent || ($this->after == 0 && $this->before == 0)) {
				if ($this->after != 0) {
					$builder->where('id', '>', $this->after);
					$builder->orderBy('id', 'asc');
				} else {
					$builder->orderBy('id', 'desc');
				}
				
			} /*
             * Loading data added before what the user is seeing
             */
			else {
				$builder->where('id', '<', $this->before);
				$builder->orderBy('id', 'desc');
			}
		}
		
		private function buildNextCursors(Collection $collection)
		{
			/*
			 * If its recent maxId will change but if not recent minId remains what was sent
			 * But if beforeId is zero it has to change
			 */
			if ($this->recent) {
				$maxId = $collection->max('id');
				if ($this->before == 0) {
					$minId = $collection->min('id');
				} else {
					$minId = $this->before;
				}
			} else {
				if ($this->after == 0) {
					$maxId = $collection->max('id');
				} else {
					$maxId = $this->after;
				}
				$minId = $collection->min('id');
			}
			
			return array(
				'count'  => $collection->count(),
				'before' => (is_null($minId) ? (int)$this->before : (int)$minId),
				'after'  => (is_null($maxId) ? (int)$this->after : (int)$maxId),
			);
		}
	}
