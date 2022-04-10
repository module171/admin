<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\shipper;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShipperController extends Controller
{
    public function index()
    {
        $title='shipper';
        $getdriver = shipper::get();
        return view('page.driver',compact('getdriver','title'));
    }

    public function list()
    {

        $getdriver = shipper::get();
        return view('datatable.drivertable',compact('getdriver'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $s
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
          'name' => 'required',
          'email' => 'required|unique:users',
          'mobile' => 'required|unique:users',
          'password' => 'required',
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
            $driver = new shipper();
            $driver->name = htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8');
            $driver->email = htmlspecialchars($request->email, ENT_QUOTES, 'UTF-8');
            $driver->mobile = htmlspecialchars($request->mobile, ENT_QUOTES, 'UTF-8');
            $driver->profile_image = "unknown.jpg";

            $driver->password = Hash::make($request->password);
            $driver->save();
            $success_output = 'Driver Added Successfully!';
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $driver = User::findorFail($request->id);
        $getdriver = shipper::select('id','name','email','mobile')->where('id',$request->id)->first();

        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'driver fetch successfully', 'ResponseData' => $getdriver], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $req)
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

        $validation = Validator::make($request->all(),[
          'name' => 'required',
          'email' => 'required|unique:users,name,' . $request->id,
          'mobile' => 'required|unique:users,mobile,' . $request->id
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
            // dd($error_array);
        }
        else
        {
            $driver = new shipper();
            $driver->exists = true;
            $driver->id = htmlspecialchars($request->id, ENT_QUOTES, 'UTF-8');
            $driver->name =htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8');
            $driver->email =htmlspecialchars($request->email, ENT_QUOTES, 'UTF-8');
            $driver->mobile =htmlspecialchars($request->mobile, ENT_QUOTES, 'UTF-8');
            $driver->save();

            $success_output = 'Driver updated Successfully!';
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }

    public function status(Request $request)
    {
        $users = shipper::where('id', $request->id)->update( array('status'=>$request->status) );
        if ($users) {
            return 1;
        } else {
            return 0;
        }
    }
}
