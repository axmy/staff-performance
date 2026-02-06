<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $designations = [
            ['name' => 'Senior Registered Nurse', 'code' => 'SRN'],
        ];

        foreach ($designations as $designation) {
            Designation::firstOrCreate(
                ['code' => $designation['code']],
                ['name' => $designation['name'], 'is_active' => true],
            );
        }
    }
}
