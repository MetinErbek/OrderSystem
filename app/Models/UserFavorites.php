<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserFavorites extends Model
{
    use HasFactory;

    protected $table = 'user_favorites';
    protected $fillable = [
        'user_id',
        'product_id'
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
