<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization',
        'tagline',
        'email',
        'phone',
        'address',
        'website',
        'logo',
    ];

    public function user()
    {
        # code...
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        # code...
        return $this->hasMany(Product::class, 'organization_id', 'id');
    }

    public function stock()
    {
        # code...
        return $this->hasMany(Stock::class, 'organization_id', 'id');
    }

    public function payment_method()
    {
        # code...
        return $this->hasMany(PaymentMethod::class, 'organization_id', 'id');
    }

    public function supplier()
    {
        # code...
        return $this->hasMany(Supplier::class, 'organization_id', 'id');
    }

    public function purchase()
    {
        # code...
        return $this->hasMany(Purchase::class, 'organization_id', 'id');
    }
}
