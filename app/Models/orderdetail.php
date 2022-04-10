<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderdetail extends Model
{
    use HasFactory;
    protected $table='order_detail';
    protected $fillable=['item_id','order_id','qly'];
    public function itemimage(){
        return $this->hasOne(ItemImages::class,'item_id','item_id')->select('item_images.id','item_images.item_id',\DB::raw("CONCAT('".url('/public/images/item/')."/', item_images.image) AS image"));
    }

    public function items(){
        return $this->hasOne(item::class,'id','item_id');
    }
}
