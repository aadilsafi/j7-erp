<?php

namespace Database\Seeders;

use App\Models\ModelTemplate;
use Illuminate\Database\Seeder;
use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModelTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new ModelTemplate())->insert([
            [
                'model_type' => 'App\Models\FileManagement',
                'template_id' => 1,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileCanecllation',
                'template_id' => 2,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileRefund',
                'template_id' => 3,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileBuyBack',
                'template_id' => 4,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileResale',
                'template_id' => 5,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileTitleTransfer',
                'template_id' => 6,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
