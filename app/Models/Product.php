<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'product',
        'category_id',
        'product_type',
        'product_code',
        'barcodes',
        'purchase_price',
        'selling_price',
        'stocks',
        'brand_id',
        'unit_id',
        'sale_unit',
        'purchase_unit',
    ];

    public function user()
    {
        # code...
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function organization()
    {
        # code...
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function category()
    {
        # code...
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function purchase_product()
    {
        # code...
        return $this->hasMany(PurchaseProduct::class, 'product_id', 'id');
    }

    public function sale_product()
    {
        # code...
        return $this->hasMany(SaleProduct::class, 'product_id', 'id');
    }

    public function brand()
    {
        # code...
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function unit()
    {
        # code...
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
