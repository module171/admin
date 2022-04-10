<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\bill;
use App\Models\item;
use App\Models\order;
use App\Models\shipper;
use App\Models\User;
use Illuminate\Http\Request;
use League\CommonMark\Node\Query\OrExpr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class OrderController extends Controller
{
     //    $order=order::find(1);
    //   $item=item::find(6);
    //   $order->item()->attach(6,['qly'=>4]);
    //    dd($order->item->toArray());
    //    dd($item->order->toArray());
    public function index(){
       $title="order";
          $order=order::find(1);

        //   $order->item()->attach(12,['qly'=>4]);
        //   $order->item()->attach(13,['qly'=>4]);
       $getorders = Order::with('user')->select('order.*','users.name')->leftJoin('users', 'order.delivery_id', '=', 'users.id')->where('order.created_at','LIKE','%' .date("Y-m-d") . '%')->get();
       $getdriver = shipper::get();
       return view('page.order',compact('title','getorders','getdriver','order'));
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoice(Request $request)
    {
        $getusers = Order::with('user')->where('order.id', $request->id)->get()->first();
        // $getorders=OrderDetails::with('itemimage')->select('order_details.id','order_details.qty','order_details.price as total_price','item.id','item.item_name','item.item_price','order_details.item_id','order_details.addons_id','order_details.item_notes')
        // ->join('item','order_details.item_id','=','item.id')
        // ->join('order','order_details.order_id','=','order.id')
        // ->where('order_details.order_id',$request->id)->get();
         $itemorder=Order::find($request->id)->item;



        $topping=[];
        // if ($getorders['order']->isNotEmpty()) {
            foreach ($itemorder as $value) {
               $arr = explode(',', $value['id']);
               $topping=item::whereIn('id',$arr)->where('type_food',2)->get();
            };

            $getorders=['order'=>Order::find($request->id),
            'itemorder'=>Order::find($request->id)->item->where('type_food',1),
            'topping'=>$topping

];
        // } else {
        //     return abort(404);
        // }
        return view('page.invoice',compact('getusers','getorders'));
    }
public function createbill(Request $request){

   $order=order::find($request->id);

   $bill=new bill(['order_id'=>$request->id]);
   $bill->save();

   return 1;
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $UpdateDetails = Order::where('id', $request->id)
                    ->update(['status' => $request->status]);

        //Notification
        $userdetails = Order::where('id', $request->id)->first();


        $getalluses=User::select('token','email','name')->where('id',$userdetails->user_id)
        ->get()->first();

        $title = "Order";

        if ($request->status == "2") {
            $body = 'Your Order '.$userdetails->order_number.' is Ready';
            $ordermessage='Your Order "'.$userdetails->order_number.'" is Ready';
        } else {
            $body = 'Your Order '.$userdetails->order_number.' is Delivered';
            $ordermessage='Your Order "'.$userdetails->order_number.'" is Delivered';
        }

        try{
            $email=$getalluses->email;
            $name=$getalluses->name;
            $data=['ordermessage'=>$ordermessage,'email'=>$email,'name'=>$name];

            Mail::send('Email.orderemail',$data,function($message)use($data){
                $message->from(env('MAIL_USERNAME'))->subject($data['ordermessage']);
                $message->to($data['email']);
            } );
        }catch(\Swift_TransportException $e){
            $response = $e->getMessage() ;
            // return Redirect::back()->with('danger', $response);
            return 0;
        }

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

        if ($UpdateDetails) {
            return 2;
        } else {
            return 0;
        }
    }

    public function assign(Request $request)
    {
        $UpdateDetails = Order::where('id', $request->bookId)
                    ->update(['delivery_id' => $request->driver_id,'status' => '3']);

        $userdetails = Order::where('id', $request->bookId)->first();

        $google_api_key = env('FIREBASE');

        $title = "Order";

        if ($userdetails->delivery_id) {

            $gettoken=shipper::select('token','name','email')->where('id',$userdetails->delivery_id)
            ->get()->first();

            $body = 'New Order '.$userdetails->order_number.' assigned to you';


            try{
                $ordermessage='New Order "'.$userdetails->order_number.'" assigned to you';
                $email=$gettoken->email;
                $name=$gettoken->name;
                $data=['ordermessage'=>$ordermessage,'email'=>$email,'name'=>$name];

                Mail::send('Email.orderemail',$data,function($message)use($data){
                    $message->from(env('MAIL_USERNAME'))->subject($data['ordermessage']);
                    $message->to($data['email']);
                } );
            }catch(\Swift_TransportException $e){
                $response = $e->getMessage() ;
                // return Redirect::back()->with('danger', $response);
                return 0;
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

        if ($userdetails->user_id) {
            $gettoken=User::select('token','name','email')->where('id',$userdetails->user_id)
            ->get()->first();

            $body = 'Your Order '.$userdetails->order_number.' is on the way';

            try{
                $ordermessage='Your Order "'.$userdetails->order_number.'" is on the way';
                $email=$gettoken->email;
                $name=$gettoken->name;
                $data=['ordermessage'=>$ordermessage,'email'=>$email,'name'=>$name];

                Mail::send('Email.orderemail',$data,function($message)use($data){
                    $message->from(env('MAIL_USERNAME'))->subject($data['ordermessage']);
                    $message->to($data['email']);
                } );
            }catch(\Swift_TransportException $e){
                $response = $e->getMessage() ;
                // return Redirect::back()->with('danger', $response);
                return 0;
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

        if ($UpdateDetails) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $order=Order::where('id', $request->id)->delete();
        $delete=OrderDetails::where('order_id', $request->id)->delete();
        if ($order) {
            return 1;
        } else {
            return 0;
        }
    }
}
