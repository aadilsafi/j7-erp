<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
            BacklistedStakeholderSeeder::class,
            LeadSourceTableSeeder::class,
            SalesPlanSeeder::class,
            SalesPlanAdditionalCostSeeder::class,
            SalesPlanInstallmentsSeeder::class,
            SalesPlanTemplatesSeeder::class,
            StakeholderTypeSeeder::class,
            ReceiptsTemplateSeeder::class,
            FileActionSeeder::class,
            FileManagementTableSeeder::class,
            BankSeeder::class,
            ReceiptsTableSeeder::class,
            UnitStakeholdersTableSeeder::class,
            // RebateIncentiveSeeder::class,
            TeamSeeder::class,
            TeamUserSeeder::class,
            TemplatesSeeder::class,
            ModelTemplateSeeder::class,
            AccountActionSeeder::class,
            AccountHeadSeeder::class,
            AccountingStartingCodeSeeder::class,
            AccountLedgerSeeder::class,
        ]);
    }
}
