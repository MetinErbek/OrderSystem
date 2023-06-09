<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'category_id',
        'price',
        'status'
    ];
    
    protected $hidden = [
        'deleted_at',
        'updated_at',
    ];
}
