<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            UserTableSeeder::class,
            CountryTableSeeder::class,
            StatusTableSeeder::class,
            StatesTableSeeder::class,
            CityTableSeeder::class,
            SiteTableSeeder::class,
            TypeSeeder::class,
            AdditionalCostsTableSeeder::class,
            SiteStatusPivotTableSeeder::class,
            FloorTableSeeder::class,
            UnitTableSeeder::class,
            StakeholdersSeeder::class,
            SalesPlanSeeder::class,
            SalesPlanAdditionalCostSeeder::class,
            SalesPlanInstallmentsSeeder::class,
        ]);
    }
}
