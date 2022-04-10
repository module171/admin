<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\bill;
use App\Models\item;
use App\Models\order;
use App\Models\User;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $title = "bill";


        $getbill = bill::with('order.user')->get();

        return view('page.bill', compact('title', 'getbill'));
    }
    public function invoice(Request $request)
    {
        $getinvoice = bill::with('order.user')->find($request->id);
        $gettax=User::find(1);
        $getitem=bill::with(['order.item'=>function($query){

            $query->where('type_food',1);

             }])->find($request->id);

        $gettopping = bill::with(['order.item'=>function($query){

            $query->where('type_food',2);

             }])->find($request->id);

        return view('page.billinvoice', compact('getinvoice', 'getitem', 'gettopping','gettax'));
    }
}
