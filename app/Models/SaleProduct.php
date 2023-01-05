<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'sale_id',
        'product_id',
        'sale_price',
        'quantity',
        'total_price',
    ];

    public function sale()
    {
        # code...
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function product()
    {
        # code...
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
