<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'MIS', 'subtitle' => 'Tech & Infrastructure'],
            ['name' => 'Production', 'subtitle' => 'Manufacturing & Operations'],
            ['name' => 'Quality', 'subtitle' => 'Quality Control & Assurance'],
            ['name' => 'Warehouse', 'subtitle' => 'Warehouse Management & Logistics'],
            ['name' => 'Workshop', 'subtitle' => 'Maintenance & Repair'],
        ];
        
        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
