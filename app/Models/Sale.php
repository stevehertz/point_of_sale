<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'customer_id',
        'payment_method_id',
        'sales_date',
        'order_total',
        'discount',
        'subtotal',
        'prev_balance',
        'total',
        'paid',
        'balance',
        'tax',
        'sale_tax',
        'shipping',
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

    public function customer()
    {
        # code...
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function sale_product()
    {
        # code...
        return $this->hasMany(SaleProduct::class, 'sale_id', 'id');
    }

    public function payment_method()
    {
        # code...
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}
