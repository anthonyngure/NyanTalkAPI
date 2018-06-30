<?php
    
    namespace App\Http\Controllers\API;
    
    use App\BulkSms;
    use App\Channels\AfricasTalkingSMSChannel;
    use App\Http\Controllers\Controller;
    use App\User;
    use Auth;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Spatie\QueryBuilder\QueryBuilder;
    
    class BulkSmsController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request)
        {
            $bulkSms = QueryBuilder::for (BulkSms::class)
                ->whereHas('createdBy', function (Builder $query) {
                    $query->whereNull('deleted_at');
                })
                ->allowedIncludes(BulkSms::ALLOWED_INCLUDES);
            
            return $this->paginate($request, $bulkSms);
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            $this->validate($request, [
                'text' => 'required|max:160',
            ]);
            
            /** @var \App\BulkSms $bulkSms */
            $bulkSms = BulkSms::create([
                'text'          => $request->input('text'),
                'created_by_id' => Auth::user()->id,
            ]);
            
            $phoneNumbers = User::whereSmsNotifiable(true)->get(['phone'])->pluck('phone');
            
            //dd($phoneNumbers->all());
            
            if (count($phoneNumbers->all()) > 0) {
                AfricasTalkingSMSChannel::sendBulk($phoneNumbers->all(), $request->input('text'));
            }
            
            return $this->show($bulkSms->id);
            
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            $bulkSms = QueryBuilder::for (BulkSms::class)
                ->allowedIncludes(BulkSms::ALLOWED_INCLUDES)
                ->findOrFail($id);
            
            return $this->itemResponse($bulkSms);
        }
        
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  int                      $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            //
        }
        
        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            //
        }
    }
