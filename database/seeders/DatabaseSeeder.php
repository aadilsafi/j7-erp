<?php

namespace Database\Seeders;

use DB;
use File;
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
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableChunkOneSeeder::class,
            CitiesTableChunkTwoSeeder::class,
            CitiesTableChunkThreeSeeder::class,
            CitiesTableChunkFourSeeder::class,
            CitiesTableChunkFiveSeeder::class,
            CitiesChunkSixSeeder::class,
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            StatusTableSeeder::class,
            SiteTableSeeder::class,
            // TypeSeeder::class,
            UserTableSeeder::class,
            // AdditionalCostsTableSeeder::class,
            SiteStatusPivotTableSeeder::class,
            // FloorTableSeeder::class,
            // UnitTableSeeder::class,
            // StakeholdersSeeder::class,
            BacklistedStakeholderSeeder::class,
            LeadSourceTableSeeder::class,
            // SalesPlanSeeder::class,
            // SalesPlanAdditionalCostSeeder::class,
            // SalesPlanInstallmentsSeeder::class,
            SalesPlanTemplatesSeeder::class,
            // StakeholderTypeSeeder::class,
            ReceiptsTemplateSeeder::class,
            FileActionSeeder::class,
            // FileManagementTableSeeder::class,
            BankSeeder::class,
            // ReceiptsTableSeeder::class,
            // UnitStakeholdersTableSeeder::class,
            // RebateIncentiveSeeder::class,
            // TeamSeeder::class,
            // TeamUserSeeder::class,
            TemplatesSeeder::class,
            ModelTemplateSeeder::class,
            AccountActionSeeder::class,
            AccountHeadSeeder::class,
            AccountingStartingCodeSeeder::class,
            // AccountLedgerSeeder::class,


        ]);

        // foreach (File::glob(public_path('app-assets/pdf/sales-plans/investment-plan/*')) as $key => $path) {
        //     $test = File::delete($path);
        //     if($test) {
        //         $this->command->info('Deleted: ' . $path);
        //     }
        // }
        // foreach (File::glob(public_path('app-assets/pdf/sales-plans/payment-plan/*')) as $key => $path) {
        //     $test = File::delete($path);
        //     if($test) {
        //         $this->command->info('Deleted: ' . $path);
        //     }
        // }
        // foreach (File::glob(public_path('app-assets/pdf/sales-plans/qrcodes/*')) as $key => $path) {
        //     $test = File::delete($path);
        //     if($test) {
        //         $this->command->info('Deleted: ' . $path);
        //     }
        // }
        // foreach (File::glob(public_path('app-assets/server-uploads/attachments/*')) as $key => $path) {
        //     $test = File::deleteDirectory($path);
        //     if($test) {
        //         $this->command->info('Deleted: ' . $path);
        //     }
        // }
        File::cleanDirectory(public_path('app-assets/pdf/sales-plans/investment-plan/'));
        File::cleanDirectory(public_path('app-assets/pdf/sales-plans/payment-plan/'));
        File::cleanDirectory(public_path('app-assets/pdf/sales-plans/qrcodes/'));
        File::cleanDirectory(public_path('app-assets/server-uploads/attachments/'));


        if (DB::connection()->getName() == 'pgsql') {
            $tablesToCheck = array('countries', 'states', 'cities', 'roles', 'permissions', 'users', 'banks');
            foreach ($tablesToCheck as $tableToCheck) {
                $this->command->info('Checking the next id sequence for ' . $tableToCheck);
                $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
                $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\'' . $tableToCheck . '_id_seq\')'))->first();
                if ($nextId->nextval < $highestId->max) {
                    DB::select('SELECT setval(\'' . $tableToCheck . '_id_seq\', ' . $highestId->max . ')');
                    $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
                    $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\'' . $tableToCheck . '_id_seq\')'))->first();
                    if ($nextId->nextval > $highestId->max) {
                        $this->command->info($tableToCheck . ' autoincrement corrected');
                    } else {
                        $this->command->info('Arff! The nextval sequence is still all screwed up on ' . $tableToCheck);
                    }
                }
            }
        }
    }
}
