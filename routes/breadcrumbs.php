<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('lang.leftbar.dashboard'), route('dashboard'));
});

// Roles Breadcrumbs
Breadcrumbs::for('roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('lang.roles.role_plural'), route('roles.index'));
});

Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('roles.index');
    $trail->push(__('lang.commons.create') . ' ' . __('lang.roles.role_singular'), route('roles.create'));
});

Breadcrumbs::for('roles.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('roles.index');
    $trail->push(__('lang.commons.edit') . ' ' . __('lang.roles.role_singular'));
});

// Permisisons Breadcrumbs
Breadcrumbs::for('permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('lang.permissions.permission_plural'), route('permissions.index'));
});

Breadcrumbs::for('permissions.create', function (BreadcrumbTrail $trail) {
    $trail->parent('permissions.index');
    $trail->push(__('lang.permissions.create_permission'), route('permissions.create'));
});

Breadcrumbs::for('permissions.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('permissions.index');
    $trail->push(__('lang.permissions.edit_permission'));
});

// Sites Breadcrumbs
Breadcrumbs::for('sites.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Sites', route('sites.index'));
});

Breadcrumbs::for('sites.create', function (BreadcrumbTrail $trail) {
    $trail->parent('sites.index');
    $trail->push('Create Sites', route('sites.create'));
});

Breadcrumbs::for('sites.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('sites.index');
    $trail->push('Edit Site');
});

//Types Breadcrumbs
Breadcrumbs::for('sites.types.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Types', route('sites.types.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.types.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.types.index', encryptParams($site_id));
    $trail->push('Create Type');
});

Breadcrumbs::for('sites.types.edit', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.types.index', encryptParams($site_id));
    $trail->push('Edit Type');
});
Breadcrumbs::for('sites.types.import', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.types.index', $site_id);
    $trail->push('Import Type');
});

//Additional Costs Breadcrumbs
Breadcrumbs::for('sites.additional-costs.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Additional Costs', route('sites.additional-costs.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.additional-costs.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.additional-costs.index', $site_id);
    $trail->push('Create Additional Cost');
});

Breadcrumbs::for('sites.additional-costs.edit', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.additional-costs.index', $site_id);
    $trail->push('Edit Additional Cost');
});
Breadcrumbs::for('sites.additional-costs.import', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.additional-costs.index', $site_id);
    $trail->push('Import Additional Costs');
});
//Floor Breadcrumbs
Breadcrumbs::for('sites.floors.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Floors', route('sites.floors.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.floors.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Create Floor');
});
Breadcrumbs::for('sites.floors.import', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Import Floor');
});

Breadcrumbs::for('sites.floors.copy', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Copy Floor');
});

Breadcrumbs::for('sites.floors.edit', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Edit Floor');
});

Breadcrumbs::for('sites.floors.floor-plan', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Floor Plan');
});


Breadcrumbs::for('sites.floors.preview', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Floors Preview');
});

//Units Breadcrumbs
Breadcrumbs::for('sites.floors.units.index', function (BreadcrumbTrail $trail, $site_id, $floor_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Units', route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id]));
});

Breadcrumbs::for('sites.floors.units.preview', function (BreadcrumbTrail $trail, $site_id, $floor_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Units Preview', route('sites.floors.units.preview', ['site_id' => $site_id, 'floor_id' => $floor_id]));
});

Breadcrumbs::for('sites.floors.units.create', function (BreadcrumbTrail $trail, $site_id, $floor_id) {
    $trail->parent('sites.floors.units.index', $site_id, $floor_id);
    $trail->push('Create Unit');
});

Breadcrumbs::for('sites.floors.units.edit', function (BreadcrumbTrail $trail, $site_id, $floor_id) {
    $trail->parent('sites.floors.units.index', $site_id, $floor_id);
    $trail->push('Edit Unit');
});

Breadcrumbs::for('sites.floors.units.import', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Import');
});
//Unit Sales Plan Breadcrumbs
Breadcrumbs::for('sites.floors.units.sales-plans.index', function (BreadcrumbTrail $trail, $site_id, $floor_id, $unit_id) {
    $trail->parent('sites.floors.units.index', $site_id, $floor_id);
    $trail->push('Sales Plan', route('sites.floors.units.sales-plans.index', ['site_id' => $site_id, 'floor_id' => $floor_id, 'unit_id' => $unit_id]));
});

Breadcrumbs::for('sites.floors.units.sales-plans.create', function (BreadcrumbTrail $trail, $site_id, $floor_id, $unit_id) {
    $trail->parent('sites.floors.units.sales-plans.index', $site_id, $floor_id, $unit_id);
    $trail->push('Sales Plan Create');
});

