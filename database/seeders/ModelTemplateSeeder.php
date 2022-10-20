<?php

namespace Database\Seeders;

use App\Models\ModelTemplate;
use Illuminate\Database\Seeder;
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
        $data = [
            [
                'model_type' => 'App\Models\FileCanecllation',
                'template_id' => 2,
                'parent_id' => 0,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileRefund',
                'template_id' => 3,
                'parent_id' => 0,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileBuyBack',
                'template_id' => 4,
                'parent_id' => 0,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileResale',
                'template_id' => 5,
                'parent_id' => 0,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileTitleTransfer',
                'template_id' => 6,
                'parent_id' => 0,
                'default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileManagement',
                'template_id' => 0,
                'parent_id' => 0,
                'default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileManagement',
                'template_id' => 1,
                'parent_id' => 7,
                'default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_type' => 'App\Models\FileManagement',
                'template_id' => 7,
                'parent_id' => 7,
                'default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($data as $item) {
            (new ModelTemplate())->create($item);
        }
    }
}
