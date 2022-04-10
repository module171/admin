<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\categories;
use App\Models\item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
class CategoriesController extends Controller
{

    protected $data=[];
    public function index()
    {
        $title="category";
        $getcategory = categories::where('is_available','1')->get();
        return view('page.categories',compact('getcategory','title'));
    }

    public function list()
    {
        $getcategory = categories::where('is_available','1')->get();
        return view('datatable.categorytable',compact('getcategory'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $s
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'category_name' => ['required',Rule::unique('categories')->where(function ($query) use ($request) {
                return $query->where('is_available', '1');
            })],
            'image' => 'required|image|mimes:jpeg,png,jpg',
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
            $image = 'category-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move('public/images/category', $image);

            $category = new categories();
            $category->image =$image;
            $category->category_name =htmlspecialchars($request->category_name, ENT_QUOTES, 'UTF-8');
            $category->save();
            $success_output = 'Category Added Successfully!';
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
        $category = categories::findorFail($request->id);
        $getcategory = categories::where('id',$request->id)->first();
        if($getcategory->image){
            $getcategory->img=url('public/images/category/'.$getcategory->image);
        }
        return response()->json(['ResponseCode' => 1, 'ResponseText' => 'Category fetch successfully', 'ResponseData' => $getcategory], 200);
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
          'category_name' => 'required|unique:categories,category_name,' . $request->id,
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
            $category = new categories();
            $category->exists = true;
            $category->id = $request->id;

            if(isset($request->image)){
                if($request->hasFile('image')){
                    $image = $request->file('image');
                    $image = 'category-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
                    $request->image->move('public/images/category', $image);
                    $category->image=$image;

                    unlink(public_path('images/category/'.$request->old_img));
                }
            }
            $category->category_name =htmlspecialchars($request->category_name, ENT_QUOTES, 'UTF-8');
            $category->save();

            $success_output = 'Category updated Successfully!';
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $category = categories::where('id', $request->id)->update( array('is_available'=>$request->status) );
        if ($category) {
            $item = item::where('cat_id', $request->id)->update( array('item_status'=>$request->status) );
            // $getitem = Item::where('cat_id', $request->id)->first();
            // $UpdateCart = Cart::where('item_id', @$getitem->id)
            //             ->update(['is_available' => $request->status]);
            return 1;
        } else {
            return 0;
        }
    }
    public function delete(Request $request)
    {
            $category= categories::find($request->id)->delete();
                   $status=0;
            if($category==1){
                $status=1;

            }else{

                $status=0;

            }



            return json_encode($status);

    }
}
