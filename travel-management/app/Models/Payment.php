<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payer_name',
        'payer_email',
        'contact',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'user_id',
    ];

    // Optional: relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
