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

                                    .vertical-overlay-menu .custom_multi_drop_main, .vertical-overlay-menu.menu-hide .custom_multi_drop_main

                                    .main-menu.menu-light .navigation>.custom_bg.open:not(.menu-item-closing)>a,
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
                                </style>
                                <div class="main-menu menu-light menu-accordion custom_multi_drop_main">
                                    <div class="main-menu menu-light menu-accordion custom_multi_drop_main">
                                        <div class="">

                                            <ul class="navigation navigation-main main_multi_dop_ul" id="main-menu-navigation" data-menu="menu-navigation">
                                                @foreach ($account_of_heads->where('level',1) as $key_first=>$account_of_head)
                                                @php
                                                    $value_44 = 0 ;
                                                    $value_55 = 0 ;
                                                @endphp
                                                {{-- @dd($account_of_head->code); --}}
                                                    <li class="custom_bg nav-item Second_li "><a class="d-flex align-items-center" href="#"><span class="menu-title text-truncate" data-i18n="eCommerce">{{$account_of_head->name}}</span></a>
                                                        <ul class="menu-content">
                                                            <table class="table table-primary table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Name</th>
                                                                        <th scope="col">ACCOUNT LEVEl</th>
                                                                        <th scope="col">ACCOUNT CODES</th>
                                                                        <th scope="col">Balance</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="custom_td">{{$account_of_head->name}}</td>
                                                                        <td class="custom_td">{{$account_of_head->level}}</td>
                                                                        <td class="custom_td">{{account_number_format($account_of_head->code)}}</td>




                                                                        @foreach ($account_of_heads->where('level',4) as $key_forth=>$account_of_head_4)
                                                                            @if ( Str::length($account_of_head_4->code) == 10 AND ($account_of_head->code == substr($account_of_head_4->code, 0, 2)))
                                                                                @foreach ($account_of_heads->where('level',5) as $key_fiveth=>$account_of_head_5)
                                                                                    @if (($account_of_head_4->code == substr($account_of_head_5->code, 0, 10)))
                                                                                        @php
                                                                                            $value_55 += intval(trim($accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('credit')->sum() - $accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('debit')->sum(),'-'));
                                                                                        @endphp
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                        <td class="custom_td">{{number_format($value_55)}}</td>

                                                                        {{-- <td class="custom_td">{{trim((collect($account_balances)->pluck('credit_'.$account_of_head->code)->sum() - collect($account_balances)->pluck('debit_'.$account_of_head->code)->sum()),'-')}}</td> --}}
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            @foreach ($account_of_heads as $key=>$account_of_head_full_array)
                                                            @if ( Str::length($account_of_head_full_array->code) == 4 AND ($account_of_heads[$key_first]->code == substr($account_of_head_full_array->code, 0, 2)))
                                                                <li class="nav-item Second_li ms-3">
                                                                    <a class="d-flex align-items-center" href="#"><span class="menu-title text-truncate" data-i18n="eCommerce"><i class="bi bi-arrow-bar-right"></i>{{$account_of_head_full_array->name}}</span></a>
                                                                        <ul class="menu-content">

                                                                            <table class="table table-primary table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="col">Name</th>
                                                                                        <th scope="col">ACCOUNT LEVEl</th>
                                                                                        <th scope="col">ACCOUNT CODES</th>
                                                                                        <th scope="col">Balance</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="custom_td">{{$account_of_head_full_array->name}}</td>
                                                                                        <td>{{$account_of_head_full_array->level}}</td>
                                                                                        <td>{{account_number_format($account_of_head_full_array->code)}}</td>



                                                                                        @foreach ($account_of_heads->where('level',4) as $key_forth=>$account_of_head_4)
                                                                                        @if ( Str::length($account_of_head_4->code) == 10 AND ($account_of_head_full_array->code == substr($account_of_head_4->code, 0, 4)))
                                                                                            @foreach ($account_of_heads->where('level',5) as $key_fiveth=>$account_of_head_5)
                                                                                                @if (($account_of_head_4->code == substr($account_of_head_5->code, 0, 10)))
                                                                                                    @php
                                                                                                        $value_44 += intval(trim($accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('credit')->sum() - $accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('debit')->sum(),'-'));
                                                                                                    @endphp
                                                                                                @endif
                                                                                            @endforeach
                                                                                         @endif
                                                                                    @endforeach
                                                                                        <td class="custom_td">{{number_format($value_44)}}</td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            @foreach ($account_of_heads->where('level',3) as $key_second=>$account_of_head_3)
                                                                                @if ( Str::length($account_of_head_3->code) == 6 AND ($account_of_head_full_array->code == substr($account_of_head_3->code, 0, 4)))
                                                                                    <li class="nav-item Second_li ms-1">
                                                                                        <a class="d-flex align-items-center" href="#"><span class="menu-title text-truncate" data-i18n="eCommerce"><i class="bi bi-arrow-bar-right"></i>{{$account_of_head_3->name}}</span></a>
                                                                                            <ul class="menu-content ms-3">
                                                                                                @php
                                                                                                    $value_4=0;
                                                                                                    $value_5=0;
                                                                                                    $value_33=0;
                                                                                                @endphp

                                                                                                <table class="table table-primary table-striped">
                                                                                                    <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col">Name</th>
                                                                                                        <th scope="col">ACCOUNT LEVEl</th>
                                                                                                        <th scope="col">ACCOUNT CODES</th>
                                                                                                        <th scope="col">Balance</th>
                                                                                                    </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                    <tr>
                                                                                                        <td class="custom_td">{{$account_of_head_3->name}}</td>
                                                                                                        <td class="custom_td">{{$account_of_head_3->level}}</td>
                                                                                                        <td class="custom_td">{{account_number_format($account_of_head_3->code)}}</td>









                                                                                                    @foreach ($account_of_heads->where('level',4) as $key_forth=>$account_of_head_4)
                                                                                                        @if ( Str::length($account_of_head_4->code) == 10 AND ($account_of_head_3->code == substr($account_of_head_4->code, 0, 6)))
                                                                                                            @foreach ($account_of_heads->where('level',5) as $key_fiveth=>$account_of_head_5)
                                                                                                                @if (($account_of_head_4->code == substr($account_of_head_5->code, 0, 10)))
                                                                                                                {{-- @dd($account_of_head_5->code); --}}
                                                                                                                    @php
                                                                                                                        $value_33 += intval(trim($accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('credit')->sum() - $accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('debit')->sum(),'-'));
                                                                                                                    @endphp
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                         @endif
                                                                                                    @endforeach
                                                                                                        <td class="custom_td">{{number_format($value_33)}}</td>

                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>

                                                                                                @foreach ($account_of_heads->where('level',4) as $key_forth=>$account_of_head_4)
                                                                                                    @php
                                                                                                        $value_5=0;
                                                                                                    @endphp
                                                                                                    @if ( Str::length($account_of_head_4->code) == 10 AND ($account_of_head_3->code == substr($account_of_head_4->code, 0, 6)))
                                                                                                        <li class="nav-item Second_li ">
                                                                                                            <a class="d-flex align-items-center" href="#"><span class="menu-title text-truncate" data-i18n="eCommerce"><i class="bi bi-arrow-bar-right"></i>{{$account_of_head_4->name}}</span></a>
                                                                                                                <ul class="menu-content ms-3">
                                                                                                                <table class="table table-primary table-striped">
                                                                                                                        <thead>
                                                                                                                        <tr>
                                                                                                                            <th scope="col">Name</th>
                                                                                                                            <th scope="col">ACCOUNT LEVEl</th>
                                                                                                                            <th scope="col">ACCOUNT CODES</th>
                                                                                                                            <th scope="col">Balance</th>
                                                                                                                        </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                        <tr>
                                                                                                                            <td class="custom_td">{{$account_of_head_4->name}}</td>
                                                                                                                            <td class="custom_td">{{$account_of_head_4->level}}</td>
                                                                                                                            <td class="custom_td">{{account_number_format($account_of_head_4->code)}}</td>
                                                                                                                            @foreach ($account_of_heads->where('level',5) as $key_fiveth=>$account_of_head_5)
                                                                                                                            @if ( Str::length($account_of_head_5->code) > 10 AND ($account_of_head_4->code == substr($account_of_head_5->code, 0, 10)))
                                                                                                                                @php
                                                                                                                                    $value_5 += intval(trim($accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('credit')->sum() - $accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('debit')->sum(),'-'));
                                                                                                                                @endphp
                                                                                                                             @endif
                                                                                                                             @endforeach
                                                                                                                             <td class="custom_td">{{number_format($value_5)}}</td>
                                                                                                                            </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>

                                                                                                                    <table class="table table-primary table-striped ms-4">
                                                                                                                        <thead>
                                                                                                                        <tr>
                                                                                                                            <th scope="col">Name</th>
                                                                                                                            <th scope="col">ACCOUNT LEVEl</th>
                                                                                                                            <th scope="col">ACCOUNT CODES</th>
                                                                                                                            <th scope="col">Balance</th>
                                                                                                                        </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                    @foreach ($account_of_heads->where('level',5) as $key_fiveth=>$account_of_head_5)
                                                                                                                    @if ( Str::length($account_of_head_5->code) > 10 AND ($account_of_head_4->code == substr($account_of_head_5->code, 0, 10)))
                                                                                                                    {{-- @dd(trim($accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('credit')->sum() - $accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('debit')->sum())); --}}
                                                                                                                            <tr>
                                                                                                                                <td class="custom_td">{{$account_of_head_5->name}}</td>
                                                                                                                                <td class="custom_td">{{$account_of_head_5->level}}</td>
                                                                                                                                <td class="custom_td">{{account_number_format($account_of_head_5->code)}}</td>
                                                                                                                                <td class="custom_td">{{number_format(trim($accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('credit')->sum() - $accountLedgers_all->where('account_head_code',$account_of_head_5->code)->pluck('debit')->sum(),'-'))}}</td>

                                                                                                                            </tr>
                                                                                                                         @endif
                                                                                                                    @endforeach
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                            </ul>
                                                                                                        </li>
                                                                                                     @endif

                                                                                                @endforeach
                                                                                        </ul>
                                                                                    </li>
                                                                                @endif

                                                                            @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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

{{-- <section style="min-height: 40vh"> --}}
    <style>
      .custom_bg{
        margin-top: 0.5rem;
        background: whitesmoke;
        margin-top: 0 !important;
      }
      .main_multi_dop_ul{
        padding-bottom: 0 !important;
      }
      .custom_multi_drop_main{
        position: unset;
      }
    </style>
