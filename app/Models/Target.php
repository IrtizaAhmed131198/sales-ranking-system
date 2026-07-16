<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Target extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'target_amount', 'month'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
