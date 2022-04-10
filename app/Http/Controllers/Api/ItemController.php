<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\item;
use App\Models\ItemImages;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\DB;
use Validator;

class ItemController extends Controller
{
    public function item(Request $request)
    {



        if($request->cat_id == ""){
            return response()->json(["status"=>0,"message"=>"category is required"],400);
        }



        if($request->user_id == ""){
            $user_id  = $request->user_id;
            $itemdata=item::with('itemimage')->select('item.id','item.item_name','item.item_price')

            ->join('categories','item.cat_id','=','categories.id')
            ->where('item.item_status','1')
            ->where('item.cat_id',$request['cat_id'])
            ->where('item.type_food',1)
            ->orderBy('item.id', 'DESC')->paginate(10);

            if(!empty($itemdata))
            {
                return response()->json(['status'=>1,'message'=>'Item Successful','data'=>$itemdata,'currency'=>env('CURRENCY')],200);
            }
            else
            {
                return response()->json(['status'=>0,'message'=>'No data found'],200);
            }
            return response()->json(["status"=>0,"message"=>"category is required"],400);
        } else {
            $checkuser=ModelsUser::where('id',$request->user_id)->first();

            if($checkuser->is_available == '1')
            {
                $user_id  = $request->user_id;
                $itemdata=Item::with('itemimage')->select('item.id','item.item_name','item.item_price')

                ->join('categories','item.cat_id','=','categories.id')
                ->where('item.item_status','1')
                ->where('item.type_food',1)
                ->where('item.cat_id',$request['cat_id'])->orderBy('item.id', 'DESC')->paginate(10);

                if(!empty($itemdata))
                {
                    return response()->json(['status'=>1,'message'=>'Item Successful','data'=>$itemdata,'currency'=>env('CURRENCY')],200);
                }
                else
                {
                    return response()->json(['status'=>0,'message'=>'No data found'],200);
                }
                return response()->json(["status"=>0,"message"=>"category is required"],400);
            } else {
                $status=2;
                $message='Your account has been blocked by Admin';
                return response()->json(['status'=>$status,'message'=>$message],422);
            }
        }
    }

    public function itemdetails(Request $request)
    {
        if($request->item_id == ""){
            return response()->json(["status"=>0,"message"=>"Item ID is required"],400);
        }

    	$itemdata=Item::with('itemimagedetails')->select('item.id','item.item_name','item.item_description','item.item_price','item.item_status','categories.category_name','item.cat_id')
    	->join('categories','item.cat_id','=','categories.id')
    	->where('item.id',$request['item_id'])->get()->first();
        $topping=Item::with('itemimagedetails')->select('item.id','item.item_name','item.item_description','item.item_price','item.item_status')
    	->where('item.type_food',2)
        ->where('item.cat_id',$itemdata->cat_id)->get();

        $data = array(
            'id' => $itemdata->id,
            'item_name' => $itemdata->item_name,
            'item_description' => $itemdata->item_description,
            'item_price' => $itemdata->item_price,
            'topping'=>$topping,
            'item_status' => $itemdata->item_status,
            'category_name' => $itemdata->category_name,
            // 'average_rating' => number_format($review->average_rating,1),
            'images' => $itemdata->itemimagedetails,


        );

        if(!empty($data))
        {
        	return response()->json(['status'=>1,'message'=>'Item Successful','data'=>$data],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
    }

    public function searchitem(Request $request)
    {

        if($request->keyword == ""){
            return response()->json(["status"=>0,"message"=>"Keyword is required"],400);
        }

        $user_id  = $request->user_id;
        $itemdata=Item::with('itemimage')->select('item.id','item.item_name','item.item_price')

        ->join('categories','item.cat_id','=','categories.id')
        ->where('item.item_status','1')
        ->where('item.item_name', 'LIKE', '%' . $request['keyword'] . '%')->orderBy('item.id', 'DESC')->paginate(10);

        if(!$itemdata->isEmpty())
        {
            return response()->json(['status'=>1,'message'=>'Item Successful','data'=>$itemdata],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
    }


}
