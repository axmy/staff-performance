<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Administration', 'code' => 'ADMIN'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Marketing', 'code' => 'MKT'],
            ['name' => 'Sales', 'code' => 'SALES'],
            ['name' => 'Customer Service', 'code' => 'CS'],
            ['name' => 'Maintenance', 'code' => 'MAINT'],
            ['name' => 'Security', 'code' => 'SEC'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['code' => $department['code']],
                ['name' => $department['name'], 'is_active' => true],
            );
        }
    }
}
