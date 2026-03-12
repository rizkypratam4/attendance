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
            ['name' => 'Workshop', 'subtitle' => 'Equipment Maintenance & Repair'],
            ['name' => 'Director', 'subtitle' => 'Leadership & Strategy'],
            ['name' => 'Engineering', 'subtitle' => 'Product Development & Innovation'],
            ['name' => 'Finance & Accounting', 'subtitle' => 'Financial Management'],
            ['name' => 'HRGA', 'subtitle' => 'Human Resources & General Affairs'],
            ['name' => 'HSE', 'subtitle' => 'Health, Safety & Environment'],
            ['name' => 'Internal Audit', 'subtitle' => 'Internal Audit & Compliance'],
            ['name' => 'Maintenance', 'subtitle' => 'Maintenance & Repair'],
            ['name' => 'MR', 'subtitle' => 'Material Requisition & Procurement'],
            ['name' => 'PPC', 'subtitle' => 'Production Planning & Control'],
            ['name' => 'Purchasing', 'subtitle' => 'Procurement & Supplier Management'],
            ['name' => 'Sales', 'subtitle' => 'Sales & Marketing'],
            ['name' => 'Secretary', 'subtitle' => 'Executive Support & Administration'],
        ];
        
            foreach ($departments as $department) {
                Department::create($department);
        }
    }
}
