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
        'amount',
        'payment_method',
        'status',
        'transaction_id',
    ];
}