//Stakholders Breadcrumbs
Breadcrumbs::for('sites.stakeholders.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Stakeholders', route('sites.stakeholders.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.stakeholders.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.stakeholders.index', encryptParams($site_id));
    $trail->push('Create Stakeholder');
});

Breadcrumbs::for('sites.stakeholders.edit', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.stakeholders.index', encryptParams($site_id));
    $trail->push('Edit Stakeholder');
});
Breadcrumbs::for('sites.stakeholders.import', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.stakeholders.index', $site_id);
    $trail->push('Import Stakeholders');
});
Breadcrumbs::for('sites.stakeholders.import.kins', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.stakeholders.index', $site_id);
    $trail->push('Import Stakeholders Kins');
});
//Users Breadcrumbs
Breadcrumbs::for('sites.users.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Users', route('sites.users.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.users.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.users.index', encryptParams($site_id));
    $trail->push('Create Users');
});

Breadcrumbs::for('sites.users.edit', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.users.index', encryptParams($site_id));
    $trail->push('Edit Users');
});

//Teams Breadcrumbs
Breadcrumbs::for('sites.teams.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Teams', route('sites.teams.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.teams.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.teams.index', encryptParams($site_id));
    $trail->push('Create Team');
});

Breadcrumbs::for('sites.teams.edit', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.teams.index', encryptParams($site_id));
    $trail->push('Edit Team');
});

//Leads Source Breadcrumbs
Breadcrumbs::for('sites.lead-sources.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Lead Sources', route('sites.lead-sources.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.lead-sources.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.lead-sources.index', $site_id);
    $trail->push('Create Lead Source');
});

Breadcrumbs::for('sites.lead-sources.edit', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.lead-sources.index', $site_id);
    $trail->push('Edit Lead Source');
});

//receipts Breadcrumbs
Breadcrumbs::for('sites.floors.units.sales-plans.receipts.index', function (BreadcrumbTrail $trail, $site_id, $floor_id, $unit_id, $sales_plan_id) {
    $trail->parent('sites.floors.units.sales-plans.index', $site_id, $floor_id, $unit_id);
    $trail->push('Receipts');
});


//receipts Breadcrumbs Main
Breadcrumbs::for('sites.receipts.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Receipts', route('sites.receipts.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.receipts.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.receipts.index', $site_id);
    $trail->push('Create Receipts');
});

Breadcrumbs::for('sites.receipts.show', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.receipts.index', $site_id);
    $trail->push(' Receipt Details');
});

//File Management Breadcrumbs

