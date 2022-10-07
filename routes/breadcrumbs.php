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

//Floor Breadcrumbs
Breadcrumbs::for('sites.floors.index', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Floors', route('sites.floors.index', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.floors.create', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Create Floor');
});

Breadcrumbs::for('sites.floors.copy', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Copy Floor');
});

Breadcrumbs::for('sites.floors.edit', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('sites.floors.index', $site_id);
    $trail->push('Edit Floor');
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
Breadcrumbs::for('sites.floors.units.sales-plans.receipts.index', function (BreadcrumbTrail $trail, $site_id, $floor_id, $unit_id , $sales_plan_id ) {
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

//Recovery Accounts BreadCrumbs
Breadcrumbs::for('sites.accounts.recovery.dashboard', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('File Title Transfer', route('sites.accounts.recovery.dashboard', ['site_id' => $site_id]));
});

Breadcrumbs::for('sites.accounts.recovery.salesPlan', function (BreadcrumbTrail $trail, $site_id) {
    $trail->parent('dashboard');
    $trail->push('Sales Plan', route('sites.accounts.recovery.salesPlan', ['site_id' => $site_id]));
});
