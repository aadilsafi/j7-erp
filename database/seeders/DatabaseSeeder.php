<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SatkeholderTypeSeeder;
use Database\Seeders\SalesPlanTemplatesSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            CountryTableSeeder::class,
            StatusTableSeeder::class,
            StatesTableSeeder::class,
            CityTableSeeder::class,
            SiteTableSeeder::class,
            TypeSeeder::class,
            UserTableSeeder::class,
            AdditionalCostsTableSeeder::class,
            SiteStatusPivotTableSeeder::class,
            FloorTableSeeder::class,
            UnitTableSeeder::class,
            StakeholdersSeeder::class,
            LeadSourceTableSeeder::class,
            SalesPlanSeeder::class,
            SalesPlanAdditionalCostSeeder::class,
            SalesPlanInstallmentsSeeder::class,
            SalesPlanTemplatesSeeder::class,
            StakeholderTypeSeeder::class,
            ReceiptsTemplateSeeder::class,
            FileManagementTableSeeder::class,
            ReceiptsTableSeeder::class,
            UnitStakeholdersTableSeeder::class,
            TeamSeeder::class,
            TeamUserSeeder::class,
        ]);
    }
}
