<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@google.com',
            'password' => bcrypt('admin1_*'),
            'is_admin' => true,
        ]);
    }
}
