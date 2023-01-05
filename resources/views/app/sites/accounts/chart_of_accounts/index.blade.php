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

        .glyphicon-minus-sign::after {
            content: "−";
        }

        .glyphicon-plus-sign::before {
            content: "\2b";
            font-style: initial;
        }

        .custom_folder_icon {
            color: #ff9f43;
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
            background: whitesmoke;
        }


        .custom_plus_th {
            /* position: absolute !important; */
            right: 0;
            cursor: pointer;
            /* padding-right: 1.1rem !important; */
            text-align: end;
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

    <style>
        .tree,
        .tree ul {
            margin: 0;
            padding: 0;
            list-style: none
        }

        .tree ul {
            margin-left: 1em;
            position: relative
        }

        .tree ul ul {
            margin-left: .5em
        }

        .tree ul:before {
            content: "";
            display: block;
            width: 0;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            border-left: 1px solid;
            z-index: 1;

        }

        .tree li {
            margin: 0;
            padding: 0 1em;
            line-height: 3.5em;
            color: #369;
            font-weight: 700;
            position: relative;
            padding: 6px;
            padding-left: 0.9rem;
        }

        .tree ul li:before {
            content: "";
            display: block;
            width: 10px;
            height: 0;
            border-top: 1px solid;
            margin-top: -1px;
            position: absolute;
            top: 2.21em;
            left: 0
        }

        .tree ul li:last-child:before {
            background: #fff;
            height: auto;
            top: 2.21rem;
            bottom: 0
        }

        .indicator {
            margin-right: 5px;
        }

        .tree li a {
            text-decoration: none;
            color: #369;
        }

        .tree li button,
        .tree li button:active,
        .tree li button:focus {
            text-decoration: none;
            color: #369;
            border: none;
            background: transparent;
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
            outline: 0;
        }

        .table:not(.table-dark):not(.table-light) tfoot:not(.table-dark) th,
        .table:not(.table-dark):not(.table-light) thead:not(.table-dark) th {
            white-space: nowrap;
        }

        .table>:not(caption)>*>* {
            white-space: nowrap;
        }
    </style>
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
        <!-- Right Sidebar starts -->
        <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task-modal">
            <div class="modal-dialog sidebar-lg">
                <div class="modal-content p-0">
                    <form id="form-modal-todo" class="todo-modal needs-validation" novalidate onsubmit="return false">
                        <div class="modal-header align-items-center mb-1">
                            <h5 class="modal-title">Add Task</h5>
                            <div class="todo-item-action d-flex align-items-center justify-content-between ms-auto">
                                <span class="todo-item-favorite cursor-pointer me-75"><i data-feather="star"
                                        class="font-medium-2"></i></span>
                                <i data-feather="x" class="cursor-pointer" data-bs-dismiss="modal" stroke-width="3"></i>
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
        <div class="card pb-2">
        <ul id="tree1">
            </p>

            @foreach ($account_of_heads->where('level', 1) as $key_first => $account_of_head)
                <li class="ps-3 main_accets_lik">
                    <i class="fa-regular fa-folder custom_folder_icon"></i><a class="custom_accets_link" href="#">
                        {{ $account_of_head->name }}</a>
                    <ul>
                        <li id="{{ $account_of_head->id }}">
                            <div class="table-responsive">
                                <table class="table">
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
                                            <td class="custom_td">
                                                0
                                            </td>
                                            <td class="custom_td">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        {{-- second level --}}
                        @foreach ($account_of_heads as $key => $account_of_head_full_array)
                            @if (Str::length($account_of_head_full_array->code) == 4 and
                                $account_of_heads[$key_first]->code == substr($account_of_head_full_array->code, 0, 2))
                                <li class="ps-2"><a href="#">{{ $account_of_head_full_array->name }}</a>
                                    <ul>
                                        <li id="{{ $account_of_head_full_array->id }}">
                                            <div class="table-responsive">
                                                <table class="table">
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
                                                            <td class="custom_td">{{ $account_of_head_full_array->name }}
                                                            </td>
                                                            <td class="custom_td">{{ $account_of_head_full_array->level }}
                                                            </td>
                                                            <td class="custom_td">
                                                                {{ account_number_format($account_of_head_full_array->code) }}
                                                            </td>
                                                            <td class="custom_td">
                                                                {{ ucfirst($account_of_head_full_array->account_type) }}
                                                            </td>
                                                            <td class="custom_td">
                                                                0
                                                            </td>
                                                            <td class="custom_td">
                                                            </td>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </li>
                                        {{-- third level --}}
                                        @foreach ($account_of_heads->where('level', 3) as $key_second => $account_of_head_3)
                                            @if (Str::length($account_of_head_3->code) == 6 and
                                                $account_of_head_full_array->code == substr($account_of_head_3->code, 0, 4))
                                                <li class="ps-2">
                                                    <a onclick="getFourthLevelAccounts({{ $account_of_head_3->code }})"
                                                        href="#">{{ $account_of_head_3->name }}</a>
                                                    <ul>

                                                        <li class="fourth_level_account" id="{{ $account_of_head_3->id }}">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col">Name</th>
                                                                            <th scope="col">ACCOUNT LEVEl</th>
                                                                            <th scope="col">ACCOUNT CODES</th>
                                                                            <th scope="col">ACCOUNT NATURE</th>
                                                                            <th scope="col">Balance</th>
                                                                            <th class="custom_plus_th" scope="col">
                                                                                <i data-feather='plus'
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#new-task-modal"></i>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="custom_td">
                                                                                {{ $account_of_head_3->name }}
                                                                            </td>
                                                                            <td class="custom_td">
                                                                                {{ $account_of_head_3->level }}
                                                                            </td>
                                                                            <td class="custom_td">
                                                                                {{ account_number_format($account_of_head_3->code) }}
                                                                            </td>
                                                                            <td class="custom_td">
                                                                                {{ ucfirst($account_of_head_3->account_type) }}
                                                                            </td>
                                                                            <td class="custom_td">
                                                                                0
                                                                            </td>
                                                                            <td class="custom_td">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </li>
                                                        {{-- <li >
                                                            <a href="#">4 Level</a>
                                                            <ul>
                                                                <li>
                                                                    <table class="table">
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
                                                                        <tbody >

                                                                        </tbody>
                                                                    </table>

                                                                </li>



                                                                <li id="fifth-level-accounts">
                                                                    <table class="table">
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
                                                                        <tbody id="fifth-level-accounts">

                                                                        </tbody>
                                                                    </table>
                                                                </li>
                                                            </ul>
                                                        </li> --}}
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

    <script>
        function getFourthLevelAccounts(code) {
            // alert(code);
            showBlockUI('#tree1');
            let url =
                "{{ route('sites.accounts.charts-of-accounts.ajax-get-fourth-level-accounts', ['site_id' => encryptParams($site->id)]) }}";
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'code': code,
                    '_token': _token
                },
                success: function(data) {

                    let fourth_level_accounts = data.fourth_level_accounts;
                    $('.alreadyExistFourthLevelAccount').remove();
                    for (let index = 0; index < fourth_level_accounts.length; index++) {
                        const account_data = fourth_level_accounts[index];

                        $('.fourth_level_account').append('<li class="alreadyExistFourthLevelAccount ' +
                            account_data.code + '" id="' + account_data.code +
                            '"><a class="ps-1" href="#" onclick="fifthLevelAccounts(' + account_data.code +
                            ')">' +
                            account_data.name + '</a></li>');
                    }

                    hideBlockUI('#tree1');
                },
                error: function(error) {
                    console.log(error);
                    hideBlockUI('#tree1');
                }
            });
            hideBlockUI('#tree1');
        }

        function fifthLevelAccounts(code) {

            showBlockUI('#tree1');
            let url =
                "{{ route('sites.accounts.charts-of-accounts.ajax-get-fifth-level-accounts', ['site_id' => encryptParams($site->id)]) }}";
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'code': code,
                    '_token': _token
                },
                success: function(data) {
                    let selected_account = data.fourth_level_account;

                    $('.removeAlreadyUl').remove();
                    $('.' + code + '').append('<ul class="removeAlreadyUl fifthLevelAccounts">\
                                                            <li>\
                                                                <div class="table-responsive">\
                                                                <table class="table">\
                                                                    <thead>\
                                                                    <tr>\
                                                                        <th scope="col">Name</th>\
                                                                         <th scope="col">ACCOUNT LEVEL</th>\
                                                                         <th scope="col">ACCOUNT CODES</th>\
                                                                        <th scope="col">ACCOUNT NATURE</th>\
                                                                        <th scope="col">Balance</th>\
                                                                     </thead>\
                                                                <tbody >\
                                                                    <tr>\
                                                                        <td class="custom_td">' + selected_account.name + ' </td>\
                                                                        <td class="custom_td">' + selected_account.level + ' </td>\
                                                                        <td class="custom_td">' + selected_account.code + ' </td>\
                                                                        <td class="custom_td">' + selected_account.account_type + ' </td>\
                                                                        <td class="custom_td">0</td>\
                                                                    </tr>\
                                                                </tbody>\
                                                                </table>\
                                                                </div>\
                                                             </li>\
                                                            </ul>');

                    let fifth_level_accounts = data.fifth_level_accounts;


                    $('.alreadyExistFifthLevelAccount').remove();
                    for (let index = 0; index < fifth_level_accounts.length; index++) {
                        const account_data = fifth_level_accounts[index];
                        console.log(account_data.name)
                        $('.fifthLevelAccounts').append('<li class="ps-3 alreadyExistFifthLevelAccount">\
                                                        <div class="table-responsive">\
                                                            <table class="table">\
                                                                    <thead>\
                                                                    <tr>\
                                                                        <th scope="col">Name</th>\
                                                                         <th scope="col">ACCOUNT LEVEL</th>\
                                                                         <th scope="col">ACCOUNT CODES</th>\
                                                                        <th scope="col">ACCOUNT NATURE</th>\
                                                                        <th scope="col">Balance</th>\
                                                                     </thead>\
                                                                <tbody >\
                                                                    <tr>\
                                                                        <td class="custom_td">' + account_data.name + ' </td>\
                                                                        <td class="custom_td">' + account_data.level + ' </td>\
                                                                        <td class="custom_td">' + account_data.code + ' </td>\
                                                                        <td class="custom_td">' + account_data.account_type + ' </td>\
                                                                        <td class="custom_td">0</td>\
                                                                    </tr>\
                                                                </tbody>\
                                                                </table>\
                                                                </div>\
                                                        </li>');
                    }


                    hideBlockUI('#tree1');
                },
                error: function(error) {
                    console.log(error);
                    hideBlockUI('#tree1');
                }
            });


            // <th class="custom_plus_th" scope="col">\
            //                                 <i data-feather="plus" data-bs-toggle="modal" data-bs-target="#new-task-modal">Plus</i>\
            //                             </tr>\

            // <td class="custom_td">0</td>\




            hideBlockUI('#tree1');
        }
    </script>

    <script>
        $.fn.extend({
            treed: function(o) {

                var openedClass = 'glyphicon-minus-sign';
                var closedClass = 'glyphicon-plus-sign';

                if (typeof o != 'undefined') {
                    if (typeof o.openedClass != 'undefined') {
                        openedClass = o.openedClass;
                    }
                    if (typeof o.closedClass != 'undefined') {
                        closedClass = o.closedClass;
                    }
                };

                //initialize each of the top levels
                var tree = $(this);
                tree.addClass("tree");
                tree.find('li').has("ul").each(function() {
                    var branch = $(this); //li with children ul
                    branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
                    branch.addClass('branch');
                    branch.on('click', function(e) {
                        if (this == e.target) {
                            var icon = $(this).children('i:first');
                            icon.toggleClass(openedClass + " " + closedClass);
                            $(this).children().children().toggle();
                        }
                    })
                    branch.children().children().toggle();
                });
                //fire event from the dynamically added icon
                tree.find('.branch .indicator').each(function() {
                    $(this).on('click', function() {
                        $(this).closest('li').click();
                    });
                });
                //fire event to open branch if the li contains an anchor instead of text
                tree.find('.branch>a').each(function() {
                    $(this).on('click', function(e) {
                        $(this).closest('li').click();
                        e.preventDefault();
                    });
                });
                //fire event to open branch if the li contains a button instead of text
                tree.find('.branch>button').each(function() {
                    $(this).on('click', function(e) {
                        $(this).closest('li').click();
                        e.preventDefault();
                    });
                });
            }
        });

        //Initialization of treeviews

        $('#tree1').treed();

        $('#tree2').treed({
            openedClass: 'glyphicon-folder-open',
            closedClass: 'glyphicon-folder-close'
        });

        $('#tree3').treed({
            openedClass: 'glyphicon-chevron-right',
            closedClass: 'glyphicon-chevron-down'
        });
    </script>
@endsection
