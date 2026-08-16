<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['email' => config('mars.admin_email')],
            [
                'name' => 'Mars Station Admin',
                'is_active' => true,
            ]
        );
    }
}
