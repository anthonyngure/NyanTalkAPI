<?php
    
    namespace App\Http\Controllers\API;
    
    use App\Department;
    use App\Http\Controllers\Controller;
    use Auth;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;
    use Spatie\QueryBuilder\QueryBuilder;
    
    class DepartmentController extends Controller
    {
        /**
         * DepartmentController constructor.
         */
        public function __construct()
        {
            $this->middleware('userType:ADMIN');
        }
        
        
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $departments = QueryBuilder::for (Department::class)
                ->allowedIncludes(Department::ALLOWED_INCLUDES)
                ->get();
            
            return $this->collectionResponse($departments);
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
                'name'        => ['required', 'max:191'],
                'description' => ['nullable'],
            ]);
            
            $department = Department::create([
                'name'        => $request->input('name'),
                'description' => $request->input('description'),
                'created_by_id'  => Auth::user()->id,
            ]);
            
            return $this->show($department->id);
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            $department = QueryBuilder::for (Department::class)
                ->allowedIncludes(Department::ALLOWED_INCLUDES)
                ->findOrFail($id);
            
            return $this->itemResponse($department);
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
            $this->validate($request, [
                'name'        => ['required', Rule::unique('departments')->ignore($id), 'max:191'],
                'description' => ['nullable'],
            ]);
            
            /** @var \App\Department $department */
            $department = Department::findOrFail($id);
            $department->name = $request->input('name');
            $department->description = $request->input('description');
            $department->last_edited_by = Auth::user()->id;
            $department->save();
            
            return $this->show($department->id);
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
            /** @var \App\Department $department */
            $department = Department::findOrFail($id);
            
            $deleted = $department->delete();
            if ($deleted) {
                $department->deleted_by_id = Auth::user()->id;
                $department->save();
            }
            
            return $this->itemResponse($department);
        }
    }
