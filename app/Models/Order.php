<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'total_price',
        'total_discount',
        'order_status'
    ];
    protected $hidden = [
        'deleted_at',
        'updated_at',
    ];
    
    public function OrderProducts()
    {
        return $this->hasMany('App\Models\OrderProducts', 'order_id', 'id');
    }

}
