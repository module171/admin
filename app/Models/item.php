<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;
    protected $table='item';
    protected $fillable=['cat_id','item_name','item_description','item_price','type_food'];
    public $timestamps = true;
    /**
     * Get the category that owns the item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(categories::class, 'cat_id', 'id');
    }
    public function itemimage()
    {
        return $this->hasOne(ItemImages::class,'item_id','id')->select('item_images.id','item_images.item_id',\DB::raw("CONCAT('".url('/public/images/item/')."/', item_images.image) AS image"));
    }
    public function itemimagedetails(){
        return $this->hasMany(ItemImages::class,'item_id','id')->select('item_id',\DB::raw("CONCAT('".url('/public/images/item/')."/', image) AS itemimage"));
    }

    /**
     * The roles that belong to the item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function order()
    {
        return $this->belongsToMany(order::class, 'order_detail')->withPivot('qly');
    }
}
