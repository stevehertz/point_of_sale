<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'name',
        'short_name',
        'base_unit',
        'operator',
        'operation_value',
    ];

    public function product()
    {
        # code...
        return $this->hasMany(Product::class, 'unit_id', 'id');
    }
}
