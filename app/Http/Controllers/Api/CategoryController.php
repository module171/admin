<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Helpers\BaseFunction;

use App\Models\categories;
use Validator;

class CategoryController extends Controller
{
    public function category(Request $request)
    {

    	$categorydata=categories::select('id','category_name',\DB::raw("CONCAT('".url('/public/images/category/')."/', image) AS image"))
        ->where('is_available','=','1')
        ->get();
        if(!empty($categorydata))
        {
        	return response()->json(['status'=>1,'message'=>'Category Successful','data'=>$categorydata],200);
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
    }
}
