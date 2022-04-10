<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\promocode;
use Illuminate\Http\Request;
use Validator;
class PromocodeController extends Controller
{
    //
    public function index()
    {



        $title="promocode";
        $getpromocode = promocode::get();
        return view('page.promocode',compact('getpromocode','title'));
    }

    public function list()
    {
        $getpromocode = Promocode::get();
        return view('datatable.promocodetable',compact('getpromocode'));
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
          'offer_name' => 'required|unique:promocode',
          'offer_code' => 'required|unique:promocode',
          'offer_amount' => 'required',
          'description' => 'required',
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
            $promocode = new Promocode;
            $promocode->offer_name =htmlspecialchars($request->offer_name, ENT_QUOTES, 'UTF-8');
            $promocode->offer_code =htmlspecialchars($request->offer_code, ENT_QUOTES, 'UTF-8');
            $promocode->offer_amount =htmlspecialchars($request->offer_amount, ENT_QUOTES, 'UTF-8');
            $promocode->description =htmlspecialchars($request->description, ENT_QUOTES, 'UTF-8');
            $promocode->save();
            $success_output = 'Promocode Added Successfully!';
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
        $getpromocode = Promocode::where('id',$request->id)->first();
        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Promocode fetch successfully', 'ResponseData' => $getpromocode], 200);
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
          'getoffer_name' => 'required|unique:promocode,offer_name,' . $request->id,
          'getoffer_code' => 'required|unique:promocode,offer_name,' . $request->id,
          'getoffer_amount' => 'required',
          'get_description' => 'required',
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
            $promocode = new Promocode;
            $promocode->exists = true;
            $promocode->id = $request->id;
            $promocode->offer_name =htmlspecialchars($request->getoffer_name, ENT_QUOTES, 'UTF-8');
            $promocode->offer_code =htmlspecialchars($request->getoffer_code, ENT_QUOTES, 'UTF-8');
            $promocode->offer_amount =htmlspecialchars($request->getoffer_amount, ENT_QUOTES, 'UTF-8');
            $promocode->description =htmlspecialchars($request->get_description, ENT_QUOTES, 'UTF-8');
            $promocode->save();

            $success_output = 'Promocode updated Successfully!';
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }

    public function status(Request $request)
    {
        $promocode = Promocode::where('id', $request->id)->update( array('status'=>$request->status) );
        if ($promocode) {
            return 1;
        } else {
            return 0;
        }
    }
}
