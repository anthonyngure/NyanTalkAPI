<?php
    
    namespace App\Http\Controllers\API;
    
    use App\Forum;
    use App\Http\Controllers\Controller;
    use App\Topic;
    use Auth;
    use Illuminate\Http\Request;
    use Spatie\QueryBuilder\QueryBuilder;
    
    class TopicController extends Controller
    {
        /**
         * TopicController constructor.
         */
        public function __construct()
        {
        }
        
        
        public function noAuthTopics(Request $request)
        {
            $this->validate($request, [
                'filter' => 'required|in:PENDING_APPROVAL,APPROVED,REJECTED,ALL,pending_approval,approved,rejected,all',
            ]);
            $topics = QueryBuilder::for (Topic::class)
                ->allowedIncludes(Topic::ALLOWED_INCLUDES)
                ->withCount(Topic::COUNTS)
                ->where('status', Topic::STATUS_APPROVED);
            
            if (strtoupper($request->input('filter')) != 'ALL') {
                $topics = $topics->where('status', strtoupper($request->input('filter')));
            }
            
            return $this->paginate($request, $topics);
        }
        
        /**
         * Display a listing of the resource.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request)
        {
            $this->validate($request, [
                'filter' => 'required|in:PENDING_APPROVAL,APPROVED,REJECTED,ALL,pending_approval,approved,rejected,all',
            ]);
            $topics = QueryBuilder::for (Topic::class)
                ->withCount(Topic::COUNTS)
                ->allowedIncludes(Topic::ALLOWED_INCLUDES);
            
            $user = Auth::user();
            if ($user->isCitizen()) {
                $topics = $topics->where('status', Topic::STATUS_APPROVED);
            }
            
            if (strtoupper($request->input('filter')) != 'ALL') {
                $topics = $topics->where('status', strtoupper($request->input('filter')));
            }
            
            return $this->paginate($request, $topics);
        }
        
        /**
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $forums = Forum::all();
            
            return $this->collectionResponse($forums);
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
                'forumId'     => 'required|exists:forums,id',
                'title'       => 'required|string|max:50',
                'description' => 'required|string|max:255',
            ]);
            
            $user = Auth::user();
            $topic = $user->topics()->firstOrCreate([
                'title'       => $request->input('title'),
                'description' => $request->input('description'),
            ], [
                'forum_id' => $request->input('forumId'),
            ]);
            
            return $this->itemCreatedResponse($topic);
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            $topic = QueryBuilder::for (Topic::class)
                ->withCount(Topic::COUNTS)
                ->allowedIncludes(Topic::ALLOWED_INCLUDES)
                ->findOrFail($id);
            
            return $this->itemResponse($topic);
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
        
        
        /**
         * @param $id
         * @return \Illuminate\Http\Response
         */
        public function reject($id)
        {
            /** @var \App\Topic $topic */
            $topic = Topic::findOrFail($id);
            $topic->status = Topic::STATUS_REJECTED;
            $topic->rejected_at = now()->toDateTimeString();
            $topic->rejected_by = Auth::user()->id;
            $topic->save();
            
            return $this->show($id);
        }
        
        /**
         * @param $id
         * @return \Illuminate\Http\Response
         */
        public function approve($id)
        {
            /** @var \App\Topic $topic */
            $topic = Topic::findOrFail($id);
            $topic->status = Topic::STATUS_APPROVED;
            $topic->approved_at = now()->toDateTimeString();
            $topic->approved_by = Auth::user()->id;
            $topic->save();
            
            return $this->show($id);
        }
    }
