<?php
    
    namespace App\Http\Controllers\API;
    
    use App\Forum;
    use App\Http\Controllers\Controller;
    use Auth;
    use Illuminate\Http\Request;
    use Spatie\QueryBuilder\QueryBuilder;
    
    class ForumController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $forums = Forum::withCount(Forum::COUNTS)->get();
            
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
            //
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            $forum = QueryBuilder::for (Forum::class)
                ->withCount(Forum::COUNTS)
                ->allowedIncludes(Forum::ALLOWED_INCLUDES)
                ->findOrFail($id);
            
            return $this->itemResponse($forum);
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
            /** @var \App\Forum $forum */
            $forum = Forum::findOrFail($id);
            $this->validate($request, [
                'name' => 'required|string|max:191',
            ]);
            $forum->name = $request->input('name');
            
            return $this->show($forum->id);
        }
        
        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         * @throws \Exception
         */
        public function destroy($id)
        {
            $forum = Forum::findOrFail($id);
            $deleted = $forum->delete();
            if ($deleted) {
                $forum->deleted_by_id = Auth::user()->id;
            }
            
            return $this->itemDeletedResponse($forum);
        }
    }
