<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $designations = [
            ['name' => 'Manager', 'code' => 'MGR'],
            ['name' => 'Assistant Manager', 'code' => 'AMGR'],
            ['name' => 'Senior Officer', 'code' => 'SOFF'],
            ['name' => 'Officer', 'code' => 'OFF'],
            ['name' => 'Junior Officer', 'code' => 'JOFF'],
            ['name' => 'Supervisor', 'code' => 'SUP'],
            ['name' => 'Team Leader', 'code' => 'TL'],
            ['name' => 'Executive', 'code' => 'EXEC'],
            ['name' => 'Trainee', 'code' => 'TRN'],
            ['name' => 'Intern', 'code' => 'INT'],
        ];

        foreach ($designations as $designation) {
            Designation::firstOrCreate(
                ['code' => $designation['code']],
                ['name' => $designation['name'], 'is_active' => true],
            );
        }
    }
}