Breadcrumbs::for('sites.file-managements.view-files', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('View Customer Files', route('sites.file-managements.view-files', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.customers', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('File Management', route('sites.file-managements.customers', ['site_id' => $site_id]));
    $trail->push('Customer\'s List', route('sites.file-managements.customers', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.customers.units', function (BreadcrumbTrail $trail, $site_id, $customer_id) {
    $trail->parent('sites.file-managements.customers', $site_id);
    $trail->push('Units List', route('sites.file-managements.customers.units', ['site_id' => $site_id, 'customer_id' => $customer_id]));
});

Breadcrumbs::for('sites.file-managements.customers.units.files.index', function (BreadcrumbTrail $trail, $site_id, $customer_id, $unit_id) {
    $trail->parent('sites.file-managements.customers.units', $site_id, $customer_id, $unit_id);
    $trail->push('Preview Files', route('sites.file-managements.customers.units.files.index', ['site_id' => $site_id, 'customer_id' => $customer_id, 'unit_id' => $unit_id]));
});

Breadcrumbs::for('sites.file-managements.customers.units.files.show', function (BreadcrumbTrail $trail, $site_id, $customer_id, $unit_id) {
    $trail->parent('sites.file-managements.view-files', $site_id, $customer_id, $unit_id);
    $trail->push('Files', route('sites.file-managements.customers.units.files.index', ['site_id' => $site_id, 'customer_id' => $customer_id, 'unit_id' => $unit_id]));
});

Breadcrumbs::for('sites.file-managements.customers.units.files.create', function (BreadcrumbTrail $trail, $site_id, $customer_id, $unit_id) {
    $trail->parent('sites.file-managements.customers', $site_id);
    $trail->push('Create Files', route('sites.file-managements.customers.units.files.create', ['site_id' => $site_id, 'customer_id' => $customer_id, 'unit_id' => $unit_id]));
});

Breadcrumbs::for('sites.file-managements.customers.units.files.viewFile', function (BreadcrumbTrail $trail, $site_id, $customer_id, $unit_id) {
    $trail->parent('sites.file-managements.customers', $site_id);
    $trail->push('View File', route('sites.file-managements.customers.units.files.create', ['site_id' => $site_id, 'customer_id' => $customer_id, 'unit_id' => $unit_id]));
});

//Rebate Incentive Breadcrumbs Main
Breadcrumbs::for('sites.file-managements.rebate-incentive.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Rebate Incentive Details', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.rebate-incentive.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.rebate-incentive.index', $site_id);
    $trail->push('Create Rebate Incentive', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

//Dealer Incentive Breadcrumbs Main
Breadcrumbs::for('sites.file-managements.dealer-incentive.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Dealer Incentive Details', route('sites.file-managements.dealer-incentive.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.dealer-incentive.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.dealer-incentive.index', $site_id);
    $trail->push('Create Dealer Incentive', route('sites.file-managements.dealer-incentive.index', ['site_id' => $site_id]));
});


//File Refund  Breadcrumbs Main
Breadcrumbs::for('sites.file-managements.file-refund.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('File Refund', route('sites.file-managements.file-refund.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-refund.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-refund.index', $site_id);
    $trail->push('Create File Refund', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-refund.preview', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-refund.index', $site_id);
    $trail->push('Preview File Refund', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

//File buy back  Breadcrumbs Main
Breadcrumbs::for('sites.file-managements.file-buy-back.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('File Buy Back', route('sites.file-managements.file-buy-back.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-buy-back.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-buy-back.index', $site_id);
    $trail->push('Create File Buy Back', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-buy-back.preview', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-buy-back.index', $site_id);
    $trail->push('Preview File Buy Back', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

//File Cancellation  Breadcrumbs Main
Breadcrumbs::for('sites.file-managements.file-cancellation.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('File Cancellation', route('sites.file-managements.file-cancellation.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-cancellation.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-cancellation.index', $site_id);
    $trail->push('Create Cancellation', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-cancellation.preview', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-cancellation.index', $site_id);
    $trail->push('Preview Cancellation', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

//File Resale  Breadcrumbs Main
Breadcrumbs::for('sites.file-managements.file-resale.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('File Resale', route('sites.file-managements.file-resale.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-resale.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-resale.index', $site_id);
    $trail->push('Create File Resale', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-resale.preview', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-resale.index', $site_id);
    $trail->push('Preview Resale', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

//File Title Transfer Breadcrumbs Main
Breadcrumbs::for('sites.file-managements.file-title-transfer.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('File Title Transfer', route('sites.file-managements.file-title-transfer.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-title-transfer.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-title-transfer.index', $site_id);
    $trail->push('Create File Title Transfer', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.file-managements.file-title-transfer.preview', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.file-managements.file-title-transfer.index', $site_id);
    $trail->push('Preview File Title Transfer', route('sites.file-managements.rebate-incentive.index', ['site_id' => $site_id]));
});

// Custom Fields Breadcrumbs
Breadcrumbs::for('sites.settings.custom-fields.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.custom-fields.index', ['site_id' => $site_id]));
    $trail->push('Custom Fields', route('sites.settings.custom-fields.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.settings.custom-fields.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.settings.custom-fields.index', $site_id);
    $trail->push('Create Custom Fields', route('sites.settings.custom-fields.create', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.settings.custom-fields.edit', function (BreadcrumbTrail $trail, $site_id, $id) {
    $trail->parent('sites.settings.custom-fields.index', $site_id);
    $trail->push('Edit Custom Fields', route('sites.settings.custom-fields.edit', ['site_id' => $site_id, 'id' => $id]));
});

// Accounts Breadcrumbs
Breadcrumbs::for('sites.accounts.recovery.dashboard', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Accounts', route('sites.accounts.recovery.dashboard', ['site_id' => $site_id]));
    $trail->push('Dashboard', route('sites.accounts.recovery.dashboard', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.accounts.recovery.salesPlan', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Accounts', route('sites.accounts.recovery.salesPlan', ['site_id' => $site_id]));
    $trail->push('Recovery', route('sites.accounts.recovery.salesPlan', ['site_id' => $site_id]));
    $trail->push('Sales Plan', route('sites.accounts.recovery.salesPlan', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.accounts.recovery.calender', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Accounts', route('sites.accounts.recovery.calender', ['site_id' => $site_id]));
    $trail->push('Recovery', route('sites.accounts.recovery.calender', ['site_id' => $site_id]));
    $trail->push('Calendar', route('sites.accounts.recovery.calender', ['site_id' => $site_id]));
});
// journal Entries
Breadcrumbs::for('sites.accounts.journal-entry.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Journal Entries', route('sites.accounts.journal-entry.index', ['site_id' => $site_id]));
});
// ledgers Entries
Breadcrumbs::for('sites.accounts.ledger.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Ledgers', route('sites.accounts.ledger.index', ['site_id' => $site_id]));
});
// chart of accounts
Breadcrumbs::for('sites.accounts.charts-of-accounts.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Charts Of Accounts', route('sites.accounts.charts-of-accounts.index', ['site_id' => $site_id]));
});
//Trial Balance
Breadcrumbs::for('sites.accounts.trial-balance.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Trial Balance', route('sites.accounts.trial-balance.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.banks.import', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.receipts.index', $site_id);
    $trail->push('Import Banks');
});
// accounts first level
Breadcrumbs::for('sites.settings.accounts.first-level.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.first-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.first-level.index', ['site_id' => $site_id]));
    $trail->push('1st Level Account', route('sites.settings.accounts.first-level.index', ['site_id' => $site_id]));
});
Breadcrumbs::for('sites.settings.accounts.first-level.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.first-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.first-level.index', ['site_id' => $site_id]));
    $trail->push('Create 1st Level Account', route('sites.settings.accounts.first-level.create', ['site_id' => $site_id]));
});
// accounts second level
Breadcrumbs::for('sites.settings.accounts.second-level.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.second-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.second-level.index', ['site_id' => $site_id]));
    $trail->push('2nd Level Account', route('sites.settings.accounts.second-level.index', ['site_id' => $site_id]));
});
Breadcrumbs::for('sites.settings.accounts.second-level.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.second-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.second-level.index', ['site_id' => $site_id]));
    $trail->push('Create 2nd Level Account', route('sites.settings.accounts.second-level.create', ['site_id' => $site_id]));
});
// accounts third level
Breadcrumbs::for('sites.settings.accounts.third-level.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.third-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.third-level.index', ['site_id' => $site_id]));
    $trail->push('3rd Level Account', route('sites.settings.accounts.third-level.index', ['site_id' => $site_id]));
});
Breadcrumbs::for('sites.settings.accounts.third-level.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.third-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.third-level.index', ['site_id' => $site_id]));
    $trail->push('Create 3rd Level Account', route('sites.settings.accounts.third-level.create', ['site_id' => $site_id]));
});
// accounts fourth level
Breadcrumbs::for('sites.settings.accounts.fourth-level.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.fourth-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.fourth-level.index', ['site_id' => $site_id]));
    $trail->push('4th Level Account', route('sites.settings.accounts.fourth-level.index', ['site_id' => $site_id]));
});
Breadcrumbs::for('sites.settings.accounts.fourth-level.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.fourth-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.fourth-level.index', ['site_id' => $site_id]));
    $trail->push('Create 4th Level Account', route('sites.settings.accounts.fourth-level.create', ['site_id' => $site_id]));
});
// accounts fifth level
Breadcrumbs::for('sites.settings.accounts.fifth-level.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.fifth-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.fifth-level.index', ['site_id' => $site_id]));
    $trail->push('5th Level Account', route('sites.settings.accounts.fifth-level.index', ['site_id' => $site_id]));
});
Breadcrumbs::for('sites.settings.accounts.fifth-level.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.accounts.fifth-level.index', ['site_id' => $site_id]));
    $trail->push('Accounts', route('sites.settings.accounts.fifth-level.index', ['site_id' => $site_id]));
    $trail->push('Create 5th Level Account', route('sites.settings.accounts.fifth-level.create', ['site_id' => $site_id]));
});
//Images Import
Breadcrumbs::for('sites.settings.import', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Import', route('sites.index'));
});
// BlackListed Stakeholders
Breadcrumbs::for('sites.blacklisted-stakeholders.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Blacklisted Stakeholders', route('sites.blacklisted-stakeholders.index', ['site_id' => $site_id]));
});

//Countries Breadcrumbs
Breadcrumbs::for('sites.countries.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Countries', route('sites.settings.countries.index', ['site_id' => $site_id]));
});

//Countries Breadcrumbs
Breadcrumbs::for('sites.states.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('States', route('sites.settings.states.index', ['site_id' => $site_id]));
});

//Countries Breadcrumbs
Breadcrumbs::for('sites.cities.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Cities', route('sites.settings.cities.index', ['site_id' => $site_id]));
});

//Activity Logs Breadcrumbs
Breadcrumbs::for('sites.settings.activity-logs.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('sites.settings.activity-logs.index', ['site_id' => $site_id]));
    $trail->push('Activity Logs', route('sites.settings.activity-logs.index', ['site_id' => $site_id]));
});

//payment-voucher Breadcrumbs Main
Breadcrumbs::for('sites.payment-voucher.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Payment Voucher', route('sites.payment-voucher.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.payment-voucher.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.payment-voucher.index', $site_id);
    $trail->push('Create Payment Voucher');
});

Breadcrumbs::for('sites.payment-voucher.show', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.payment-voucher.index', $site_id);
    $trail->push(' Payment Voucher Details');
});
