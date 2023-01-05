<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'purchase_id',
        'product_id',
        'quantity',
        'total_amount',
    ];


    public function product()
    {
        # code...
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function purchase()
    {
        # code...
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }
}
