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
            [
                'name' => 'File Refund Form',
                'slug' => 'file_refund_template',
                'image' => '/images/templates/file_refund_template.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'File Buy Back Form',
                'slug' => 'file_buy_back_template',
                'image' => '/images/templates/file_buy_back_template.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'File Resale Form',
                'slug' => 'file_resale_template',
                'image' => '/images/templates/file_resale_template.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'File Title Transfer Form',
                'slug' => 'file_title_transfer_template',
                'image' => '/images/templates/file_title_transfer_template.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
