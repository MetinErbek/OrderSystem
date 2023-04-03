<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProducts extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'order_products';
    protected $fillable = [
        'order_id',
        'product_id',
        'product_price',
        'qty',
        'price',
        'discount'
    ];
    protected $hidden = [
        'deleted_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

}
