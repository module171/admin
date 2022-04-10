<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart;
use App\Order;

use App\OrderDetails;
use App\Promocode;
use App\ItemImages;
use App\Addons;
use App\Models\item;
use App\Models\order as ModelsOrder;
use App\Models\orderdetail;
use App\Models\promocode as ModelsPromocode;
use App\Models\shipper;
use App\Models\User as ModelsUser;
use App\Pincode;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class CheckoutController extends Controller
{
    public function summary(Request $request)
    {
        if($request->user_id == ""){
            return response()->json(["status"=>0,"message"=>"User ID is required"],400);
        }



        $taxval=ModelsUser::select('users.tax','users.delivery_charge')->where('users.id','1')
        ->get()->first();
        $cartdata=$request->cart;

        foreach ($cartdata as $value) {


            $arr1 = explode(',', $value['topping_id']);
            $item=item::with('itemimage')

            ->select('item.id','item.item_name','item.item_price','item.item_description')
            ->where('id',$value['item_id'])
            ->first();
            $topping = item::whereIn('id',$arr1)
            ->where('type_food',2)
            ->select('item_name','item_price','id','item_description')
            ->get();

            // $images = ItemImages::where('id',$value['item_id'])->get();

            $data[] = array(
                "id" => $value['item_id'],
                "qty" => $value['qty'],
                "total_price" => $value['total_price'],
                "item_name" =>$item->item_name,
                "item_price" =>$item->item_price,
                // "item_id" =>$item->item_id,
                "item_notes" => $value['item_notes'],
                "topping" =>$topping,
                "itemimage" =>$item->itemimage,
            );
        }

        @$order_total = array_sum(array_column(@$data, 'total_price'));
        $summery = array(
            'order_total' => "$order_total",
            'tax' => "$taxval->tax",
            'delivery_charge' => "$taxval->delivery_charge",
        );

        if(!empty($cartdata))
        {
            return response()->json(['status'=>1,'message'=>'Summery list Successful','data'=>@$data  ],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
    }

    public function order(Request $request)
    {
        if($request->user_id == ""){
            return response()->json(["status"=>0,"message"=>"User ID is required"],400);
        }
        if($request->order_total == ""){
            return response()->json(["status"=>0,"message"=>"Total Amount is required"],400);
        }

        if($request->payment_type == ""){
            return response()->json(["status"=>0,"message"=>"Payment Type is required"],400);
        }

        $order_number = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 10)), 0, 10);

        try {

            if($request->payment_type == "1") {

                if ($request->order_type == "2") {
                    $delivery_charge = "0.00";
                    $address = "";

                    $order_total = $request->order_total-$request->$delivery_charge;
                } else {

                    if($request->address == ""){
                        return response()->json(["status"=>0,"message"=>"Address is required"],400);
                    }




                    $delivery_charge = $request->delivery_charge;
                    $address = $request->address;

                    $order_total = $request->order_total;

                }

                $order = new ModelsOrder;
                $order->order_number =$order_number;
                $order->user_id =$request->user_id;
                $order->order_total =$order_total;
                $order->payment_id =$request->payment_id;
                $order->payment_type =$request->payment_type;
                $order->order_type =$request->order_type;
                $order->status ='1';
                $order->address =$address;


                $order->promotecode =$request->promocode;
                $order->discount_amount =$request->discount_amount;
                $order->discount_pr =$request->discount_pr;
                $order->tax =$request->tax;
                $order->tax_amount =$request->tax_amount;
                $order->delivery_charge =$delivery_charge;
                $order->order_notes =$request->order_notes;

                $order->save();

                $order_id = DB::getPdo()->lastInsertId();

                $cartdata=$request->cart;
                foreach ($cartdata as $value) {
                    $OrderPro = new orderdetail();
                    $OrderPro->order_id = $order_id;

                    $OrderPro->item_id = $value['item_id'];

                    $OrderPro->qly = $value['qly'];

                    $OrderPro->save();
                }



                //Notification
                $getalluses=ModelsUser::select('token','email','name')->where('id',$request->user_id)
                ->get()->first();

                try{
                    $email=$getalluses->email;
                    $name=$getalluses->name;
                    $ordermessage='Order "'.$order_number.'" has been placed';
                    $data=['ordermessage'=>$ordermessage,'email'=>$email,'name'=>$name];

                    Mail::send('Email.orderemail',$data,function($message)use($data){
                        $message->from(env('MAIL_USERNAME'))->subject($data['ordermessage']);
                        $message->to($data['email']);
                    } );

                    $title = "Order";
                    $body = 'Order "'.$order_number.'" has been placed';
                    $google_api_key = env('FIREBASE');

                    $registrationIds = $getalluses->token;
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
                }catch(\Swift_TransportException $e){
                    $response = $e->getMessage() ;
                    // return Redirect::back()->with('danger', $response);
                    return response()->json(['status'=>0,'message'=>'Something went wrong while sending email Please try again...'],200);
                }

                return response()->json(['status'=>1,'message'=>'Order has been placed'],200);

            } else {
                if ($request->order_type == "2") {
                    $delivery_charge = "0.00";
                    $address = "";

                    $order_total = $request->order_total-$request->$delivery_charge;
                } else {

                    if($request->address == ""){
                        return response()->json(["status"=>0,"message"=>"Address is required"],400);
                    }



                    $delivery_charge = $request->delivery_charge;
                    $address = $request->address;

                    $order_total = $request->order_total;

                }

                $order = new ModelsOrder();
                $order->order_number =$order_number;
                $order->user_id =$request->user_id;
                $order->order_total =$order_total;
                $order->payment_type =$request->payment_type;
                $order->order_type =$request->order_type;
                $order->status ='1';
                $order->address =$address;

                $order->promotecode =$request->promocode;
                $order->discount_amount =$request->discount_amount;
                $order->discount_pr =$request->discount_pr;
                $order->tax =$request->tax;
                $order->tax_amount =$request->tax_amount;
                $order->delivery_charge =$delivery_charge;
                $order->order_notes =$request->order_notes;

                $order->save();


                $order_id = DB::getPdo()->lastInsertId();
                $cartdata=$request->cart;
                foreach ($cartdata as $value) {
                    $OrderPro = new orderdetail();
                    $OrderPro->order_id = $order_id;

                    $OrderPro->item_id = $value['item_id'];

                    $OrderPro->qly = $value['qly'];

                    $OrderPro->save();

                }


                //Notification
                $getalluses=ModelsUser::select('token','email','name')->where('id',$request->user_id)
                ->get()->first();

                try{
                    $email=$getalluses->email;
                    $name=$getalluses->name;
                    $ordermessage='Order "'.$order_number.'" has been placed';
                    $data=['ordermessage'=>$ordermessage,'email'=>$email,'name'=>$name];

                    Mail::send('Email.orderemail',$data,function($message)use($data){
                        $message->from(env('MAIL_USERNAME'))->subject($data['ordermessage']);
                        $message->to($data['email']);
                    } );

                    $title = "Order";
                    $body = 'Order "'.$order_number.'" has been placed';
                    $google_api_key = env('FIREBASE');

                    $registrationIds = $getalluses->token;
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
                    // dd($result);
                    curl_close ( $ch );
                }catch(\Swift_TransportException $e){
                    $response = $e->getMessage() ;
                    // return Redirect::back()->with('danger', $response);
                    return response()->json(['status'=>0,'message'=>'Something went wrong while sending email Please try again...'],200);
                }

                return response()->json(['status'=>1,'message'=>'Order has been placed'],200);
            }

        } catch (\Exception $e){

            return response()->json(['status'=>0,'message'=>$e],400);
        }
    }

    public function orderhistory(Request $request)
    {
        if($request->user_id == ""){
            return response()->json(["status"=>0,"message"=>"User ID is required"],400);
        }
        \DB::statement("SET SQL_MODE=''");
        $cartdata=orderdetail::select('order.order_total as total_price',DB::raw('SUM(order_detail.qly) AS qly'),'order.id','order.order_type','order.order_number','order.status','order.payment_type',DB::raw('DATE_FORMAT(order.created_at, "%d-%m-%Y") as date'))
        ->join('item','order_detail.item_id','=','item.id')
        ->join('order','order_detail.order_id','=','order.id')
        ->where('order.user_id',$request->user_id)->groupBy('order_detail.order_id')->orderBy('order_detail.order_id', 'DESC')->get();

        if(!empty($cartdata))
        {
            return response()->json(['status'=>1,'message'=>'Order history list Successful','data'=>$cartdata],200);
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

        $cartdata=orderdetail::with('itemimage')->select('order_detail.qly','item.item_price as total_price','item.id','item.item_name','item.item_price','order_detail.item_id')
        ->join('item','order_detail.item_id','=','item.id')
        ->join('order','order_detail.order_id','=','order.id')
        ->where('order_detail.order_id',$request->order_id)->get()->toArray();

        $status=ModelsOrder::select('order.delivery_id','order.address','order.promotecode','order.discount_amount','order.order_number','order.status','order.order_notes','order.order_type','order.tax','order.delivery_charge')
        ->join('users','order.user_id','=','users.id')
        ->where('order.id',$request['order_id'])
        ->get()->first();

        $getdriver=shipper::select('name',\DB::raw("CONCAT('".url('/public/images/profile/')."/',profile_image) AS profile_image"),'mobile')->where('id',$status->delivery_id)
        ->get()->first();

        foreach ($cartdata as $value) {
            $data[] = array(
                "total_price" => $value['total_price']
            );
        }

        foreach ($cartdata as $value) {

            // $arr = explode(',', $value['addons_id']);
            // $addons = Addons::whereIn('id',$arr)->get();

            // $images = ItemImages::where('id',$value['item_id'])->get();

            $cdata[] = array(
                "id" => $value['id'],
                "qty" => $value['qly'],
                "total_price" => $value['total_price'],
                "item_name" => $value['item_name'],
                "item_price" => $value['item_price'],
                "item_id" => $value['item_id'],


                "itemimage" => $value["itemimage"]
            );
        }

        @$order_total = array_sum(array_column(@$data, 'total_price'));
        $summery = array(
            'order_total' => "$order_total",
            'tax' => $status->tax,
            'discount_amount' => $status->discount_amount,
            'promocode' => $status->promocode,
            'order_notes' => $status->order_notes,
            'delivery_charge' => $status->delivery_charge,
            "driver_name" => @$getdriver["name"],
            "driver_profile_image" => @$getdriver["profile_image"],
            "driver_mobile" => @$getdriver["mobile"],
        );

        if(!empty($cartdata))
        {
            return response()->json(['status'=>1,'message'=>'Summery list Successful','address'=>$status->address,'order_number'=>$status->order_number,'order_type'=>$status->order_type,'data'=>@$cdata,'summery'=>$summery],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
    }

    public function ordercancel(Request $request)
    {
        if($request->order_id == ""){
            return response()->json(["status"=>0,"message"=>"Order Number is required"],400);
        }

        $UpdateDetails = ModelsOrder::where('id', $request->order_id)
                    ->update(['status' => '4']);

        if(!empty($UpdateDetails))
        {
            return response()->json(['status'=>1,'message'=>'Order has been cancelled'],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'Something went wrong'],400);
        }
    }

    public function promocodelist()
    {

        $promocode=ModelsPromocode::select('promocode.offer_name','promocode.offer_code','promocode.offer_amount','promocode.description')
        // ->where('is_available','=','1')
        ->get();

        if(!empty($promocode))
        {
            return response()->json(['status'=>1,'message'=>'Promocode List','data'=>$promocode],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No Promocode found'],200);
        }
    }

    public function promocode(Request $request)
    {
        if($request->offer_code == ""){
            return response()->json(["status"=>0,"message"=>"Promocode is required"],400);
        }

        if($request->user_id == ""){
            return response()->json(["status"=>0,"message"=>"user_id is required"],400);
        }

        $checkpromo=ModelsOrder::select('promotecode')->where('promotecode',$request->offer_code)->where('user_id',$request->user_id)
        ->count();

        if ($checkpromo > "0" ) {
            return response()->json(['status'=>0,'message'=>'The Offer Is Applicable Only Once Per User'],200);
        } else {
            $promocode=ModelsPromocode::select('promocode.offer_amount','promocode.description','promocode.offer_code')->where('promocode.offer_code',$request['offer_code'])
            ->get()->first();

            if($promocode['offer_code']== $request->offer_code) {
                if(!empty($promocode))
                {
                    return response()->json(['status'=>1,'message'=>'Promocode has been applied','data'=>$promocode],200);
                }
            } else {
                return response()->json(['status'=>0,'message'=>'You applied wrong Promocode'],200);
            }
        }
    }


}
