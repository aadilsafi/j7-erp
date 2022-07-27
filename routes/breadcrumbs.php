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
Breadcrumbs::for('types.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Types', route('types.index'));
});

Breadcrumbs::for('types.create', function (BreadcrumbTrail $trail) {
    $trail->parent('types.index');
    $trail->push('Create Type');
});

Breadcrumbs::for('types.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('types.index');
    $trail->push('Edit Type');
});

//Additional Costs Breadcrumbs
Breadcrumbs::for('sites.additional-costs.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Additional Costs', route('sites.additional-costs.index', ['site_id' => encryptParams(1)]));
});

Breadcrumbs::for('sites.additional-costs.create', function (BreadcrumbTrail $trail) {
    $trail->parent('sites.additional-costs.index', ['site_id' => encryptParams(1)]);
    $trail->push('Create Additional Cost');
});

Breadcrumbs::for('sites.additional-costs.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('sites.additional-costs.index', ['site_id' => encryptParams(1)]);
    $trail->push('Edit Additional Cost');
});
