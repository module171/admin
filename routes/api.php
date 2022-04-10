<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController as Category;
use App\Http\Controllers\Api\UserController as User;
use App\Http\Controllers\Api\ItemController as Item;
use App\Http\Controllers\Api\ShipperController as Shipper;
use App\Http\Controllers\Api\CheckoutController as Checkout;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace'=>'api'],function (){
    Route::post('login',[User::class, 'login']);
    Route::post('register',[User::class, 'register']);

    Route::post('emailverify', [User::class, 'emailverify']);
    Route::post('resendemailverification', [User::class, 'resendemailverification']);
    Route::post('editprofile',[User::class, 'editprofile']);
    Route::post('getprofile',[User::class, 'getprofile']);
    Route::post('changepassword',[User::class, 'changepassword']);
    Route::post('forgotPassword',[User::class, 'forgotPassword']);
    // Route::get('restaurantslocation','UserController@restaurantslocation');
    // Route::get('isopenclose','TimeController@isopenclose');

    //Driver
    Route::post('driverlogin',[User::class, 'driverlogin']);

    Route::get('category',[Category::class, 'category']);

    Route::post('item',[Item::class, 'item']);
    Route::post('itemdetails',[Item::class, 'itemdetails']);
    Route::post('searchitem',[Item::class, 'searchitem']);
    // Route::post('addfavorite','ItemController@addfavorite');
    // Route::post('favoritelist','ItemController@favoritelist');
    // Route::post('removefavorite','ItemController@removefavorite');
    Route::post('latestitem',[Item::class, 'latestitem']);



    // Route::post('checkpincode','CheckoutController@checkpincode');
    Route::post('summary',[Checkout::class, 'summary']);
    Route::post('order',[Checkout::class, 'order']);
    Route::post('orderhistory',[Checkout::class, 'orderhistory']);
    Route::post('getorderdetails',[Checkout::class, 'getorderdetails']);
    Route::post('ordercancel',[Checkout::class, 'ordercancel']);
    Route::get('promocodelist',[Checkout::class, 'promocodelist']);
    Route::post('promocode',[Checkout::class, 'promocode']);
    Route::post('exploreitem',[Checkout::class, 'exploreitem']);



    //Driver
    Route::post('driverlogin',[Shipper::class, 'login']);
    Route::post('drivergetprofile',[Shipper::class, 'getprofile']);
    Route::post('drivereditprofile',[Shipper::class, 'editprofile']);
    Route::post('driverchangepassword',[Shipper::class, 'changepassword']);
    Route::post('driverforgotPassword',[Shipper::class, 'forgotPassword']);
    Route::post('driverongoingorder',[Shipper::class, 'ongoingorder']);
    Route::post('driverorder',[Shipper::class, 'orderhistory']);
    Route::post('driverorderdetails',[Shipper::class, 'getorderdetails']);
    Route::post('delivered',[Shipper::class, 'delivered']);
});
