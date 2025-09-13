<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'lat',
        'lng',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}