<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'brand',
        'icon',
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
}
