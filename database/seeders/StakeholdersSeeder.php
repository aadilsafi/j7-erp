<?php

namespace Database\Seeders;

use App\Models\Stakeholder;
use App\Models\StakeholderType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StakeholdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Stakeholder())->insert([
            [
                'site_id' => 1,
                'full_name' => 'Zain Ali',
                'father_name' => 'Hassan Raza',
                'occupation' => 'Web Developer',
                'designation' => 'Laravel Developer',
                'cnic' => '1234567890123',
                'address' => 'Sarai Kharbooza, Opposite E16 GT Road, Islamabad, Pakistan',
                'contact' => '0512226044',
                'parent_id' => 0,
                'attachment' => 'cnic-back-side.jpg,NADRA.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'full_name' => 'Ali Raza',
                'father_name' => 'Raza',
                'occupation' => 'Web Developer',
                'designation' => 'Laravel Developer',
                'cnic' => '1234567890123',
                'address' => 'Sarai Kharbooza, Opposite E16 GT Road, Islamabad, Pakistan',
                'contact' => '0512226044',
                'parent_id' => 0,
                'attachment' => 'cnic-back-side.jpg,NADRA.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
