<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\User;
use Validator;

class UserController extends Controller
{
    public function register(Request $request )
    {
        if($request->email == ""){
            return response()->json(["status"=>0,"message"=>"Email ID is required"],400);
        }
        if($request->password == ""){
            return response()->json(["status"=>0,"message"=>"Password is required"],400);
        }
        if($request->name == ""){
            return response()->json(["status"=>0,"message"=>"Name is required"],400);
        }
        if($request->mobile == ""){
            return response()->json(["status"=>0,"message"=>"Mobile is required"],400);
        }

        $checkemail=ModelsUser::where('email',$request['email'])->first();

        if(!empty($checkemail))
        {
            return response()->json(['status'=>0,'message'=>'Email ID already exist.'],400);
        }

        $checkmobile=ModelsUser::where('mobile',$request['mobile'])->first();

        if(!empty($checkmobile))
        {
            return response()->json(['status'=>0,'message'=>'Mobile number already exist.'],400);
        }

        $otp = rand ( 100000 , 999999 );

        try{
            $title='Email Verification Code';
            $email=$request->email;
            $data=['title'=>$title,'email'=>$email,'otp'=>$otp];

            Mail::send('Email.emailverification',$data,function($message)use($data){
                $message->from(env('MAIL_USERNAME'))->subject($data['title']);
                $message->to($data['email']);
            } );

            $data['name']=$request->get('name');
            $data['mobile']=$request->get('mobile');
            $data['email']=$request->get('email');
            $data['profile_image']='unknown.png';
            $data['password']=Hash::make($request->get('password'));
            $data['otp']=$otp;
            $data['type']='2';
            $user=ModelsUser::create($data);
            if($user)
            {
                $arrayName = array(
                    'id' => $user->id
                );

                return response()->json(['status'=>1,'message'=>'Registration Successful','data'=>$arrayName],200);
            }
            else
            {
                return response()->json(['status'=>0,'message'=>'Something went wrong'],400);
            }
        }catch(\Swift_TransportException $e){
            $response = $e->getMessage() ;
            $email=$request->email;
            return response()->json(['status'=>0,'message'=>$response],200);
        }
    }

    public function emailverify(Request $request )
    {
        if($request->email == ""){
            return response()->json(["status"=>0,"message"=>"Email is required"],400);
        }
        if($request->otp == ""){
            return response()->json(["status"=>0,"message"=>"OTP is required"],400);
        }
        // if($request->token == ""){
        //     return response()->json(["status"=>0,"message"=>"Token is required"],400);
        // }

        $checkuser=ModelsUser::where('email',$request->email)->first();

        if (!empty($checkuser)) {
        	if ($checkuser->otp == $request->otp) {
	        	$update=ModelsUser::where('email',$request['email'])->update(['otp'=>NULL,'is_verified'=>'1','token'=>$request->token]);

                $arrayName = array(
                    'id' => $checkuser->id,
                    'name' => $checkuser->name,
                    'mobile' => $checkuser->mobile,
                    'email' => $checkuser->email,
                );

                return response()->json(['status'=>1,'message'=>"Email has been verified",'data'=>$arrayName],200);

	        } else {
	        	return response()->json(["status"=>0,"message"=>"Invalid OTP"],400);
	        }
        } else {
        	return response()->json(["status"=>0,"message"=>"Invalid Email Address"],400);
        }
    }

    public function resendemailverification(Request $request )
    {
        if($request->email == ""){
            return response()->json(["status"=>0,"message"=>"Email is required"],400);
        }

        $checkuser=ModelsUser::where('email',$request->email)->first();

        if (!empty($checkuser)) {

            try{
                $otp = rand ( 100000 , 999999 );

                $update=ModelsUser::where('email',$request['email'])->update(['otp'=>$otp,'is_verified'=>'2']);

                $title='Email Verification Code';
                $email=$request->email;
                $data=['title'=>$title,'email'=>$email,'otp'=>$otp];

                Mail::send('Email.emailverification',$data,function($message)use($data){
                    $message->from(env('MAIL_USERNAME'))->subject($data['title']);
                    $message->to($data['email']);
                } );
            }catch(\Swift_TransportException $e){
                $response = $e->getMessage() ;
                return response()->json(['status'=>0,'message'=>'Something went wrong while sending email Please try again...'],200);
            }

        	return response()->json(["status"=>1,"message"=>"Email has been sent to your registered email address"],200);

        } else {
        	return response()->json(["status"=>0,"message"=>"Invalid Email Address"],400);
        }
    }

    public function login(Request $request )
    {
        if($request->email == ""){
            return response()->json(["status"=>0,"message"=>"Email id is required"],400);
        }
        if($request->password == ""){
            return response()->json(["status"=>0,"message"=>"Password is required"],400);
        }

        $login=ModelsUser::where('email',$request['email'])->where('type','=','2')->first();

        if(!empty($login))
        {
        	if($login->is_verified == '1')
            {
	            if($login->is_available == '1')
	            {
	                if(Hash::check($request->get('password'),$login->password))
	                {
	                    $arrayName = array(
	                        'id' => $login->id,
	                        'name' => $login->name,
	                        'mobile' => $login->mobile,
	                        'email' => $login->email,
	                    );

	                    $data=array('user'=>$arrayName);
	                    $status=1;
	                    $message='Login Successful';

	                    $data_token['token'] = $request['token'];
	                    $update=ModelsUser::where('email',$request['email'])->update($data_token);

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
	        } else {
                $status=2;
                $message="Your haven't verified your email address";
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

        $users =ModelsUser::where('id',$request['user_id'])->get()->first();

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

        $user = new ModelsUser();
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
        $check_user=ModelsUser::where('id',$request['user_id'])->get()->first();
        if(Hash::check($request['old_password'],$check_user->password))
        {
            $data['password']=Hash::make($request['new_password']);
            $update=ModelsUser::where('id',$request['user_id'])->update($data);
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

        $checklogin=ModelsUser::where('email',$request['email'])->first();

        if(empty($checklogin))
        {
            return response()->json(['status'=>0,'message'=>'Email does not exist'],400);
        }
        else {
            try{
                $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 8 );
                $newpassword['password'] = Hash::make($password);
                $update = ModelsUser::where('email', $request['email'])->update($newpassword);

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
}
