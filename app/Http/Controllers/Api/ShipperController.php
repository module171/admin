<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Order;
use App\Addons;
use App\Models\order as ModelsOrder;
use App\Models\orderdetail;
use App\Models\shipper;
use App\Models\User as ModelsUser;
use App\OrderDetails;
use Validator;

class ShipperController extends Controller
{
    public function login(Request $request )
    {
        if($request->email == ""){
            return response()->json(["status"=>0,"message"=>"Email id is required"],400);
        }
        if($request->password == ""){
            return response()->json(["status"=>0,"message"=>"Password is required"],400);
        }

        $login=shipper::where('email',$request['email'])->first();

        if(!empty($login))
        {
            if($login->status == '1')
            {
                if(Hash::check($request->get('password'),$login->password))
                {
                    $arrayName = array(
                        'id' => $login->id,
                        'name' => $login->name,
                        'mobile' => $login->mobile,
                        'email' => $login->email,
                    );
                    // $login->fcm_token = '';
                    $data=array('user'=>$arrayName);
                    $status=1;
                    $message='Login Successful';

                    $data_token['token'] = $request['token'];
                    $update=shipper::where('email',$request['email'])->update($data_token);

                    return response()->json(['status'=>$status,'message'=>$message,'data'=>$arrayName],200);
                }
                else
                {
                    $status=0;
                    $message='Password is incorrect';
                    return response()->json(['status'=>$status,'message'=>$message],422);
                }
            }
            else
            {
                $status=0;
                $message='Your account has been blocked by Admin';
                return response()->json(['status'=>$status,'message'=>$message],422);
            }
        }
        else
        {
            $status=0;
            $message='Email is incorrect';
            $data="";
            return response()->json(['status'=>$status,'message'=>$message],422);
        }


        return response()->json(['status'=>$status,'message'=>$message,'data'=>$data],200);
    }

    public function getprofile(Request $request )
    {
        if($request->user_id == ""){
            return response()->json(["status"=>0,"message"=>"User ID is required"],400);
        }

        $users = shipper::where('id',$request['user_id'])->get()->first();

        if ($users->mobile == "") {
            $mobile = "";
        } else {
            $mobile = $users->mobile;
        }

        $arrayName = array(
            'id' => $users->id,
            'name' => $users->name,
            'mobile' => $mobile,
            'email' => $users->email,
            'profile_image' => url('/public/images/profile/'.$users->profile_image)
        );


        if(!empty($arrayName))
        {
            return response()->json(['status'=>1,'message'=>'Profile data','data'=>$arrayName],200);
        } else {
            $status=0;
            $message='No User found';
            $data="";
            return response()->json(['status'=>$status,'message'=>$message],422);
        }

        return response()->json(['status'=>$status,'message'=>$message,'data'=>$data],200);
    }

