<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <span class="brand-logo">
                        {{-- <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                            <defs>
                                <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%"
                                    y2="89.4879456%">
                                    <stop stop-color="#000000" offset="0%"></stop>
                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                </lineargradient>
                                <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%"
                                    y2="100%">
                                    <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                </lineargradient>
                            </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                    <g id="Group" transform="translate(400.000000, 178.000000)">
                                        <path class="text-primary" id="Path"
                                            d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                            style="fill:currentColor"></path>
                                        <path id="Path1"
                                            d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                            fill="url(#linearGradient-1)" opacity="0.2"></path>
                                        <polygon id="Path-2" fill="#000000" opacity="0.049999997"
                                            points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325">
                                        </polygon>
                                        <polygon id="Path-21" fill="#000000" opacity="0.099999994"
                                            points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338">
                                        </polygon>
                                        <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994"
                                            points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288">
                                        </polygon>
                                    </g>
                                </g>
                            </g>
                        </svg> --}}
                    </span>
                    <h2 class="brand-text">{{ env('APP_NAME') }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"
                        data-ticon="disc">
                    </i>
                </a>
            </li>
        </ul>
    </div>

    <div class="shadow-bottom"></div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : null }}">
                <a class="d-flex align-items-center" href="{{ route('dashboard') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">{{ __('lang.leftbar.dashboard') }}</span>
                </a>
            </li>

            @if (Auth::user()->can('permissions.index') ||
                Auth::user()->can('roles.index') ||
                Auth::user()->can('sites.configurations.configView'))
                <li class="navigation-header">
                    <span
                        data-i18n="{{ __('lang.leftbar.administration') }}">{{ __('lang.leftbar.administration') }}</span>
                    <i data-feather="more-horizontal"></i>
                </li>
            @endif

            {{-- Roles & Permission Menu --}}
            {{-- <li class="nav-item ">
                <a class="d-flex align-items-center" href="javascript:void(0)">
                    <i data-feather='shield'></i>
                    <span class="menu-title text-truncate" data-i18n="{{ __('lang.leftbar.roles_and_permissions') }}">{{
                        __('lang.leftbar.roles_and_permissions') }}</span>
                </a>
                <ul class="menu-content">
                    <li class="">
                        <a class="d-flex align-items-center" href="{{ route('roles.index') }}">
                            <i data-feather='shield'></i>
                            <span class="menu-item text-truncate" data-i18n="{{ __('lang.leftbar.roles') }}">{{
                                __('lang.leftbar.roles') }}</span>
                        </a>
                        <ul class="menu-content">
                            <li class="{{ request()->routeIs('roles.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center" href="{{ route('roles.index') }}">
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ __('lang.commons.view_all') }}">{{ __('lang.commons.view_all')
                                        }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('roles.create') ? 'active' : null }}">
                                <a class="d-flex align-items-center" href="{{ route('roles.create') }}">
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ __('lang.commons.add_new') }}">{{ __('lang.commons.add_new')
                                        }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a class="d-flex align-items-center" href="{{ route('permissions.index') }}">
                            <i data-feather='shield'></i>
                            <span class="menu-item text-truncate" data-i18n="{{ __('lang.leftbar.permissions') }}">{{
                                __('lang.leftbar.permissions') }}</span>
                        </a>
                        <ul class="menu-content">
                            <li class="{{ request()->routeIs('permissions.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center" href="{{ route('permissions.index') }}">
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ __('lang.commons.view_all') }}">{{ __('lang.commons.view_all')
                                        }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('permissions.create') ? 'active' : null }}">
                                <a class="d-flex align-items-center" href="{{ route('permissions.create') }}">
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ __('lang.commons.add_new') }}">{{ __('lang.commons.add_new')
                                        }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li> --}}

            @if (Auth::user()->can('permissions.index') || Auth::user()->can('roles.index'))
                <li class="nav-item ">
                    <a class="d-flex align-items-center" href="javascript:void(0)">
                        <i data-feather='shield'></i>
                        <span class="menu-title text-truncate"
                            data-i18n="{{ __('lang.leftbar.roles_and_permissions') }}">{{ __('lang.leftbar.roles_and_permissions') }}</span>
                    </a>
                    <ul class="menu-content">
                        @can('roles.index')
                            <li class="nav-item {{ request()->routeIs('roles.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center" href="{{ route('roles.index') }}">
                                    <i data-feather='shield'></i>
                                    <span class="menu-title text-truncate"
                                        data-i18n="Email">{{ __('lang.leftbar.roles') }}</span>
                                </a>
                            </li>
                        @endcan

                        @can('permissions.index')
                            <li class="nav-item {{ request()->routeIs('permissions.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center" href="{{ route('permissions.index') }}">
                                    <i data-feather='shield'></i>
                                    <span class="menu-title text-truncate"
                                        data-i18n="Email">{{ __('lang.leftbar.permissions') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            {{-- <li class="navigation-header">
                <span data-i18n="Others">Configurations</span>
                <i data-feather="more-horizontal"></i>
            </li> --}}

            @can('sites.configurations.configView')
                <li
                    class="nav-item {{ request()->routeIs('sites.configurations.configView', ['id' => encryptParams($site_id)]) ? 'active' : null }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('sites.configurations.configView', ['id' => encryptParams($site_id)]) }}">
                        <i data-feather='settings'></i>
                        <span class="menu-title text-truncate" data-i18n="Email">Site Configurations</span>
                    </a>
                </li>
            @endcan

            @canany(['sites.types.index', 'sites.additional-costs.index', 'sites.floors.index'])
                <li class="navigation-header">
                    <span data-i18n="Others">Others</span>
                    <i data-feather="more-horizontal"></i>
                </li>
            @endcanany

            @if (Auth::user()->can('sites.stakeholders.index') || Auth::user()->can('sites.users.index'))
                <li class="nav-item ">
                    <a class="d-flex align-items-center" href="javascript:void(0)">
                        <i data-feather='users'></i>
                        <span class="menu-title text-truncate"
                            data-i18n="{{ __('lang.leftbar.roles_and_permissions') }}">
                            Users</span>
                    </a>
                    <ul class="menu-content">
                        @can('sites.stakeholders.index')
                            <li class="nav-item {{ request()->routeIs('sites.stakeholders.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.stakeholders.index', ['site_id' => encryptParams(1)]) }}">
                                    <i data-feather='users'></i>
                                    <span class="menu-title text-truncate" data-i18n="Email">Stakeholders</span>
                                </a>
                            </li>
                        @endcan

                        @can('sites.users.index')
                            <li class="nav-item {{ request()->routeIs('sites.users.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.users.index', ['site_id' => encryptParams(1)]) }}">
                                    <i data-feather='users'></i>
                                    <span class="menu-title text-truncate" data-i18n="Email">Internal Users</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif


            @can('sites.types.index')
                <li class="nav-item {{ request()->routeIs('sites.types.index') ? 'active' : null }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('sites.types.index', ['site_id' => encryptParams($site_id)]) }}">
                        <i data-feather='menu'></i>
                        <span class="menu-title text-truncate" data-i18n="Email">Types</span>
                    </a>
                </li>
            @endcan

            {{-- Additional Costs Menu --}}
            @can('sites.additional-costs.index')
                <li
                    class="nav-item {{ request()->routeIs('sites.additional-costs.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('sites.additional-costs.index', ['site_id' => encryptParams($site_id)]) }}">
                        <i data-feather='dollar-sign'></i>
                        <span class="menu-title text-truncate" data-i18n="Email">Additional Costs</span>
                    </a>
                </li>
            @endcan


            {{-- Floors Menu --}}
            @can('sites.floors.index')
                <li
                    class="nav-item {{ request()->routeIs('sites.floors.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('sites.floors.index', ['site_id' => encryptParams($site_id)]) }}">
                        <i data-feather='layers'></i>
                        <span class="menu-title text-truncate" data-i18n="Email">Floors</span>
                    </a>
                </li>
            @endcan

            {{-- Lead Sources Menu --}}
            @can('sites.lead-sources.index')
                <li
                    class="nav-item {{ request()->routeIs('sites.lead-sources.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)]) }}">
                        <i data-feather='trello'></i>
                        <span class="menu-title text-truncate" data-i18n="Email">Lead Sources</span>
                    </a>
                </li>
            @endcan

            {{-- Receipts Menu --}}
            {{-- @can('sites.receipts.index')
                <li
                    class="nav-item {{ request()->routeIs('sites.receipts.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('sites.receipts.index', ['site_id' => encryptParams($site_id)]) }}">
                        <i class="bi bi-receipt-cutoff" style="
                            margin-bottom: 10px;">
                        </i>
                        <span class="menu-title text-truncate" data-i18n="Email">Receipts</span>
                    </a>
                </li>
            @endcan --}}

            {{-- File Management Menu --}}
            @can('sites.file-managements.customers')
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                        <span class="menu-title text-truncate" data-i18n="file-managements">File Management</span>
                    </a>
                    <ul>
                        @can('sites.receipts.index')
                            <li>
                                <a class="d-flex align-items-center" href="#">
                                    <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                    <span class="menu-title text-truncate" data-i18n="file-managements">Step 1</span>
                                </a>
                                <ul>
                                    <li
                                        class="nav-item {{ request()->routeIs('sites.receipts.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('sites.receipts.index', ['site_id' => encryptParams($site_id)]) }}">
                                            <i class="bi bi-receipt-cutoff"
                                                style="
                                    margin-bottom: 10px;">
                                            </i>
                                            <span class="menu-title text-truncate" data-i18n="Email">Receipts</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endcan
                        <li>
                            <a class="d-flex align-items-center" href="#">
                                <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                <span class="menu-title text-truncate" data-i18n="file-managements">Step 2</span>
                            </a>
                            <ul>
                                @can('sites.file-managements.customers')
                                    <li
                                        class="nav-item {{ request()->routeIs('sites.file-managements.customers', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('sites.file-managements.customers', ['site_id' => encryptParams($site_id)]) }}">
                                            <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                                            <span class="menu-title text-truncate" data-i18n="file-managements">File Creation
                                            </span>
                                        </a>
                                    </li>
                                @endcan

                                <li
                                    class="nav-item {{ request()->routeIs('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)]) }}">
                                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                                        <span class="menu-title text-truncate" data-i18n="file-managements">Rebate Incentive Form
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ request()->routeIs('sites.file-managements.dealer-incentive.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                    <a class="d-flex align-items-center"
                                        href="#">
                                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                                        <span class="menu-title text-truncate" data-i18n="file-managements">Dealer Incentive Form
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a class="d-flex align-items-center" href="#">
                                <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                <span class="menu-title text-truncate" data-i18n="file-managements">Step 3</span>
                            </a>
                            <ul>
                                {{-- @can('sites.receipts.index')
                                    <li
                                        class="nav-item {{ request()->routeIs('sites.receipts.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('sites.receipts.index', ['site_id' => encryptParams($site_id)]) }}">
                                            <i class="bi bi-receipt-cutoff"
                                                style="
                                    margin-bottom: 10px;">
                                            </i>
                                            <span class="menu-title text-truncate" data-i18n="Email">Receipts</span>
                                        </a>
                                    </li>
                                @endcan --}}
                            </ul>
                        </li>

                    </ul>
                </li>
            @endcan



        </ul>
    </div>
</div>
<!-- END: Main Menu-->
