<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = ['user_id', 'total_amount', 'status', 'payment_method'];

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }
}
