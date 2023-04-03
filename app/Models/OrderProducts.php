<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProducts extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'orders';
    protected $fillable = [
        'order_id',
        'product_id',
        'product_price',
        'qty',
        'price',
        'discount'
    ];

}
