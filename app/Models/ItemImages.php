<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImages extends Model
{
    use HasFactory;
    protected $table='item_images';
    protected $fillable=['item_id','image'];

    /**
     * Get the item that owns the ItemImages
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item(){
        return $this->belongsTo(item::class, 'item_id', 'id');
    }
}
