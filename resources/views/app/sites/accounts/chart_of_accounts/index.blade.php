@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.accounts.charts-of-accounts.index', encryptParams($site->id)) }}
@endsection

@section('page-title', 'Charts Of Accounts')

@section('page-vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/pickers/flatpickr/flatpickr.min.css">

@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Charts Of Accounts</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.accounts.charts-of-accounts.index', encryptParams($site->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="app-user-view-connections">
        <div class="row removeInvalidMessages">
            <div class="col-xl-12 col-lg-12">
                {{-- @dd($account_of_heads[67]->accountLedgers) --}}
                {{-- <ul class="nav nav-pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link active" id="salesPlanTab" data-bs-toggle="tab" href="#salesPlanData"
                            aria-controls="sales-plan" role="tab" aria-selected="true">
                            <i class="bi bi-receipt font-medium-3 me-50"></i>
                            <span class="fw-bold">Sales Invoice</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="buyBackTab" data-bs-toggle="tab" href="#buybackData"
                            aria-controls="buy-back" role="tab" aria-selected="true">
                            <i data-feather="home" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Buy Back</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="refund" data-bs-toggle="tab" href="#refundData"
                            aria-controls="refund-data" role="tab" aria-selected="false">
                            <i data-feather="layers" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Refund</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="resale" data-bs-toggle="tab" href="#resaleData"
                            aria-controls="resale-data" role="tab" aria-selected="false">
                            <i class="bi bi-receipt font-medium-3 me-50"></i>
                            <span class="fw-bold">Resale</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="cancellation" data-bs-toggle="tab" href="#cancellationData"
                            aria-controls="cancellation-data" role="tab" aria-selected="false">
                            <i class="bi bi-door-open font-medium-3 me-50"></i>
                            <span class="fw-bold">Cancellation</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="title-transfer" data-bs-toggle="tab" href="#titleTransferData"
                            aria-controls="title-transfer" role="tab" aria-selected="false">
                            <i class="bi bi-receipt font-medium-3 me-50"></i>
                            <span class="fw-bold">Title Transfer</span></a>
                    </li>
                </ul> --}}
                <div class="tab-content">
                    <div class="tab-pane active" id="salesPlanData" aria-labelledby="salesPlanData" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                {{-- <section> --}}
                                <style>
                                    .custom_bg {
                                        margin-top: 0.5rem;
                                        /* background: whitesmoke; */
                                        margin-top: 0 !important;
                                        border: 1px solid #80808026;
                                        border-bottom: 0px solid white !important;
                                    }

                                    .main_multi_dop_ul {
                                        padding-bottom: 0 !important;
                                    }

                                    .custom_multi_drop_main {
                                        width: 100%;
                                        position: unset;
                                    }

                                    /* @media(min-width: 768px) { */
                                    .custom_multi_drop_main {
                                        width: 100% !important;
                                        max-width: 100%;
                                        display: block;
                                    }

                                    /* } */

                                    .vertical-overlay-menu .custom_multi_drop_main,
                                    .vertical-overlay-menu.menu-hide .custom_multi_drop_main .main-menu.menu-light .navigation>.custom_bg.open:not(.menu-item-closing)>a,
                                    .main-menu.menu-light .navigation>.custom_bg .sidebar-group-active>a {

                                        opacity: 1 !important;
                                    }

                                    .main-menu.menu-light .navigation>.Second_li>ul .Second_li.has-sub>a,
                                    .main-menu.menu-light .navigation>.Second_li>ul .Second_li.has-sub>ul>.Second_li,
                                    .main-menu.menu-light .navigation>.Second_li>ul .Second_li:not(.has-sub) {
                                        margin: 0 15px;
                                        margin: 0;
                                        border-radius: 0 !important;
                                    }


                                    .custom_td {
                                        border-radius: 0 !important;
                                    }

                                    .vertical-layout.vertical-menu-modern.menu-expanded .main-menu .navigation li.has-sub>a:before {
                                        /* right: 25px !important; */
                                    }

                                    .accordion-flush .accordion-item .accordion-button,
                                    .new_according_bg {
                                        background: whitesmoke !important;
                                    }

                                    .accordion-button::after {
                                        content: '';
                                        -webkit-transition: -webkit-transform .2s ease-in-out;
                                        transition: -webkit-transform .2s ease-in-out;
                                        transition: transform .2s ease-in-out;
                                        transition: transform .2s ease-in-out, -webkit-transform .2s ease-in-out;
                                        -webkit-transform: rotate(90deg);
                                        -ms-transform: rotate(90deg);
                                        transform: rotate(90deg);
                                    }

                                    .custom_plus_th {
                                        position: absolute !important;
                                        right: 0;
                                        cursor: pointer;
                                        padding-right: 1.1rem !important;
                                    }

                                    .accordion-body,
                                    table,
                                    thead {
                                        position: relative;
                                    }

                                    .accordion-flush .accordion-collapse {
                                        border-width: 0;
                                        background: whitesmoke;
                                    }
                                </style>
                                <!-- Right Sidebar starts -->
                                <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task-modal">
                                    <div class="modal-dialog sidebar-lg">
                                        <div class="modal-content p-0">
                                            <form id="form-modal-todo" class="todo-modal needs-validation" novalidate
                                                onsubmit="return false">
                                                <div class="modal-header align-items-center mb-1">
                                                    <h5 class="modal-title">Add Task</h5>
                                                    <div
                                                        class="todo-item-action d-flex align-items-center justify-content-between ms-auto">
                                                        <span class="todo-item-favorite cursor-pointer me-75"><i
                                                                data-feather="star" class="font-medium-2"></i></span>
                                                        <i data-feather="x" class="cursor-pointer" data-bs-dismiss="modal"
                                                            stroke-width="3"></i>
                                                    </div>
                                                </div>
                                                <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                    <div class="action-tags">
                                                        Create Account
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Right Sidebar ends -->






                                {{-- example according  --}}

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    @foreach ($account_of_heads->where('level', 1) as $key_first => $account_of_head)
                                    @php
                                    $value_44 = 0;
                                    $value_55 = 0;
                                    $value_frist = [];
                                    $value_frist_value = 0;
                                @endphp
                                        <div class="accordion-item ">
                                            <h2 class="accordion-header" id="flush-{{ $key_first }}">
                                                <button class="new_according_bg accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseOne-{{ $key_first }}"
                                                    aria-expanded="false" aria-controls="flush-collapseOne">
                                                    {{ $account_of_head->name }}
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne-{{ $key_first }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="flush-{{ $key_first }}"
                                                data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body ps-0 pt-0 pb-0 pe-0">
                                                    <table class="table table-primary table-striped check_hide">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">ACCOUNT LEVEl</th>
                                                                <th scope="col">ACCOUNT CODES</th>
                                                                <th scope="col">ACCOUNT NATURE</th>
                                                                <th scope="col">Balance</th>
                                                                <th class="custom_plus_th" scope="col">
                                                                    <i data-feather='plus' data-bs-toggle="modal"
                                                                        data-bs-target="#new-task-modal"></i>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="custom_td">{{ $account_of_head->name }}
                                                                </td>
                                                                <td class="custom_td">{{ $account_of_head->level }}
                                                                </td>
                                                                <td class="custom_td">
                                                                    {{ account_number_format($account_of_head->code) }}
                                                                </td>
                                                                <td class="custom_td">
                                                                    {{ ucfirst($account_of_head->account_type) }}
                                                                </td>
                                                                @foreach ($account_of_heads->where('level', 3) as $key_second => $account_of_head_3)
                                                                    @if (Str::length($account_of_head_3->code) == 6 and
                                                                        $account_of_head->code == substr($account_of_head_3->code, 0, 2))
                                                                        @foreach ($account_of_heads->where('level', 4) as $key_forth => $account_of_head_4)
                                                                            @if (Str::length($account_of_head_4->code) == 10 and
                                                                                $account_of_head_3->code == substr($account_of_head_4->code, 0, 6))
                                                                                @foreach ($account_of_heads->where('level', 5) as $key_fiveth => $account_of_head_5)
                                                                                    @if ($account_of_head_4->code == substr($account_of_head_5->code, 0, 10))
                                                                                        {{-- @dd($account_of_head_5->code); --}}
                                                                                        @php
                                                                                            $value_frist_value = 0;
                                                                                            $value_frist_value += intval(
                                                                                                trim(
                                                                                                    $accountLedgers_all
                                                                                                        ->where('account_head_code', $account_of_head_5->code)
                                                                                                        ->pluck('debit')
                                                                                                        ->sum() -
                                                                                                        $accountLedgers_all
                                                                                                            ->where('account_head_code', $account_of_head_5->code)
                                                                                                            ->pluck('credit')
                                                                                                            ->sum(),
                                                                                                    '-',
                                                                                                ),
                                                                                            );
                                                                                        @endphp
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach

                                                                <td class="custom_td">
                                                                    {{ number_format($value_frist_value) ?? 0 }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    {{-- secend level --}}
                                                    @foreach ($account_of_heads as $key => $account_of_head_full_array)
                                                        @if (Str::length($account_of_head_full_array->code) == 4 and
                                                            $account_of_heads[$key_first]->code == substr($account_of_head_full_array->code, 0, 2))
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header"
                                                                    id="flush-{{ $key }}">
                                                                    <button class="accordion-button collapsed ps-3"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#flush-collapseOne-{{ $key }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="flush-collapseOne">
                                                                        <i
                                                                            class="bi bi-arrow-bar-right"></i><span>{{ $account_of_head_full_array->name }}</span>
                                                                    </button>
                                                                </h2>
                                                                <div id="flush-collapseOne-{{ $key }}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="flush-{{ $key }}"
                                                                    data-bs-parent="#flush-collapseOne-{{ $key_first }}">
                                                                    <div class="accordion-body ps-4 pt-0 pb-0 pe-0">
                                                                        <table class="table table-primary table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th scope="col">Name</th>
                                                                                    <th scope="col">ACCOUNT LEVEl
                                                                                    </th>
                                                                                    <th scope="col">ACCOUNT CODES
                                                                                    </th>
                                                                                    <th scope="col">ACCOUNT NATURE
                                                                                    </th>
                                                                                    <th scope="col">Balance</th>
                                                                                    <th class="custom_plus_th"
                                                                                        scope="col">
                                                                                        <i data-feather='plus'
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#new-task-modal"></i>
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="custom_td">
                                                                                        {{ $account_of_head_full_array->name }}
                                                                                    </td>
                                                                                    <td>{{ $account_of_head_full_array->level }}
                                                                                    </td>
                                                                                    <td>{{ account_number_format($account_of_head_full_array->code) }}
                                                                                    </td>
                                                                                    <td class="custom_td">
                                                                                        {{ ucfirst($account_of_head_full_array->account_type) }}
                                                                                    </td>
                                                                                    @php
                                                                                        $value_44 = 0;
                                                                                    @endphp
                                                                                    @foreach ($account_of_heads->where('level', 3) as $key_second => $account_of_head_3)
                                                                                        @if (Str::length($account_of_head_3->code) == 6 and
                                                                                            $account_of_head_full_array->code == substr($account_of_head_3->code, 0, 4))
                                                                                            @foreach ($account_of_heads->where('level', 4) as $key_forth => $account_of_head_4)
                                                                                                @if (Str::length($account_of_head_4->code) == 10 and
                                                                                                    $account_of_head_3->code == substr($account_of_head_4->code, 0, 6))
                                                                                                    @foreach ($account_of_heads->where('level', 5) as $key_fiveth => $account_of_head_5)
                                                                                                        @if ($account_of_head_4->code == substr($account_of_head_5->code, 0, 10))
                                                                                                            {{-- @dd($account_of_head_5->code); --}}
                                                                                                            @php
                                                                                                                $value_44 += intval(
                                                                                                                    trim(
                                                                                                                        $accountLedgers_all
                                                                                                                            ->where('account_head_code', $account_of_head_5->code)
                                                                                                                            ->pluck('debit')
                                                                                                                            ->sum() -
                                                                                                                            $accountLedgers_all
                                                                                                                                ->where('account_head_code', $account_of_head_5->code)
                                                                                                                                ->pluck('credit')
                                                                                                                                ->sum(),
                                                                                                                        '-',
                                                                                                                    ),
                                                                                                                );
                                                                                                            @endphp
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif
                                                                                    @endforeach

                                                                                    <td class="custom_td">
                                                                                        {{ number_format($value_44) }}
                                                                                    </td>

                                                                                </tr>
                                                                            </tbody>
                                                                        </table>

                                                                        {{-- Third level --}}
                                                                        @foreach ($account_of_heads->where('level', 3) as $key_second => $account_of_head_3)
                                                                            @if (Str::length($account_of_head_3->code) == 6 and
                                                                                $account_of_head_full_array->code == substr($account_of_head_3->code, 0, 4))
                                                                                <div class="accordion-item">
                                                                                    <h2 class="accordion-header"
                                                                                        id="flush-{{ $key_second }}">
                                                                                        <button
                                                                                            class="accordion-button collapsed ps-3"
                                                                                            type="button"
                                                                                            data-bs-toggle="collapse"
                                                                                            data-bs-target="#flush-collapseOne-{{ $key_second }}"
                                                                                            aria-expanded="false"
                                                                                            aria-controls="flush-collapseOne">
                                                                                            <i
                                                                                                class="bi bi-arrow-bar-right ms-2"></i><span>
                                                                                                {{ $account_of_head_3->name }}</span>
                                                                                        </button>
                                                                                    </h2>
                                                                                    <div id="flush-collapseOne-{{ $key_second }}"
                                                                                        class="accordion-collapse collapse"
                                                                                        aria-labelledby="flush-{{ $key_second }}"
                                                                                        data-bs-parent="#flush-collapseOne-{{ $key }}">
                                                                                        <div
                                                                                            class="accordion-body ps-5 pt-0 pb-0 pe-0">
                                                                                            @php
                                                                                                $value_4 = 0;
                                                                                                $value_5 = 0;
                                                                                                $value_33 = 0;
                                                                                            @endphp

                                                                                            <table
                                                                                                class="table table-primary table-striped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">
                                                                                                            Name</th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT LEVEl
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT CODES
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT NATURE
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            Balance</th>
                                                                                                        <th class="custom_plus_th"
                                                                                                            scope="col">
                                                                                                            <i data-feather='plus'
                                                                                                                data-bs-toggle="modal"
                                                                                                                data-bs-target="#new-task-modal"></i>
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ $account_of_head_3->name }}
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ $account_of_head_3->level }}
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ account_number_format($account_of_head_3->code) }}
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ ucfirst($account_of_head_3->account_type) }}
                                                                                                        </td>

                                                                                                        @foreach ($account_of_heads->where('level', 4) as $key_forth => $account_of_head_4)
                                                                                                            @if (Str::length($account_of_head_4->code) == 10 and
                                                                                                                $account_of_head_3->code == substr($account_of_head_4->code, 0, 6))
                                                                                                                @foreach ($account_of_heads->where('level', 5) as $key_fiveth => $account_of_head_5)
                                                                                                                    @if ($account_of_head_4->code == substr($account_of_head_5->code, 0, 10))
                                                                                                                        {{-- @dd($account_of_head_5->code); --}}
                                                                                                                        @php
                                                                                                                            $value_33 += intval(
                                                                                                                                trim(
                                                                                                                                    $accountLedgers_all
                                                                                                                                        ->where('account_head_code', $account_of_head_5->code)
                                                                                                                                        ->pluck('debit')
                                                                                                                                        ->sum() -
                                                                                                                                        $accountLedgers_all
                                                                                                                                            ->where('account_head_code', $account_of_head_5->code)
                                                                                                                                            ->pluck('credit')
                                                                                                                                            ->sum(),
                                                                                                                                    '-',
                                                                                                                                ),
                                                                                                                            );
                                                                                                                        @endphp
                                                                                                                    @endif
                                                                                                                @endforeach
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ number_format($value_33) }}
                                                                                                        </td>

                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach

                                                                        {{-- fourth level --}}


                                                                        @foreach ($account_of_heads->where('level', 4) as $key_forth => $account_of_head_4)
                                                                            @php
                                                                                $value_5 = 0;
                                                                            @endphp
                                                                            @if (Str::length($account_of_head_4->code) == 10 and
                                                                                $account_of_head_3->code == substr($account_of_head_4->code, 0, 6))
                                                                                <div class="accordion-item">
                                                                                    <h2 class="accordion-header"
                                                                                        id="flush-{{ $key_forth }}">
                                                                                        <button
                                                                                            class="accordion-button collapsed ps-3"
                                                                                            type="button"
                                                                                            data-bs-toggle="collapse"
                                                                                            data-bs-target="#flush-collapseOne-{{ $key_forth }}"
                                                                                            aria-expanded="false"
                                                                                            aria-controls="flush-collapseOne">
                                                                                            <i
                                                                                                class="bi bi-arrow-bar-right"></i><span>{{ $account_of_head_4->name }}</span>
                                                                                        </button>
                                                                                    </h2>
                                                                                    <div id="flush-collapseOne-{{ $key_forth }}"
                                                                                        class="accordion-collapse collapse"
                                                                                        aria-labelledby="flush-{{ $key_forth }}"
                                                                                        data-bs-parent="#flush-collapseOne-{{ $key }}">
                                                                                        <div
                                                                                            class="accordion-body pb-0 pe-0 pt-0">
                                                                                            @php
                                                                                                $value_4 = 0;
                                                                                                $value_5 = 0;
                                                                                                $value_33 = 0;
                                                                                            @endphp
                                                                                            <table
                                                                                                class="table table-primary table-striped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">
                                                                                                            Name
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT
                                                                                                            LEVEl
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT
                                                                                                            CODES
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT
                                                                                                            NATURE
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            Balance
                                                                                                        </th>
                                                                                                        <th class="custom_plus_th"
                                                                                                            scope="col">
                                                                                                            <i data-feather='plus'
                                                                                                                data-bs-toggle="modal"
                                                                                                                data-bs-target="#new-task-modal"></i>
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ $account_of_head_4->name }}
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ $account_of_head_4->level }}
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ account_number_format($account_of_head_4->code) }}
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ ucfirst($account_of_head_4->account_type) }}
                                                                                                        </td>
                                                                                                        @foreach ($account_of_heads->where('level', 5) as $key_fiveth => $account_of_head_5)
                                                                                                            @if (Str::length($account_of_head_5->code) > 10 and
                                                                                                                $account_of_head_4->code == substr($account_of_head_5->code, 0, 10))
                                                                                                                @php
                                                                                                                    $value_5 += intval(
                                                                                                                        trim(
                                                                                                                            $accountLedgers_all
                                                                                                                                ->where('account_head_code', $account_of_head_5->code)
                                                                                                                                ->pluck('debit')
                                                                                                                                ->sum() -
                                                                                                                                $accountLedgers_all
                                                                                                                                    ->where('account_head_code', $account_of_head_5->code)
                                                                                                                                    ->pluck('credit')
                                                                                                                                    ->sum(),
                                                                                                                            '-',
                                                                                                                        ),
                                                                                                                    );
                                                                                                                @endphp
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                        <td
                                                                                                            class="custom_td">
                                                                                                            {{ number_format($value_5) }}
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>

                                                                                            <table
                                                                                                class="table table-primary table-striped ms-0">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">
                                                                                                            Name
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT
                                                                                                            LEVEl
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT
                                                                                                            CODES
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            ACCOUNT
                                                                                                            NATURE
                                                                                                        </th>
                                                                                                        <th scope="col">
                                                                                                            Balance
                                                                                                        </th>
                                                                                                        <th class="custom_plus_th"
                                                                                                            scope="col">
                                                                                                            <i data-feather='plus'
                                                                                                                data-bs-toggle="modal"
                                                                                                                data-bs-target="#new-task-modal"></i>
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                </thead>

                                                                                                <tbody>

                                                                                                    {{-- fifth level --}}
                                                                                                    @foreach ($account_of_heads->where('level', 5) as $key_fiveth => $account_of_head_5)
                                                                                                        @if (Str::length($account_of_head_5->code) > 10 and
                                                                                                            $account_of_head_4->code == substr($account_of_head_5->code, 0, 10))
                                                                                                            {{-- @dd(trim($accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('credit')->sum() - $accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('debit')->sum())); --}}

                                                                                                            <tr>
                                                                                                                <td
                                                                                                                    class="custom_td">
                                                                                                                    {{ $account_of_head_5->name }}
                                                                                                                </td>
                                                                                                                <td
                                                                                                                    class="custom_td">
                                                                                                                    {{ $account_of_head_5->level }}
                                                                                                                </td>
                                                                                                                <td
                                                                                                                    class="custom_td">
                                                                                                                    {{ account_number_format($account_of_head_5->code) }}
                                                                                                                </td>
                                                                                                                <td
                                                                                                                    class="custom_td">
                                                                                                                    {{ ucfirst($account_of_head->account_type) }}
                                                                                                                </td>
                                                                                                                <td
                                                                                                                    class="custom_td">
                                                                                                                    @if ($account_of_head->account_type == 'debit')
                                                                                                                        {{ number_format(trim($accountLedgers_all->where('account_head_code', $account_of_head_5->code)->pluck('debit')->sum() -$accountLedgers_all->where('account_head_code', $account_of_head_5->code)->pluck('credit')->sum(),'-')) }}
                                                                                                                    @else
                                                                                                                        {{ number_format(trim($accountLedgers_all->where('account_head_code', $account_of_head_5->code)->pluck('credit')->sum() -$accountLedgers_all->where('account_head_code', $account_of_head_5->code)->pluck('debit')->sum(),'-')) }}
                                                                                                                    @endif
                                                                                                                </td>

                                                                                                            </tr>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- example according  --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>




















































    @foreach ($account_of_heads->where('level', 3) as $key_second => $account_of_head_3)
        @if (Str::length($account_of_head_3->code) == 6 and
            $account_of_head_full_array->code == substr($account_of_head_3->code, 0, 4))
            @php
                $value_4 = 0;
                $value_5 = 0;
                $value_33 = 0;
            @endphp

            @foreach ($account_of_heads->where('level', 4) as $key_forth => $account_of_head_4)
                @if (Str::length($account_of_head_4->code) == 10 and
                    $account_of_head_3->code == substr($account_of_head_4->code, 0, 6))
                    @foreach ($account_of_heads->where('level', 5) as $key_fiveth => $account_of_head_5)
                        @if ($account_of_head_4->code == substr($account_of_head_5->code, 0, 10))
                            {{-- @dd($account_of_head_5->code); --}}
                            @php
                                $value_33 += intval(
                                    trim(
                                        $accountLedgers_all
                                            ->where('account_head_code', $account_of_head_5->code)
                                            ->pluck('debit')
                                            ->sum() -
                                            $accountLedgers_all
                                                ->where('account_head_code', $account_of_head_5->code)
                                                ->pluck('credit')
                                                ->sum(),
                                        '-',
                                    ),
                                );
                            @endphp
                        @endif
                    @endforeach
                @endif
            @endforeach
            <td class="custom_td">
                {{ number_format($value_33) }}
            </td>
        @endif
    @endforeach




















@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.colVis.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.server-side.js"></script>
@endsection

@section('custom-js')

@endsection




<style>
    .custom_bg {
        margin-top: 0.5rem;
        background: whitesmoke;
        margin-top: 0 !important;
    }

    .main_multi_dop_ul {
        padding-bottom: 0 !important;
    }

    .custom_multi_drop_main {
        position: unset;
    }
</style>
