<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <span class="brand-logo">
                        <img class="img-fluid custom_side_full_logo" src="{{ asset('app-assets') }}//images/logo/j7 logo_s.png" />
                    </span>
                    {{-- <h2 class="brand-text">{{ env('APP_NAME') }}</h2> --}}
                    <img class="img-fluid custom_side_icon" src="{{ asset('app-assets') }}/images/logo/logo icon.png" />
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
                        <i class="fa-solid fa-gears"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">Site Configurations</span>
                    </a>
                </li>
            @endcan

            {{-- Setting Menu --}}
            @canany(['sites.settings.custom-fields.index', 'sites.settings.activity-logs.index',
                'sites.settings.accounts.first-level.index', 'sites.settings.accounts.second-level.index',
                'sites.settings.accounts.third-level.index', 'sites.settings.accounts.fourth-level.index',
                'sites.settings.accounts.fifth-level.index', 'sites.settings.import',
                'sites.stakeholders.importStakeholders', 'sites.stakeholders.kins.importStakeholders',
                'sites.stakeholders.kins.importStakeholders', 'sites.floors.importFloors', 'sites.types.importTypes',
                'sites.additional-costs.importAdcosts', 'sites.floors.unitsImport.importUnits', 'sites.banks.importBanks',
                'sites.settings.import.images.index', 'sites.settings.countries.index', 'sites.settings.states.index',
                'sites.settings.cities.index'])
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="javascript:void(0)">
                        <i data-feather='settings'></i>
                        <span class="menu-title text-truncate" data-i18n="Settings">Settings</span>
                    </a>
                    <ul class="menu-context">
                        @can('sites.settings.activity-logs.index')
                            <li
                                class="nav-item {{ request()->routeIs('sites.settings.activity-logs.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.settings.activity-logs.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i class="bi bi-clock-history"></i>
                                    <span class="menu-title text-truncate" data-i18n="Activity Logs">Activity Logs</span>
                                </a>
                            </li>
                        @endcan
                        @can('sites.settings.custom-fields.index')
                            <li
                                class="nav-item {{ request()->routeIs('sites.settings.custom-fields.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='list'></i>
                                    <span class="menu-title text-truncate" data-i18n="Custom Fields">Custom Fields</span>
                                </a>
                            </li>
                        @endcan
                        {{-- @can('sites.settings.companies.index')
                            <li
                                class="nav-item {{ request()->routeIs('sites.settings.companies.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.settings.companies.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i class="bi bi-building-fill-gear"></i>
                                    <span class="menu-title text-truncate" data-i18n="Companies">Companies</span>
                                </a>
                            </li>
                        @endcan --}}
                        <li class="nav-item ">
                            <a class="d-flex align-items-center" href="javascript:void(0)">
                                <i data-feather='list'></i>
                                <span class="menu-title text-truncate"
                                    data-i18n="{{ __('lang.leftbar.roles_and_permissions') }}">
                                    Accounts</span>
                            </a>
                            <ul class="menu-content">

                                @can('sites.settings.accounts.first-level.index')
                                    <li
                                        class="nav-item {{ request()->routeIs('sites.settings.accounts.first-level.index') ? 'active' : null }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('sites.settings.accounts.first-level.index', ['site_id' => encryptParams($site_id)]) }}">
                                            <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                            <span class="menu-title text-truncate" data-i18n="Email">1st Level</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('sites.settings.accounts.second-level.index')
                                    <li
                                        class="nav-item {{ request()->routeIs('sites.settings.accounts.second-level.index') ? 'active' : null }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('sites.settings.accounts.second-level.index', ['site_id' => encryptParams($site_id)]) }}">
                                            <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                            <span class="menu-title text-truncate" data-i18n="Email">2nd Level</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('sites.settings.accounts.third-level.index')
                                    <li
                                        class="nav-item {{ request()->routeIs('sites.settings.accounts.third-level.index') ? 'active' : null }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('sites.settings.accounts.third-level.index', ['site_id' => encryptParams($site_id)]) }}">
                                            <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                            <span class="menu-title text-truncate" data-i18n="Email">3rd Level</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('sites.settings.accounts.fourth-level.index')
                                    <li
                                        class="nav-item {{ request()->routeIs('sites.settings.accounts.fourth-level.index') ? 'active' : null }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('sites.settings.accounts.fourth-level.index', ['site_id' => encryptParams($site_id)]) }}">
                                            <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                            <span class="menu-title text-truncate" data-i18n="Email">4th Level</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('sites.settings.accounts.fifth-level.index')
                                    <li
                                        class="nav-item {{ request()->routeIs('sites.settings.accounts.fifth-level.index') ? 'active' : null }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('sites.settings.accounts.fifth-level.index', ['site_id' => encryptParams($site_id)]) }}">
                                            <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                            <span class="menu-title text-truncate" data-i18n="Email">5th Level</span>
                                        </a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    </ul>
                    <ul class="menu-content">
                        @can('sites.settings.import')
                            <li class="nav-item ">
                                <a class="d-flex align-items-center" href="javascript:void(0)">
                                    <i data-feather='cloud'></i>
                                    <span class="menu-title text-truncate"
                                        data-i18n="{{ __('lang.leftbar.roles_and_permissions') }}">
                                        Import</span>
                                </a>
                                <ul class="menu-content">

                                    @can('sites.stakeholders.importStakeholders')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.stakeholders.importStakeholders') || request()->routeIs('sites.stakeholders.storePreview') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.stakeholders.importStakeholders', ['site_id' => encryptParams($site_id)]) }}">
                                                <i data-feather='users'></i>
                                                <span class="menu-title text-truncate" data-i18n="Users">Stakeholders</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.stakeholders.kins.importStakeholders')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.stakeholders.kins.importStakeholders') || request()->routeIs('sites.stakeholders.kins.storePreview') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.stakeholders.kins.importStakeholders', ['site_id' => encryptParams($site_id)]) }}">
                                                <i data-feather='user'></i>
                                                <span class="menu-title text-truncate" data-i18n="Users">Stakeholders Kins</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.floors.importFloors')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.floors.importFloors') || request()->routeIs('sites.floors.storePreview') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.floors.importFloors', ['site_id' => encryptParams($site_id)]) }}">
                                                <i data-feather='layers'></i>
                                                <span class="menu-title text-truncate" data-i18n="Floors">Floors</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.types.importTypes')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.types.importTypes') || request()->routeIs('sites.types.storePreview') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.types.importTypes', ['site_id' => encryptParams($site_id)]) }}">
                                                <i data-feather='menu'></i>
                                                <span class="menu-title text-truncate" data-i18n="Types">Types</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.additional-costs.importAdcosts')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.additional-costs.importAdcosts') || request()->routeIs('sites.additional-costs.storePreview') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.additional-costs.importAdcosts', ['site_id' => encryptParams($site_id)]) }}">
                                                <i data-feather='dollar-sign'></i>
                                                <span class="menu-title text-truncate" data-i18n="Types">Additional Costs</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.floors.unitsImport.importUnits')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.floors.unitsImport.importUnits') || request()->routeIs('sites.floors.unitsImport.storePreview') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.floors.unitsImport.importUnits', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-shop-window"></i>
                                                <span class="menu-title text-truncate" data-i18n="Types">Units</span>
                                            </a>
                                        </li>
                                    @endcan
                                    {{-- @can('sites.floors.SalesPlanImport.importSalesPlan')
                                        <li class="nav-item ">
                                            <a class="d-flex align-items-center" href="javascript:void(0)">

                                                <span class="menu-title text-truncate">
                                                    Sales Plan</span>
                                            </a>
                                            <ul class="menu-content">
                                                <li
                                                    class="nav-item {{ request()->routeIs('sites.floors.SalesPlanImport.importSalesPlan') || request()->routeIs('sites.floors.SalesPlanImport.storePreview') ? 'active' : null }}">
                                                    <a class="d-flex align-items-center"
                                                        href="{{ route('sites.floors.SalesPlanImport.importSalesPlan', ['site_id' => encryptParams($site_id)]) }}">
                                                        <i class="bi bi-calculator"></i>
                                                        <span class="menu-title text-truncate" data-i18n="Types">Sales Plan</span>
                                                    </a>
                                                </li>
                                    @endcan
                                    @can('sites.floors.spadcostsImport.importspadcosts')
                                                <li
                                                    class="nav-item {{ request()->routeIs('sites.floors.spadcostsImport.importspadcosts') || request()->routeIs('sites.floors.spadcostsImport.storePreview') ? 'active' : null }}">
                                                    <a class="d-flex align-items-center"
                                                        href="{{ route('sites.floors.spadcostsImport.importspadcosts', ['site_id' => encryptParams($site_id)]) }}">
                                                        <i class="bi bi-currency-dollar"></i>
                                                        <span class="menu-title" data-i18n="Types">Sales Plan Additional
                                                            Costs</span>
                                                    </a>
                                                </li>
                                    @endcan
                                    @can('sites.floors.spInstallmentsImport.ImportInstallments')
                                                <li
                                                    class="nav-item {{ request()->routeIs('sites.floors.spInstallmentsImport.ImportInstallments') || request()->routeIs('sites.floors.spInstallmentsImport.storePreviewInstallments') ? 'active' : null }}">
                                                    <a class="d-flex align-items-center"
                                                        href="{{ route('sites.floors.spInstallmentsImport.ImportInstallments', ['site_id' => encryptParams($site_id)]) }}">
                                                        <i class="bi bi-calendar-date"></i>
                                                        <span class="menu-title" data-i18n="Types">Sales Plan Installments
                                                        </span>
                                                    </a>
                                                </li>
                                    @endcan
                                            </ul>
                                        </li> --}}
                                    @can('sites.banks.importBanks')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.banks.importBanks') || request()->routeIs('sites.banks.storePreview') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.banks.importBanks', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-bank2"></i>
                                                <span class="menu-title" data-i18n="Types">Banks
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.settings.import.images.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.settings.import.images.index') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.settings.import.images.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-images"></i>
                                                <span class="menu-title text-truncate" data-i18n="Users">Images</span>
                                            </a>
                                        </li>
                                    @endcan
                                    {{-- @can('sites.receipts.importReceipts')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.receipts.importReceipts') || request()->routeIs('sites.receipts.storePreview') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.receipts.importReceipts', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-wallet2"></i>
                                                <span class="menu-title" data-i18n="Types">Receipts
                                                </span>
                                            </a>
                                        </li>
                                    @endcan --}}
                                </ul>
                            </li>
                        @endcan
                        @if (Auth::user()->can('sites.settings.countries.index'))
                            <li class="nav-item ">
                                <a class="d-flex align-items-center" href="javascript:void(0)">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span class="menu-title text-truncate"
                                        data-i18n="{{ __('lang.leftbar.roles_and_permissions') }}">Locations</span>
                                </a>
                                <ul class="menu-content">
                                    @can('sites.settings.countries.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.settings.countries.index') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.settings.countries.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i data-feather='flag'></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Countries</span>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('sites.settings.states.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.settings.states.index') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.settings.states.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-geo"></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">States</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.settings.cities.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.settings.cities.index') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.settings.cities.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-map-fill"></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Cities</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif



                        {{-- @if (Auth::user()->can('sites.settings.countries.index'))
                            <li class="nav-item ">
                                <a class="d-flex align-items-center" href="javascript:void(0)">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    <span class="menu-title text-truncate"
                                        data-i18n="{{ __('lang.leftbar.roles_and_permissions') }}">Recycle Bin</span>
                                </a>
                                <ul class="menu-content">
                                    @can('sites.settings.bin.type')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.settings.bin.type') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.settings.bin.type', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="fa-sharp fa-solid fa-bars"></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Types</span>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('sites.bin.states.unit')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.bin.states.unit') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.bin.states.unit', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="fa-sharp fa-solid fa-door-open"></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Units</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.bin.cities.additonalcosts')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.bin.cities.additonalcosts') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.bin.cities.additonalcosts', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="fa-solid fa-dollar-sign"></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Additional Costs</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif --}}
                    </ul>
                </li>
            @endcanany

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
                            Stakeholders</span>
                    </a>
                    <ul class="menu-content">
                        @can('sites.blacklisted-stakeholders.index')
                            <li
                                class="nav-item {{ request()->routeIs('sites.blacklisted-stakeholders.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='users'></i>
                                    <span class="menu-title text-truncate" data-i18n="Email">Blacklisted
                                    </span>
                                </a>
                            </li>
                        @endcan

                        @can('sites.stakeholders.index')
                            <li class="nav-item {{ request()->routeIs('sites.stakeholders.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='users'></i>
                                    <span class="menu-title text-truncate" data-i18n="Email">External </span>
                                </a>
                            </li>
                        @endcan

                        @if (Auth::user()->can('sites.teams.index') || Auth::user()->can('sites.users.index'))
                            <li class="nav-item ">
                                <a class="d-flex align-items-center" href="javascript:void(0)">
                                    <i data-feather='users'></i>
                                    <span class="menu-title text-truncate"
                                        data-i18n="{{ __('lang.leftbar.roles_and_permissions') }}">
                                        Internal </span>
                                </a>
                                <ul class="menu-content">

                                    @can('sites.users.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.users.index') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.users.index', ['site_id' => encryptParams(1)]) }}">
                                                <i data-feather='users'></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Users</span>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('sites.teams.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.teams.index') ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.teams.index', ['site_id' => encryptParams(1)]) }}">
                                                <i data-feather='users'></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Teams</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif


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

            {{-- Sales Plan  --}}
            @canany(['sites.floors.units.sales-plans.index', 'sites.sales_plan.create'])
                <li
                    class="nav-item {{ request()->routeIs('sites.floors.units.sales-plans.index') || request()->routeIs('sites.sales_plan.create') || request()->routeIs('sites.sales_plan.generateSalesPlan') ? 'active' : null }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams(0), 'unit_id' => encryptParams(0)]) }}">
                        <i class="bi bi-clipboard-data"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">Sales Plan</span>
                    </a>
                </li>
            @endcan


            {{-- File Management Menu --}}
            @canany(['sites.file-managements.view-files', 'sites.receipts.index', 'sites.payment-voucher.index',
                'sites.file-managements.customers', 'sites.file-managements.rebate-incentive.index',
                'sites.file-managements.dealer-incentive.index', 'sites.file-managements.file-refund.index',
                'sites.file-managements.file-buy-back.index', 'sites.file-managements.file-cancellation.index',
                'sites.file-managements.file-resale.index', 'sites.file-managements.file-title-transfer.index'])
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                        <span class="menu-title text-truncate" data-i18n="file-managements">File Management</span>
                    </a>
                    <ul>
                        @can('sites.file-managements.view-files')
                            <li
                                class="nav-item {{ request()->routeIs('sites.file-managements.view-files', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.file-managements.view-files', ['site_id' => encryptParams($site_id)]) }}">
                                    <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                                    <span class="menu-title text-truncate" data-i18n="file-managements">View Customer Files
                                    </span>
                                </a>
                            </li>
                        @endcan
                        @canany(['sites.receipts.index', 'sites.payment-voucher.index'])
                            <li>
                                <a class="d-flex align-items-center" href="#">
                                    <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                    <span class="menu-title text-truncate" data-i18n="file-managements">Step 1</span>
                                </a>
                                <ul>
                                    @can('sites.receipts.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.receipts.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.receipts.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-receipt-cutoff" style="margin-bottom: 10px;">
                                                </i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Receipts</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.file-transfer-receipts.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.file-transfer-receipts.index', ['site_id' => encryptParams($site_id)]) || request()->routeIs('sites.file-transfer-receipts.create', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.file-transfer-receipts.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-receipt-cutoff" style="margin-bottom: 10px;">
                                                </i>
                                                <span class="menu-title text-truncate" data-i18n="Email">File Transfer
                                                    Receipts</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.payment-voucher.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.payment-voucher.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.payment-voucher.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-receipt-cutoff" style="margin-bottom: 10px;"></i>
                                                <span class="menu-title text-truncate" data-i18n="Email">Payment Voucher</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        @canany(['sites.file-managements.customers', 'sites.file-managements.rebate-incentive.index',
                            'sites.file-managements.dealer-incentive.index'])
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

                                    @can('sites.file-managements.rebate-incentive.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                                                <span class="menu-title text-truncate" data-i18n="file-managements">Rebate
                                                    Incentive Form
                                                </span>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('sites.file-managements.dealer-incentive.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.file-managements.dealer-incentive.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.file-managements.dealer-incentive.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-folder2" style="margin-bottom: 10px;"></i>
                                                <span class="menu-title text-truncate" data-i18n="file-managements">Dealer
                                                    Incentive Form
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['sites.file-managements.file-refund.index', 'sites.file-managements.file-buy-back.index',
                            'sites.file-managements.file-cancellation.index', 'sites.file-managements.file-resale.index',
                            'sites.file-managements.file-title-transfer.index'])
                            <li>
                                <a class="d-flex align-items-center" href="#">
                                    <i class="bi bi-bar-chart-steps" style="margin-bottom: 10px;"></i>
                                    <span class="menu-title text-truncate" data-i18n="file-managements">Step 3</span>
                                </a>
                                <ul>
                                    @can('sites.file-managements.file-refund.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.file-managements.file-refund.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.file-managements.file-refund.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-folder-symlink-fill">
                                                </i>
                                                <span class="menu-title text-truncate" data-i18n="Email">File Refund</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.file-managements.file-buy-back.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.file-managements.file-buy-back.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.file-managements.file-buy-back.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-folder-symlink-fill">
                                                </i>
                                                <span class="menu-title text-truncate" data-i18n="Email">File Buy Back</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.file-managements.file-cancellation.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.file-managements.file-cancellation.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.file-managements.file-cancellation.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-folder-symlink-fill">
                                                </i>
                                                <span class="menu-title text-truncate" data-i18n="Email">File Cancellation</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.file-managements.file-resale.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.file-managements.file-resale.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.file-managements.file-resale.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-folder-symlink-fill">
                                                </i>
                                                <span class="menu-title text-truncate" data-i18n="Email">File Resale</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sites.file-managements.file-title-transfer.index')
                                        <li
                                            class="nav-item {{ request()->routeIs('sites.file-managements.file-title-transfer.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                            <a class="d-flex align-items-center"
                                                href="{{ route('sites.file-managements.file-title-transfer.index', ['site_id' => encryptParams($site_id)]) }}">
                                                <i class="bi bi-folder-symlink-fill">
                                                </i>
                                                <span class="menu-title text-truncate" data-i18n="Email">File Title Transfer
                                                    Request</span>
                                            </a>
                                        </li>
                                    @endcan
                                    {{-- <li
                                    class="nav-item {{ request()->routeIs('sites.file-managements.file-adjustment.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('sites.file-managements.file-adjustment.index', ['site_id' => encryptParams($site_id)]) }}">
                                        <i class="bi bi-folder-symlink-fill">
                                        </i>
                                        <span class="menu-title text-truncate" data-i18n="Email">File Adjustment
                                            Request</span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->routeIs('sites.file-managements.unit-shifting.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('sites.file-managements.unit-shifting.index', ['site_id' => encryptParams($site_id)]) }}">
                                        <i class="bi bi-folder-symlink-fill">
                                        </i>
                                        <span class="menu-title text-truncate" data-i18n="Email">Unit Shifting</span>
                                    </a>
                                </li> --}}
                                </ul>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany

            {{-- Accounts Menu --}}
            @canany(['sites.accounts.recovery.inventory-aging', 'sites.accounts.charts-of-accounts.index',
                'sites.accounts.journal-entry.index', 'sites.accounts.trial-balance.index',
                'sites.settings.journal-vouchers.index'])
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='dollar-sign'></i>
                        <span class="menu-title text-truncate" data-i18n="Account">Accounts</span>
                    </a>
                    <ul>
                        @can('sites.accounts.charts-of-accounts.index')
                            <li
                                class="nav-item {{ request()->routeIs('sites.accounts.charts-of-accounts.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.accounts.charts-of-accounts.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='dollar-sign'></i>
                                    <span class="menu-title text-truncate" data-i18n="Email">Charts of Accounts</span>
                                </a>
                            </li>
                        @endcan
                        @can('sites.settings.journal-vouchers.index')
                            <li
                                class="nav-item {{ request()->routeIs('sites.settings.journal-vouchers.index') ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.settings.journal-vouchers.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='dollar-sign'></i>
                                    <span class="menu-title text-truncate" data-i18n="Journal Vouchers">Journal
                                        Vouchers</span>
                                </a>
                            </li>
                        @endcan
                        @can('sites.accounts.journal-entry.index')
                            <li
                                class="nav-item {{ request()->routeIs('sites.accounts.journal-entry.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.accounts.journal-entry.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='dollar-sign'></i>
                                    <span class="menu-title text-truncate" data-i18n="Email">Journal Entries</span>
                                </a>
                            </li>
                        @endcan
                        @can('sites.accounts.trial-balance.index')
                            <li
                                class="nav-item {{ request()->routeIs('sites.accounts.trial-balance.index', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.accounts.trial-balance.index', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='dollar-sign'></i>
                                    <span class="menu-title text-truncate" data-i18n="Email">Trial Balance</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            {{-- Account Recovery  --}}
            @canany(['sites.accounts.recovery.dashboard', 'sites.accounts.recovery.salesPlan',
                'sites.accounts.recovery.calender', 'sites.accounts.recovery.inventory-aging'])
                <li>
                    <a class="d-flex align-items-center" href="javascript:void(0);">
                        <i data-feather='dollar-sign'></i>
                        <span class="menu-title text-truncate" data-i18n="Recovery">Recovery</span>
                    </a>
                    <ul>
                        @can('sites.accounts.recovery.dashboard')
                            <li
                                class="nav-item {{ request()->routeIs('sites.accounts.recovery.dashboard', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.accounts.recovery.dashboard', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='layout'></i>
                                    <span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span>
                                </a>
                            </li>
                        @endcan
                        @can('sites.accounts.recovery.salesPlan')
                            <li
                                class="nav-item {{ request()->routeIs('sites.accounts.recovery.salesPlan', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.accounts.recovery.salesPlan', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='layout'></i>
                                    <span class="menu-title text-truncate" data-i18n="Sales Plans">Sales Plans</span>
                                </a>
                            </li>
                        @endcan
                        @can('sites.accounts.recovery.calender')
                            <li
                                class="nav-item {{ request()->routeIs('sites.accounts.recovery.calender', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.accounts.recovery.calender', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='calendar'></i>
                                    <span class="menu-title text-truncate" data-i18n="Calender">Calender</span>
                                </a>
                            </li>
                        @endcan
                        @can('sites.accounts.recovery.inventory-aging')
                            <li
                                class="nav-item {{ request()->routeIs('sites.accounts.recovery.inventory-aging', ['site_id' => encryptParams($site_id)]) ? 'active' : null }}">
                                <a class="d-flex align-items-center"
                                    href="{{ route('sites.accounts.recovery.inventory-aging', ['site_id' => encryptParams($site_id)]) }}">
                                    <i data-feather='calendar'></i>
                                    <span class="menu-title text-truncate" data-i18n="Calender"> Aging Report</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

        </ul>
    </div>
</div>
<!-- END: Main Menu-->
