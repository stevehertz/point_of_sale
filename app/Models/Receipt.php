<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'sale_id',
        'payment_method_id',
        'payment_date',
        'total_amount',
        'discount',
        'paid_amount',
        'change',
        'type',
        'customer_id',
        'payment_status',
        'sale_status',
        'change',
        'served_by',
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

    public function sale()
    {
        # code...
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function customer()
    {
        # code...
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function payment_method()
    {
        # code...
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}
