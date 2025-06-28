<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'Admin',
            'description' => 'Admin Department'
        ]);
        Department::create([
            'name' => 'Support',
            'description' => 'Support Department'
        ]);
        // Department::create([
        //     'name' => 'Sales',
        //     'description' => 'Sales Department'
        // ]);
        // Department::create([
        //     'name' => 'Marketing',
        //     'description' => 'Marketing Department'
        // ]);
      
    }
}
