<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\bill;
use App\Models\categories;
use App\Models\item;
use App\Models\order;
use App\Models\promocode;
use App\Models\shipper;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;
use Session;
class AdminController extends Controller
{

      public $data=[];
    public function index(){


        $user=count(User::where('type',2)->get());
        $shipper=count(shipper::all());
        $item=count(item::all());
        $bill=count(bill::all());
        $categories=count(categories::all());
        $total_order=count(order::whereDay('created_at', date("d"))->get());
        $unsettled_orders=count(order::where('status','!=',4)->whereDay('created_at', date("d"))->get());
        $settled_orders=count(order::where('status',4)->whereDay('created_at', date("d"))->get());
        $total_money=order::where('status',4)->whereDay('created_at', date("d"))->sum('order_total');
        $total_money_month=order::where('status',4)->whereMonth('created_at', date("m"))->sum('order_total');
        $order_tax=order::where('status',4)->whereMonth('created_at', date("m"))->sum('tax_amount');
        $promocode=count(promocode::all());
        $data=['user'=>$user,
        'shipper'=>$shipper,
        'item'=>$item,
        'bill'=>$bill,
        'category'=>$categories,
        'unset_today'=>$unsettled_orders,
        'set_today'=>$settled_orders,
        'total_order_today'=>$total_order,
        'promocode'=>$promocode,
        'total_money'=>$total_money,
        'order_tax'=>$order_tax,
         'total_money_month'=>$total_money_month

    ];
        $title='Dashboard';
        return view('page.dashboard',compact('title','data'));
    }

    public function login(){

        $this->data['title']='login';
        return view('page.login',$this->data);

    }
    public function logout(){

        Auth::logout();
        return 1;

    }
    public function checklogin(Request $request){

        $validation = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',

          ]);
          $error_array = array();
          $success_output = 1;
          if ($validation->fails())
          {
              foreach($validation->messages()->getMessages() as $field_name => $messages)
              {
                  $error_array[] = $messages;
              }
          }
          else
          {
        $data = [
            'email' => $request->email,
            'password' =>$request->password ,
        ];
        $val = $request->only(['email', 'password']);

        if (Auth::attempt($val)) {
            //true
            $success_output = 1;
        } else {
            $success_output = 0;
            //false
        }
    }
    $output = array(
        'error'     =>  $error_array,
        'success'   =>  $success_output
    );
    echo json_encode($output);


    }
    public function settings(request $request)
    {
        $validation = Validator::make($request->all(), [
            'tax'=>'required',
            'delivery_charge'=>'required'
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else
        {
            $setting = User::where('id', Auth::user()->id)->update( array('tax'=>$request->tax, 'delivery_charge'=>$request->delivery_charge) );

            if ($setting) {
                Session::flash('message', '<div class="alert alert-success"><strong>Success!</strong> Data updated.!! </div>');
            } else {
                $error_array[]="Please try again";
            }
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        return json_encode($output);

    }
    public function changePassword(request $request)
    {
        $validation = \Validator::make($request->all(), [
            'oldpassword'=>'required|min:6',
            'newpassword'=>'required|min:6',
            'confirmpassword'=>'required_with:newpassword|same:newpassword|min:6',
        ],[
            'oldpassword.required'=>'Old Password is required',
            'newpassword.required'=>'New Password is required',
            'confirmpassword.required'=>'Confirm Password is required'
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else if($request['oldpassword']==$request['newpassword'])
        {
            $error_array[]='Old and new password must be different';
        }
        else
        {
            if(\Hash::check($request->oldpassword,Auth::user()->password)){
                $data['password'] = Hash::make($request->newpassword);
                User::where('id', Auth::user()->id)->update($data);
                Session::flash('message', '<div class="alert alert-success"><strong>Success!</strong> Password has been changed.!! </div>');

            }else{
                $error_array[]="Old Password is not match.";
            }
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        return json_encode($output);

    }
}