    public function editprofile(Request $request )
    {
        if($request->user_id == ""){
            return response()->json(["status"=>0,"message"=>"User ID is required"],400);
        }
        if($request->name == ""){
            return response()->json(["status"=>0,"message"=>"Name is required"],400);
        }

        $user = new shipper();
        $user->exists = true;
        $user->id = $request->user_id;

        if(isset($request->image)){
            if($request->hasFile('image')){
                $image = $request->file('image');
                $image = 'profile-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move('public/images/profile', $image);
                $user->profile_image=$image;
            }
        }
        $user->name =$request->name;
        $user->save();

        if($user)
        {
            return response()->json(['status'=>1,'message'=>'Profile has been updated'],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'Something went wrong'],400);
        }
    }

    public function changepassword(Request $request)
    {
        if($request->user_id == ""){
            return response()->json(["status"=>0,"message"=>"User is required"],400);
        }
        if($request->old_password == ""){
            return response()->json(["status"=>0,"message"=>"Old Password is required"],400);
        }
        if($request->new_password == ""){
            return response()->json(["status"=>0,"message"=>"New Password is required"],400);
        }
        if($request['old_password']==$request['new_password'])
        {
            return response()->json(['status'=>0,'message'=>'Old and new password must be different'],400);
        }
        $check_user=shipper::where('id',$request['user_id'])->get()->first();
        if(Hash::check($request['old_password'],@$check_user->password))
        {
            $data['password']=Hash::make($request['new_password']);
            $update=shipper::where('id',$request['user_id'])->update($data);
            return response()->json(['status'=>1,'message'=>'Password Updated'],200);
        }
        else{
            return response()->json(['status'=>0,'message'=>'Incorrect Password'],400);
        }
    }

    public function forgotPassword(Request $request)
    {
        if($request->email == ""){
            return response()->json(["status"=>0,"message"=>"Email id is required"],400);
        }

        $checklogin=shipper::where('email',$request['email'])->first();

        if(empty($checklogin))
        {
            return response()->json(['status'=>0,'message'=>'Email does not exist'],400);
        }
        else {
            try{
                $password = mt_rand(100000, 999999);
                $newpassword['password'] = Hash::make($password);
                $update = shipper::where('email', $request['email'])->update($newpassword);

                $title='Password Reset';
                $email=$checklogin->email;
                $data=['title'=>$title,'email'=>$email,'password'=>$password];

                Mail::send('Email.email',$data,function($message)use($data){
                    $message->from(env('MAIL_USERNAME'))->subject($data['title']);
                    $message->to($data['email']);
                } );
                return response()->json(['status'=>1,'message'=>'New Password Sent to your email address'],200);
            }catch(\Swift_TransportException $e){
                $response = $e->getMessage() ;
                return response()->json(['status'=>0,'message'=>'Something went wrong while sending email Please try again...'],200);
            }
        }

    }

    public function ongoingorder(Request $request)
    {
        if($request->driver_id == ""){
            return response()->json(["status"=>0,"message"=>"Driver ID is required"],400);
        }

        $checkuser=shipper::where('id',$request->driver_id)->first();

        if($checkuser->status == '1')
        {
            \DB::statement("SET SQL_MODE=''");
            $cartdata=orderdetail::select('order.order_total as total_price',DB::raw('SUM(order_detail.qly) AS qly'),'order.id','order.order_number','order.status','order.payment_type',DB::raw('DATE_FORMAT(order.created_at, "%d-%m-%Y") as date'))
            ->join('item','order_detail.item_id','=','item.id')
            ->join('order','order_detail.order_id','=','order.id')
            ->where('order.delivery_id',$request->driver_id)->where('order.status','3')->groupBy('order_detail.order_id')->orderBy('order.created_at','DESC')->get();

            $completed_order=ModelsOrder::where('order.status','4')->where('order.delivery_id',$request->driver_id)
            ->count();

            $ongoing_order=ModelsOrder::where('order.status','3')->where('order.delivery_id',$request->driver_id)
            ->count();

            if(!empty($cartdata))
            {
                return response()->json(['status'=>1,'message'=>'Order history list Successful','completed_order'=>$completed_order,'ongoing_order'=>$ongoing_order,'currency'=>env('CURRENCY'),'data'=>$cartdata],200);
            }
            else
            {
                return response()->json(['status'=>0,'message'=>'No data found'],200);
            }
        } else {
            $status=2;
            $message='Your account has been blocked by Admin';
            return response()->json(['status'=>$status,'message'=>$message],422);
        }
    }

    public function orderhistory(Request $request)
    {
        if($request->driver_id == ""){
            return response()->json(["status"=>0,"message"=>"Driver ID is required"],400);
        }
        \DB::statement("SET SQL_MODE=''");
        $cartdata=orderdetail::select('order.order_total as total_price',DB::raw('SUM(order_detail.qly) AS qly'),'order.id','order.order_number','order.status','order.payment_type',DB::raw('DATE_FORMAT(order.created_at, "%d-%m-%Y") as date'))
        ->join('item','order_detail.item_id','=','item.id')
        ->join('order','order_detail.order_id','=','order.id')
        ->where('order.delivery_id',$request->driver_id)->where('order.status','4')->groupBy('order_detail.order_id')->orderBy('order.created_at','DESC')->get();


        $completed_order=ModelsOrder::where('order.status','4')->where('order.delivery_id',$request->driver_id)
        ->count();

        $ongoing_order=ModelsOrder::where('order.status','3')->where('order.delivery_id',$request->driver_id)
        ->count();

        if(!empty($cartdata))
        {
            return response()->json(['status'=>1,'message'=>'Order history list Successful','completed_order'=>$completed_order,'ongoing_order'=>$ongoing_order,'data'=>$cartdata],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
    }

    public function getorderdetails(Request $request)
    {
        if($request->order_id == ""){
            return response()->json(["status"=>0,"message"=>"Order Number is required"],400);
        }

        $cartdata=orderdetail::with('itemimage')->select( 'order_detail.qly','order.order_total as total_price','item.id','item.item_name','item.item_price','order_detail.item_id')
        ->join('item','order_detail.item_id','=','item.id')
        ->join('order','order_detail.order_id','=','order.id')
        ->where('type_food',1)
        ->where('order_detail.order_id',$request->order_id)->get()->toArray();

        $status=ModelsOrder::select('order.address','order.promotecode','order.discount_amount','order.order_number','order.status','order.order_notes','order.delivery_charge','users.name',\DB::raw("CONCAT('".url('/public/images/profile/')."/', users.profile_image) AS profile_image"),'users.mobile')->where('order.id',$request['order_id'])
        ->join('users','order.user_id','=','users.id')
        ->get()->first();
       $topping=orderdetail::with('itemimage')->select( 'order_detail.qly','order.order_total as total_price','item.id','item.item_name','item.item_price','order_detail.item_id')
       ->join('item','order_detail.item_id','=','item.id')
       ->join('order','order_detail.order_id','=','order.id')
       ->where('order_detail.order_id',$request->order_id)
       ->where('type_food',2)
       ->get()->toArray();
        $taxval=ModelsUser::select('users.tax')->where('users.id','1')
        ->get()->first();

        foreach ($cartdata as $value) {


            $data[] = array(
                "id" => $value['id'],
                "qty" => $value['qly'],
                "total_price" => $value['total_price'],
                "item_name" => $value['item_name'],
                "item_price" => $value['item_price'],
                "item_id" => $value['item_id'],

                "topping" => $topping,
                "itemimage" => $value["itemimage"]
            );
        }
        @$order_total = array_sum(array_column(@$data, 'total_price'));
        $summery = array(
            'order_total' => "$order_total",
            'tax' => "$taxval->tax",
            'discount_amount' => $status->discount_amount,
            'promocode' => $status->promotecode,
            'order_notes' => $status->order_notes,
            'delivery_charge' => "$status->delivery_charge",
        );

        if(!empty($cartdata))
        {
            return response()->json(['status'=>1,'message'=>'Summery list Successful','delivery_address'=>$status->address,'order_number'=>$status->order_number,'name'=>$status->name,'profile_image'=>$status->profile_image,'mobile'=>$status->mobile,'data'=>@$data,'summery'=>$summery],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
    }

    public function delivered(Request $request)
    {
        if($request->order_id == ""){
            return response()->json(["status"=>0,"message"=>"Order Number is required"],400);
        }

        $UpdateDetails = ModelsOrder::where('id', $request->order_id)
                    ->update(['status' => '4']);

        if ($UpdateDetails) {

            //Notification

            $getuser = ModelsOrder::where('id', $request->order_id)->first();

            $google_api_key = env('FIREBASE');

            $title = "Order";

            if ($getuser->delivery_id) {

                $gettoken=ModelsUser::select('token','name','email')->where('id',$getuser->delivery_id)
                ->get()->first();

                $body = 'Order '.$getuser->order_number.' is Delivered';

                try{
                    $ordermessage='Order "'.$getuser->order_number.'" is Delivered';
                    $email=$gettoken->email;
                    $name=$gettoken->name;
                    $data=['ordermessage'=>$ordermessage,'email'=>$email,'name'=>$name];

                    Mail::send('Email.orderemail',$data,function($message)use($data){
                        $message->from(env('MAIL_USERNAME'))->subject($data['ordermessage']);
                        $message->to($data['email']);
                    } );
                }catch(\Swift_TransportException $e){
                    $response = $e->getMessage() ;
                    return response()->json(['status'=>0,'message'=>'Something went wrong while sending email Please try again...'],200);
                }

                $registrationIds = $gettoken->token;
                #prep the bundle
                $msg = array
                    (
                    'body'  => $body,
                    'title' => $title,
                    'sound' => 1/*Default sound*/
                    );

                $fields = array
                    (
                    'to'            => $registrationIds,
                    'notification'  => $msg
                    );

                $headers = array
                    (
                    'Authorization: key=' . $google_api_key,
                    'Content-Type: application/json'
                    );
                #Send Reponse To FireBase Server
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                $result = curl_exec ( $ch );
                curl_close ( $ch );
            }

            if ($getuser->user_id) {
                $gettoken=ModelsUser::select('token','name','email')->where('id',$getuser->user_id)
                ->get()->first();

                $body = 'Your Order '.$getuser->order_number.' is Delivered';

                try{
                    $ordermessage='Order "'.$getuser->order_number.'" is Delivered';
                    $email=$gettoken->email;
                    $name=$gettoken->name;
                    $data=['ordermessage'=>$ordermessage,'email'=>$email,'name'=>$name];

                    Mail::send('Email.orderemail',$data,function($message)use($data){
                        $message->from(env('MAIL_USERNAME'))->subject($data['ordermessage']);
                        $message->to($data['email']);
                    } );
                }catch(\Swift_TransportException $e){
                    $response = $e->getMessage() ;
                    return response()->json(['status'=>0,'message'=>'Something went wrong while sending email Please try again...'],200);
                }

                $registrationIds = $gettoken->token;
                #prep the bundle
                $msg = array
                    (
                    'body'  => $body,
                    'title' => $title,
                    'sound' => 1/*Default sound*/
                    );

                $fields = array
                    (
                    'to'            => $registrationIds,
                    'notification'  => $msg
                    );

                $headers = array
                    (
                    'Authorization: key=' . $google_api_key,
                    'Content-Type: application/json'
                    );
                #Send Reponse To FireBase Server
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

                $result = curl_exec ( $ch );
                curl_close ( $ch );
            }

            return response()->json(['status'=>1,'message'=>'Order has been delivered'],200);
        } else {
            return response()->json(['status'=>0,'message'=>'Something went wrong'],200);
        }
    }
}
