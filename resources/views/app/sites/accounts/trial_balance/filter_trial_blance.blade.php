@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.accounts.trial-balance.index', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Trial Balance Filter')

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
@endsection

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
                <h2 class="content-header-title float-start mb-0">Trial Balance ({{ $account_head->name }})</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.accounts.trial-balance.index', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <section class="app-user-view-connections">
        <div class="row removeInvalidMessages">
            <div class="col-xl-12 col-lg-12">
                <div class="tab-content">
                    <div class="tab-pane active" id="salesPlanData" aria-labelledby="salesPlanData" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                                <div class="card"
                                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                    <div class="card-body">
                                        <div class="row mb-1 g-1">
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 position-relative">
                                                <label class="form-label" style="font-size: 15px" for="to_date">Select Date
                                                    Range</label>
                                                <input type="text" id="to_date" name="to_date"
                                                    class="form-control flatpickr-range flatpickr-input active filter_date_ranger"
                                                    placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto;">
                                    <div class="card"
                                        style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                        <div class="card-body">
                                            <div class="row g-1">
                                                <div class="col-md-12">
                                                    <button type="button" value="asd" name="apply_filter"
                                                        id="apply_filter"
                                                        class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                                        <i data-feather='save'></i>
                                                        Apply Filter
                                                    </button>
                                                </div>
                                                <div class="col-md-12">
                                                    <button onclick="resetFilter()"
                                                        class="btn btn-relief-outline-danger w-100 waves-effect waves-float waves-light"
                                                        type="button">
                                                        <i data-feather='x'></i>Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-body">
                                <div class="col-sm-12 d-flex justify-content-end align-items-center">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-relief-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-upload"></i> Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-clipboard"></i> Copy</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-spreadsheet"></i> CSV</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-filetype-pdf"></i> PDF</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-spreadsheet"></i>Excel</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print</a></li>
                                    </ul>
                                    <button type="reset" class="btn btn-relief-outline-danger ml-3" style="margin-right: 8px;margin-left: 8px;"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
                                    <button type="reset" class="btn btn-relief-outline-primary ml-3" style="margin-right: 7px;"><i class="bi bi-arrow-clockwise"></i> Reload</button>

                                </div>
                                <table id="example" class="table table-striped dt-complex-header table"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">#</th>
                                            <th class="text-nowrap">Account Codes</th>
                                            <th class="text-nowrap">Opening Balance</th>
                                            <th class="text-nowrap">Debit</th>
                                            <th class="text-nowrap">Credit</th>
                                            <th class="text-nowrap">Closing Balance</th>
                                            <th class="text-nowrap">Transactions At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $starting_balance_index = 0;
                                            $starting_balance = [];
                                            $ending_balance = 0;
                                            $ending_balance_new_array = [];
                                        @endphp
                                        @foreach ($account_ledgers as $account_ledger)
                                            <tr>
                                                @php
                                                if(substr($account_ledger->account_head_code, 0, 2) == 10 || substr($account_ledger->account_head_code, 0, 2) == 12 )
                                                {
                                                   
                                                    $ending_balance = $account_ledger->credit - $account_ledger->debit;
                                                    array_push($starting_balance,$ending_balance); 
                                                }else {
                                                    $ending_balance = $account_ledger->debit - $account_ledger->credit;
                                                    array_push($starting_balance,$ending_balance);
                                                }
                                                @endphp
                                                <td>{{ $i }}</td>
                                                <td class="text-nowrap">{{ account_number_format($account_ledger->account_head_code) }}</td>
                                                @if ($i > 1)
                                                    <td>{{number_format($starting_balance[$starting_balance_index - 1])}}</td>

                                                @else
                                                    <td>0</td>
                                                @endif
                                                <td class="text-nowrap">{{ number_format($account_ledger->debit) }}</td>
                                                <td class="text-nowrap">{{ number_format($account_ledger->credit) }}</td>
                                                @if ($i > 1)
                                                @php
                                                    $new_starting_balance = ($ending_balance + $starting_balance[$starting_balance_index - 1]);
                                                    $starting_balance[$starting_balance_index]= $new_starting_balance;
                                                @endphp
                                                <td class="text-nowrap">{{ number_format($new_starting_balance)}}</td>
                                                @php
                                                    array_push($ending_balance_new_array,$new_starting_balance);
                                                @endphp
                                                
                                                @else
                                                <td class="text-nowrap">{{ number_format($ending_balance)}}</td>
                                                @php
                                                    array_push($ending_balance_new_array,$ending_balance);
                                                @endphp
                                                    
                                                @endif
                                                <td class="text-nowrap">
                                                    <span>{{ date_format(new DateTime($account_ledger->created_at), 'h:i:s') }}
                                                    </span> <br> <span class='text-primary fw-bold'>
                                                        {{ date_format(new DateTime($account_ledger->created_at), 'Y-m-d') }}</span>
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                                $starting_balance_index++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>{{number_format(collect($starting_balance)->sum())}}</th>
                                            <th>{{ number_format($account_ledgers->pluck('debit')->sum()) }}</th>
                                            <th>{{ number_format($account_ledgers->pluck('credit')->sum()) }}</th>
                                            <th>{{number_format(collect($ending_balance_new_array)->sum())}}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
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

@section('custom-js')
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                responsive: true
            });
        });

        var flatpicker_to_date = null;
        $(document).ready(function() {

            flatpicker_to_date = $("#to_date").flatpickr({
                mode: "range",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            $('#apply_filter').on('click', function(e) {
                e.preventDefault();
                hideBlockUI();
                let filter_date_from = '',
                    filter_date_to = '';
                let to_date = $('#to_date').val();

                let data_data = '';
                if (to_date) {
                    let generated_at_date_range = to_date.split(' ');

                    if (generated_at_date_range[0]) {
                        filter_date_from = generated_at_date_range[0];
                        filter_date_to = generated_at_date_range[0];
                    }

                    if (generated_at_date_range[2]) {
                        filter_date_to = generated_at_date_range[2];
                    }

                    data_data += '&filter_generated_from=' + filter_date_from + '&filter_generated_to=' +
                        filter_date_to;
                }
                console.log(data_data);
        let url = "{{ route('sites.accounts.trial-balance.ajax-filter-data-trial-balance', ['site_id' => encryptParams($site_id)]) }}";
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'date_filter': data_data,
                    'to_date': to_date,
                    '_token': _token,
                    'account_head_code': '{{$account_ledgers[0]->account_head_code}}',
                },
            success: function(data) {
                
                console.log((data))
                if(data.status==true){
                    $('#example').html(data.data);
                }else{
                    console.log(data.data);
                }

            },
            error: function(error) {
                    console.log(error);
            }
        });
                
            });
        });

        function resetFilter() {

            $('#form_date').val('');
            $('#to_date').val('');
            flatpicker_to_date.clear();

            $('#apply_filter').trigger('click');

        }
    </script>
@endsection
