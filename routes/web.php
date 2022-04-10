<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\admin\AdminController as admin;
use App\Http\Controllers\admin\CategoriesController as Categories;
use App\Http\Controllers\admin\ItemController as Item;
use App\Http\Controllers\admin\OrderController as Order;
use App\Http\Controllers\admin\BillController as Bill;
use App\Http\Controllers\admin\ShipperController as Ship;
use App\Http\Controllers\admin\PromocodeController as Promocode;
use App\Models\order as ModelsOrder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('createpromocode', function () {
    Schema::create('promocode_order', function (Blueprint $table) {

        $table->integer('promode_id')->nullable();
        $table->integer('order_id')->nullable();
        $table->timestamps();
    });
});
Route::get('/', [admin::class, 'login'])->name('login');
Route::post('checklogin', [admin::class, 'checklogin'])->name("checklogin");
Route::prefix('admin')->middleware('auth.login')->group(function () {

    //item
    Route::get('item', [Item::class, 'index'])->name('item-index');
    Route::get('item-list', [Item::class, 'list'])->name('item-list');
    Route::post('item-add', [Item::class, 'add'])->name('item-add');
    Route::post('item-update', [Item::class, 'update'])->name('item-update');
    Route::post('item-updatestatus', [Item::class, 'status'])->name('item-updatestatus');
    Route::post('item-getbyid', [Item::class, 'show'])->name('item-getbyid');
    Route::post('item-delete', [Item::class, 'destroy'])->name('item-delete');
    Route::get('item-images/{id}', [Item::class, 'itemimages'])->name('item-images');

    //image item
    Route::post('image-add', [Item::class, 'storeimages'])->name('image-add');
    Route::post('image-update', [Item::class, 'updateimage'])->name('image-update');
    Route::post('image-delete', [Item::class, 'destroyimage'])->name('image-delete');
    Route::post('image-getbyid', [Item::class, 'showimage'])->name('image-getbyid');


    //categories
    Route::get('categories', [Categories::class, 'index'])->name('categories-index');
    Route::get('categories-list', [Categories::class, 'list'])->name('categories-list');
    Route::post('categories-add', [Categories::class, 'add'])->name('categories-add');
    Route::post('categories-update', [Categories::class, 'update'])->name('categories-update');
    Route::get('categories-updatestatus', [Categories::class, 'status'])->name('categories-updatestatus');
    Route::post('categories-getbyid', [Categories::class, 'show'])->name('categories-getbyid');
    Route::post('categories-delete', [Categories::class, 'delete'])->name('categories-delete');

    //admin
    Route::post('admin-setting', [admin::class, 'settings'])->name('admin-setting');
    Route::post('admin-changepass', [admin::class, 'changePassword'])->name('admin-changepass');
    Route::get('/', [admin::class, 'index'])->name('dashboard');
    Route::get('logout', [admin::class, 'logout'])->name('logout');

    //order
     Route::get('/invoice/{id}', [Order::class, 'invoice'])->name('invoice');
    Route::post('order-status', [Order::class, 'update'])->name('order-status');
     Route::get('order', [Order::class, 'index'])->name('order');
     Route::post('create-bill', [Order::class, 'createbill'])->name('create-bill');

     Route::post('assign-shipper', [Order::class, 'assign'])->name('assign-shipper');

     //bill
     Route::get('bill', [Bill::class, 'index'])->name('bill');
      Route::get('invoice-bill/{id}', [Bill::class, 'invoice'])->name('invoice-bill');

    //shipper

     Route::get('shipper', [Ship::class, 'index'])->name('shipper');
     Route::get('shipper-list', [Ship::class, 'list'])->name('shipper-list');
     Route::post('shipper-add', [Ship::class, 'store'])->name('shipper-add');
     Route::post('shipper-update', [Ship::class, 'update'])->name('shipper-update');
     Route::post('shipper-status', [Ship::class, 'status'])->name('shipper-status');
     Route::post('shipper-getbyid', [Ship::class, 'show'])->name('shipper-getbyid');


    //promocode
    Route::get('promocode', [Promocode::class, 'index'])->name('promocode');
    Route::get('promocode-list', [Promocode::class, 'list'])->name('promocode-list');
    Route::post('promocode-add', [Promocode::class, 'store'])->name('promocode-add');
    Route::post('promocode-update', [Promocode::class, 'update'])->name('promocode-update');
    Route::post('promocode-status', [Promocode::class, 'status'])->name('promocode-status');
    Route::post('promocode-getbyid', [Promocode::class, 'show'])->name('promocode-getbyid');
});
