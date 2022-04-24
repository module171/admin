<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $table='order';
    protected $fillable=['user_id','order_number','payment_id','address','promocode','tax','delivery_id','order_notes','status'];

    /**
     * The item that belong to the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function item()
    {
        return $this->belongsToMany(item::class, 'order_detail', 'order_id', 'item_id')->withPivot('qly');
    }
    public function order_detail()
    {
        return $this->hasOne(orderdetail::class, 'order_id', 'id');
    }
    /**
     * The promodecode that belong to the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function promodecode()
    {
        return $this->hasMany(promocode::class, 'order_id', 'id');
    }
    /**
     * Get the shipper that owns the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipper()
    {
        return $this->belongsTo(shipper::class, 'delivery_id', 'id');
    }

    /**
     * Get the user that owns the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get thn bill associated with the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bill()
    {
        return $this->hasOne(bill::class, 'order_id', 'id');
    }
}
