<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'head_name', 'target'];

    protected $casts = [
        'target' => 'decimal:2',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
