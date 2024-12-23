<?php

namespace App\Http\Controllers;

use App\DataTables\CategoriesDataTable;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //


        // return $dataTable->render('categories.index');

        return view('categories.index');


    }

    public function data(Request $request){
        $model = Category::select('id','title','status','created_at');

       return DataTables::eloquent($model)
                ->editColumn('status', '{{ $status ? "Active" : "Inactive" }} ')
                ->editColumn('action', function($data){

                   return view('categories.actions',compact('data'));
                })
                ->editColumn('created_at', '{{ $created_at ? \Carbon\Carbon::parse($created_at)->format("Y-m-d H:m:s") : "" }}')

                ->filter(function ($query) {
                   if(request()->has('search')){
                        if(Str::lower((request()->search['value'])) == 'active'){
                            $query->orWhere('status',1);
                        }
                   }


                },true)
                ->toJson();
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        //
        try{
            Category::create([
                'title' => $request->title,
                'status'=>isset($request->status) ? $request->status : false
            ]);

            return response()->json([
                'message'=>'Successfully Created!',
                'reset' => true
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Went Wrong!'
            ],500);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $category = Category::find($id);

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        //
        try{
        Category::where('id',$id)->update([
            'title' => $request->title,
            'status'=>isset($request->status) ? $request->status : false
        ]);
        return response()->json([
            'message'=>'Successfully updated!',
            'reset' => false
        ]);
        }
        catch(\Exception $e){
            return response()->json([
                'message'=>'Something Went Wrong!'
            ],500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        //
        try{
            Category::destroy($id);
            return response()->json('Successfully Deleted');
            }
            catch(\Exception $e){
                return response()->json([
                    'message'=>'Something Went Wrong!'
                ],500);
            }
    }
}
