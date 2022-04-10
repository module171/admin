<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipper extends Model
{
    use HasFactory;
    protected $table='shipper';
    protected $fillable = [
        'name', 'email' , 'mobile' , 'profile_image', 'password','money_earned','status',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',

    ];
    /**
     * Get all of the  order for the shipper
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function  order()
    {
        return $this->hasMany(order::class, 'delivery_id', 'id');
    }
}
