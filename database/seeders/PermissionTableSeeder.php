<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Permission())->insert([

            // Roles Routes
            [
                'name' => 'roles.index',
                'guard_name' => 'web',
                'show_name' => 'View Roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.create',
                'guard_name' => 'web',
                'show_name' => 'Create Role',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.store',
                'guard_name' => 'web',
                'show_name' => 'Store Role',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.edit',
                'guard_name' => 'web',
                'show_name' => 'Edit Role',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.update',
                'guard_name' => 'web',
                'show_name' => 'Update Role',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.destroy',
                'show_name' => 'Destroy Role',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.destroy-selected',
                'guard_name' => 'web',
                'show_name' => 'Destroy Selected Roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.make-default',
                'guard_name' => 'web',
                'show_name' => 'Make Default Roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Permissions Routes
            [
                'name' => 'permissions.index',
                'guard_name' => 'web',
                'show_name' => 'View Permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.view_all',
                'guard_name' => 'web',
                'show_name' => 'View All Site Roles Permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.create',
                'guard_name' => 'web',
                'show_name' => 'Create Permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.store',
                'guard_name' => 'web',
                'show_name' => 'Store Permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.edit',
                'guard_name' => 'web',
                'show_name' => 'Edit Permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.update',
                'guard_name' => 'web',
                'show_name' => 'Update Permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.destroy',
                'guard_name' => 'web',
                'show_name' => 'Destroy Permission',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.destroy-selected',
                'guard_name' => 'web',
                'show_name' => 'Destroy Selected Permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.assign-permission',
                'show_name' => 'Assign Permission',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.revoke-permission',
                'show_name' => 'Revoke Permission',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.edit-own-permission',
                'show_name' => 'Edit Own Permission',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'countries.cities',
                'show_name' => 'View Cities',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Sites Routes
            [
                'name' => 'site.cache.flush',
                'guard_name' => 'web',
                'show_name' => 'Refresh Site Cache',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.index',
                'show_name' => 'View Sites',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.create',
                'show_name' => 'Create Sites',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.store',
                'show_name' => 'Store Sites',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.edit',
                'show_name' => 'Edit Sites',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.update',
                'show_name' => 'Update Sites',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.destroy',
                'show_name' => 'Destroy Sites',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.destroy-selected',
                'show_name' => 'Destroy Selected Sites',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.configurations.configView',
                'show_name' => 'View Sites Configuration',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.configurations.configStore',
                'show_name' => 'Store Sites Configuration',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Types Routes
            [
                'name' => 'sites.types.index',
                'show_name' => 'View Sites Types',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.types.create',
                'show_name' => 'Create Site Types',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.types.store',
                'show_name' => 'Store Sites Types',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.types.edit',
                'show_name' => 'Edit Sites Types',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.types.update',
                'show_name' => 'Update Sites Types',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.types.destroy',
                'show_name' => 'Destroy Sites Types',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.types.destroy-selected',
                'show_name' => 'Destroy Selected Sites Types',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional Costs Routes
            [
                'name' => 'sites.additional-costs.index',
                'show_name' => 'View Sites Additional Costs',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.additional-costs.create',
                'show_name' => 'Create Sites Additional Costs',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.additional-costs.store',
                'show_name' => 'Store Sites Additional Costs',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.additional-costs.edit',
                'show_name' => 'Edit Sites Additional Costs',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.additional-costs.update',
                'show_name' => 'Update Sites Additional Costs',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.additional-costs.destroy-selected',
                'show_name' => 'Destroy Selected Sites Additional Costs',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Floor Routes
            [
                'name' => 'sites.floors.index',
                'show_name' => 'View Sites Floors',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.create',
                'show_name' => 'Create Sites Floors',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.store',
                'show_name' => 'Store Sites Floor',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.edit',
                'show_name' => 'Edit Sites Floors',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.update',
                'show_name' => 'Update Sites Floors',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.destroy-selected',
                'show_name' => 'Destroy Selected Sites Floors',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.copyView',
                'show_name' => 'View Sites Copy Floors',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.copyStore',
                'show_name' => 'Store Copy Sites Floor ',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Unit Routes
            [
                'name' => 'sites.floors.units.index',
                'show_name' => 'View Sites Floors Units',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.create',
                'show_name' => 'Create Site Floor Units',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.store',
                'show_name' => 'Store Site Floor Units',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.edit',
                'show_name' => 'Edit Site Flooe Units',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.update',
                'show_name' => 'Update Sites Floor Units',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.destroy-selected',
                'show_name' => 'Destroy Sites Floors Units',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Batchs Routes
            [
                'name' => 'batches.byid',
                'show_name' => 'Batches By id',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Sales Plan
            [
                'name' => 'sites.floors.units.sales-plans.index',
                'show_name' => 'View Sales Plan',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.sales-plans.create',
                'show_name' => 'Create Sales Plan',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.sales-plans.store',
                'show_name' => 'Store Sales Plan',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.sales-plans.edit',
                'show_name' => 'Edit Sales Plan',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.sales-plans.update',
                'show_name' => 'Update Sales Plan',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.floors.units.sales-plans.destroy-selected',
                'show_name' => 'Destroy Selected Sales Plan',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //Sales Plan Print
            [
                'name' => 'sites.floors.units.sales-plans.templates.print',
                'show_name' => 'Print Sales Plan',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Lead Sources Routes
            [
                'name' => 'sites.lead-sources.index',
                'show_name' => 'View Lead Sources',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.lead-sources.create',
                'show_name' => 'Create Lead Source',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.lead-sources.store',
                'show_name' => 'Store Lead Source',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.lead-sources.edit',
                'show_name' => 'Edit Lead Source',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.lead-sources.update',
                'show_name' => 'Update Lead Source',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sites.lead-sources.destroy-selected',
                'show_name' => 'Delete Selected Lead Sources',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

        (new Role())->find(1)->givePermissionTo((new Permission())->pluck('id'));
    }
}
