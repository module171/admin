<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promocode extends Model
{
    use HasFactory;
    protected $table='promocode';
    protected $fillable=['offer_name','offer_code','offer_amount','description'];

    /**
     * The roles that belong to the promocode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function order()
    {
        return $this->belongsTo(order::class, 'order_id', 'id');
    }
}
