<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'customer_id',
        'invoice_date',
        'order_total',
        'prev_balance',
        'subtotal',
        'discount',
        'total',
        'due_date',
        'invoice_number',
        'invoice_status',
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

    public function invoiceProduct()
    {
        # code...
        return $this->hasMany(InvoiceProduct::class, 'invoice_id', 'id');
    }
}
