<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Benchmark extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'front_sale_value', 'upsell_value'];

    protected $casts = [
        'front_sale_value' => 'decimal:2',
        'upsell_value' => 'decimal:2',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
