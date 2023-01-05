<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function organization()
    {
        # code...
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function invoice()
    {
        # code...
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function product()
    {
        # code...
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
