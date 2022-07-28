<?php

namespace Database\Seeders;

use App\Models\{Site, Status};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteStatusPivotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site = (new Site())->first();
        $status = (new Status())->get();
        $site->statuses()->attach($status, [
            'percentage' => 0,
        ]);
    }
}
