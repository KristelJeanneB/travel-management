<?php

// app/Models/FailedLogin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedLogin extends Model
{
    protected $fillable = ['email', 'ip_address'];
}
