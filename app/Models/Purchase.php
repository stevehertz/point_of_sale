<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'supplier_id',
        'supplier_name',
        'purchase_date',
        'order_amount',
        'discount',
        'subtotal',
        'prev_balance',
        'total_amount',
        'paid_amount',
        'balance',
        'purchase_status',
        'order_status',
        'payment_status',
    ];

    public function organization()
    {
        # code...
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function supplier()
    {
        # code...
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function purchase_product()
    {
        # code...
        return $this->hasMany(PurchaseProduct::class, 'purchase_id', 'id');
    }
}
