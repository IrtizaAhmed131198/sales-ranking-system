<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount', 'refund_month'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
