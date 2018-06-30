<?php
	
	namespace App\Http\Controllers;
	
	use App\Traits\Paginates;
	use Illuminate\Database\Eloquent\Collection;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
	use Illuminate\Foundation\Bus\DispatchesJobs;
	use Illuminate\Foundation\Validation\ValidatesRequests;
	use Illuminate\Routing\Controller as BaseController;
	
	class Controller extends BaseController
	{
		use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Paginates;
		
		
		/**
		 * @param Model $item
		 * @param array $meta
		 * @return \Illuminate\Http\Response
		 */
		protected function itemResponse(Model $item, array $meta = ['message' => 'Request successful.'])
		{
			return response()->json(array('meta' => $meta, 'data' => $item));
		}
		
		/**
		 * @param Collection $collection
		 * @param array      $meta
		 * @return \Illuminate\Http\Response
		 */
		protected function collectionResponse(Collection $collection, array $meta = ['message' => 'Request successful.'])
		{
			return response()->json(array('meta' => $meta, 'data' => $collection));
		}
		
		
		/**
		 * @param \Illuminate\Database\Eloquent\Model $item
		 * @param array                               $meta
		 * @return \Illuminate\Http\Response
		 */
		public function itemCreatedResponse(Model $item, array $meta = ['message' => 'Request successful.'])
		{
			return response()->json(array('meta' => $meta, 'data' => $item));
		}
		
		/**
		 * @param \Illuminate\Database\Eloquent\Model $item
		 * @param array                               $meta
		 * @return \Illuminate\Http\Response
		 */
		public function itemUpdatedResponse(Model $item, array $meta = ['message' => 'Request successful.'])
		{
			return response()->json(array('meta' => $meta, 'data' => $item));
		}
		
		/**
		 * @param \Illuminate\Database\Eloquent\Model $item
		 * @param array                               $meta
		 * @return \Illuminate\Http\Response
		 */
		public function itemDeletedResponse(Model $item, array $meta = ['message' => 'Request successful.'])
		{
			return response()->json(array('meta' => $meta, 'data' => $item));
		}
		
		/**
		 * @param array $data
		 * @param array $meta
		 * @return \Illuminate\Http\Response
		 */
		public function arrayResponse(array $data, array $meta = ['message' => 'Request successful.'])
		{
			return response()->json(array('meta' => $meta, 'data' => $data));
		}
	}
