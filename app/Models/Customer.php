<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'full_names',
        'email',
        'phone',
        'address',
        'balance',
    ];

    public function sale()
    {
        # code...
        return $this->hasMany(Sale::class, 'customer_id', 'id');
    }
}
