<?php
    
    namespace App\Http\Controllers\API;
    
    use App\Contribution;
    use App\Http\Controllers\Controller;
    use App\Topic;
    use Auth;
    use Illuminate\Http\Request;
    use Spatie\QueryBuilder\QueryBuilder;
    
    class TopicContributionController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @param \Illuminate\Http\Request $request
         * @param                          $topicId
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request, $topicId)
        {
            $contributions = QueryBuilder::for (Contribution::class)
                ->allowedIncludes(Contribution::ALLOWED_INCLUDES)
                ->where('topic_id', $topicId);
            
            return $this->paginate($request, $contributions);
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param                           $topicId
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request, $topicId)
        {
            $this->validate($request, [
                'text' => 'required|max:255',
            ]);
            
            /** @var \App\Topic $topic */
            $topic = Topic::findOrFail($topicId);
            /** @var Contribution $contribution */
            $contribution = $topic->contributions()->save(new Contribution([
                'user_id' => Auth::user()->getKey(),
                'text'    => $request->input('text'),
            ]));
            
            return $this->show($topicId, $contribution->id);
        }
        
        /**
         * Display the specified resource.
         *
         * @param $topicId
         * @param $contributionId
         * @return \Illuminate\Http\Response
         */
        public function show($topicId, $contributionId)
        {
            $contribution = QueryBuilder::for (Contribution::class)
                ->allowedIncludes(Contribution::ALLOWED_INCLUDES)
                ->where('topic_id', $topicId)
                ->findOrFail($contributionId);
            
            return $this->itemResponse($contribution);
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
