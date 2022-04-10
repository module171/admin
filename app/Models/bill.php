<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bill extends Model
{
    use HasFactory;
    protected $table='bill';
    protected $fillable=['order_id'];
    public $timestamps = false;


    /**
     * Get the order that owns the bill
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(order::class, 'order_id', 'id');
    }
}
