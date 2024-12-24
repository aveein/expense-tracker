<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['categories'] = Category::active()->get();

        return view('transactions.index',$data);
    }

    public function data(){


        $model = Transaction::query()->with('category');

        return DataTables::eloquent($model)
                 ->editColumn('category_id', function($data){
                    return $data->category->title;
                 })
                 ->editColumn('image', function($data){
                    $image = asset('storage/'.$data->image);

                    return $data->image ? "<img src=$image width='80%' height='50px'>" : 'No Image';
                 })
                 ->editColumn('action', function($data){

                    return view('transactions.actions',compact('data'));
                 })
                 ->editColumn('created_at', '{{ $created_at ? \Carbon\Carbon::parse($created_at)->format("Y-m-d H:m:s") : "" }}')
                 ->editColumn('type', '<span class="badge px-2 bg-label-{{ $type == "income" ? "success" : "danger"}}" text-capitalized="">{{$type}}</span> ')

                 ->filter(function ($query) {




                    if(request()->has('category_id') && !is_null(request()->category_id) ){
                         $query->where('category_id',request()->category_id);
                    }

                    if(request()->has('date')){
                        $format_date = $this->formatDate(request()->date);

                        $query->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']]);
                    }


                 },true)
                 ->escapeColumns([])
                ->make(true);
    }

    public function formatDate($date){

        $two_date = explode(' - ',$date);

        $data['from_date'] = explode('/',$two_date[0]);
        $data['to_date'] = explode('/',$two_date[1]);
        return [
            'from_date'=>end($data['from_date']).'-'.head($data['from_date']).'-'.$data['from_date'][1]." 00:00:00",
            'to_date'=>end($data['to_date']).'-'.head($data['to_date']).'-'.$data['to_date'][1]." 23:59:59",
        ];

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        //
        try{
            if($request->has('image')) $path= $this->uploadImage($request->file('image'));

            Transaction::create([
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'created_by' => Auth::id(),
                'type' => $request->type,
                'image' => $path ?? null,
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

        return response()->json(Transaction::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, string $id)
    {
        //

        try{
            $transaction = Transaction::findOrFail($id);



            if($request->has('image')) $path= $this->uploadImage($request->file('image'),$transaction->image);

            $transaction->update([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'image' => $path ?? null,
            ]);

            return response()->json([
                'message'=>'Successfully Created!',
                'reset' => false
            ]);
        }catch(\Exception $e){
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
            Transaction::destroy($id);

            return response()->json([
                'message'=>'Successfully Deleted!'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Went Wrong!'
            ],500);
        }

    }
}
