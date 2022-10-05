<?php

namespace Database\Seeders;

use App\Models\FileAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FileActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new FileAction())->insert([
            [
                'site_id' => 1,
                'name' => 'Active',
            ],
            [
                'site_id' => 1,
                'name' => 'File Refunded',
            ],
            [
                'site_id' => 1,
                'name' => 'File Buy Backed',
            ],
            [
                'site_id' => 1,
                'name' => 'File Canceled',
            ],
            [
                'site_id' => 1,
                'name' => 'File Resaled',
            ],
            [
                'site_id' => 1,
                'name' => 'File Title Transfered',
            ],
            [
                'site_id' => 1,
                'name' => 'File Adjusted',
            ],
            [
                'site_id' => 1,
                'name' => 'File Unit Shifted',
            ],
        ]);
    }
}
