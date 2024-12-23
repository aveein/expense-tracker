<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['transaction_income'] = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date,SUM(amount) as total_amount')->where('type','income')->groupBy('date')->pluck('total_amount','date');

        $data['transaction_expense'] = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date,ROUND(SUM(amount),2) as total_amount')->where('type','expense')->groupBy('date')->pluck('total_amount','date');

        $data['transaction_date'] =Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date')->groupBy('date')->pluck('date');


        $data['categories'] = Category::withSum(['transaction' => function ($query) {
             $query->where('type','income')->select(DB::raw('COALESCE(SUM(amount), 0)'));
        }], 'amount')->active()->pluck('transaction_sum_amount','title');

        $data['category_expense'] = Category::withSum(['transaction' => function ($query) {
            $query->where('type','expense')->select(DB::raw('COALESCE(SUM(amount), 0)'));
       }], 'amount')->active()->pluck('transaction_sum_amount','title');

       $data['transaction_income_total'] = Transaction::where('type','income')->sum('amount');

       $data['transaction_expense_total'] = Transaction::where('type','expense')->sum('amount');

       foreach($data['transaction_date'] as $date){
                $data['transaction_income'][$date] =  isset($data['transaction_income'][$date]) ?  $data['transaction_income'][$date] : 0;
                $data['transaction_expense'][$date] = isset($data['transaction_expense'][$date]) ?  $data['transaction_expense'][$date] : 0;
            }
        return view('home',$data);
    }

    public function getDateWise(Request $request){
        $format_date = $this->formatDate($request->dates);
        switch($request->type){
            case "month":
                $data['transaction_income'] = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date,SUM(amount) as total_amount')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->where('type','income')->groupBy('date')->pluck('total_amount','date');

                $data['transaction_expense'] = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date,ROUND(SUM(amount),2) as total_amount')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->where('type','expense')->groupBy('date')->pluck('total_amount','date');

                $data['transaction_date'] =Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as date')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->groupBy('date')->pluck('date');


                break;

            case "year":
                $data['transaction_income'] = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y") as date,SUM(amount) as total_amount')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->where('type','income')->groupBy('date')->pluck('total_amount','date');

                $data['transaction_expense'] = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y") as date,ROUND(SUM(amount),2) as total_amount')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->where('type','expense')->groupBy('date')->pluck('total_amount','date');

                $data['transaction_date'] =Transaction::selectRaw('DATE_FORMAT(created_at, "%Y") as date')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->groupBy('date')->pluck('date');



                break;

            default:
                $data['transaction_income'] = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date,SUM(amount) as total_amount')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->where('type','income')->groupBy('date')->pluck('total_amount','date');

                $data['transaction_expense'] = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date,ROUND(SUM(amount),2) as total_amount')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->where('type','expense')->groupBy('date')->pluck('total_amount','date');

                $data['transaction_date'] =Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date')->whereBetween('created_at',[$format_date['from_date'],$format_date['to_date']])->groupBy('date')->pluck('date');
                break;


        }

        foreach($data['transaction_date'] as $date){
            $data['transaction_income'][$date] =  isset($data['transaction_income'][$date]) ?  $data['transaction_income'][$date] : 0;
            $data['transaction_expense'][$date] = isset($data['transaction_expense'][$date]) ?  $data['transaction_expense'][$date] : 0;
        }
        return response()->json($data);
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
}
