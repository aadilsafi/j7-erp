<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new Template())->insert([
            [
                'name' => 'Application Form',
                'slug' => 'application_form_template',
                'image' => '/images/templates/application_form_template.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'File Cancellation Form',
                'slug' => 'file_cancellation_template',
                'image' => '/images/templates/file_cancellation_template.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
