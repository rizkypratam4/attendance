<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'CKG',
                'description' => 'Head Office',
                'latitude' => -6.21462,
                'longitude' => 106.84513,
                'address' => 'Jl. Raya Bekasi No.Km. 23, Cakung Bar., Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13910',
                'is_active' => true,
            ],
            [
                'name' => 'KIP',
                'description' => 'Plant',
                'latitude' => -6.21462,
                'longitude' => 106.84513,
                'address' => 'QWQ7+PCP, RT.2/RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13930',
                'is_active' => true,
            ],
        ];
        
        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
