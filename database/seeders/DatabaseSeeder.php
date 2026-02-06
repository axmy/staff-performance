<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('role', 'admin')->exists()) {
            $admin = new User();
            $admin->forceFill([
                'name' => 'Shareefa Adam',
                'email' => 'shary2121@gmail.com',
                'password' => 'shary@2121',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ])->save();
        }

        $this->call([
            DepartmentSeeder::class,
            DesignationSeeder::class,
            ActionTypeSeeder::class,
            DefaultTriggerSeeder::class,
        ]);
    }
}
