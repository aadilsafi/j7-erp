@extends('app.layout.layout')

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


@section('custom-css')
@endsection

@section('content')

    <section class="app-user-view-connections">
        <div class="row removeInvalidMessages">
            <div class="col-xl-12 col-lg-12">
                <div class="tab-content">
                    <div class="tab-pane active" id="salesPlanData" aria-labelledby="salesPlanData" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                                        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                            <div class="card-body">
                                                <div class="row mb-1 g-1">
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 position-relative">
                                                        <label class="form-label" style="font-size: 15px" for="to_date">To Date</label>
                                                        <input type="text" id="to_date" name="to_date"
                                                            class="form-control flatpickr-range flatpickr-input active filter_date_ranger"
                                                            placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly="readonly">
                                                    </div>
                    
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 position-relative">
                                                        <label class="form-label" style="font-size: 15px" for="form_date">Form Date</label>
                                                        <input type="text" id="form_date" name="form_date"
                                                            class="form-control flatpickr-range flatpickr-input active filter_date_ranger"
                                                            placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    
                    
                                    <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                        <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto;">
                                            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                                <div class="card-body">
                                                    <div class="row g-1">
                                                        <div class="col-md-12">
                                                            <button type="button" value="asd" name="apply_filter" id="apply_filter"
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
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">#</th>
                                            <th class="text-nowrap">Account Codes</th>
                                            <th class="text-nowrap">Starting Balance</th>
                                            <th class="text-nowrap">Debit</th>
                                            <th class="text-nowrap">Credit</th>
                                            <th class="text-nowrap">Ending Balance</th>
                                            <th class="text-nowrap">Transactions At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i=1;
                                        @endphp
                                        @foreach ($account_ledgers as $account_ledger)
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{account_number_format($account_ledger->account_head_code)}}</td>
                                                <td>Starting Balance</td>
                                                <td>{{number_format($account_ledger->debit)}}</td>
                                                <td>{{number_format($account_ledger->credit)}}</td>
                                                <td>Ending Balance</td>
                                                <td>{{$account_ledger->created_at}}</td>
                                            </tr>
                                            @php
                                                
                                                $i++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>{{$account_ledgers->pluck('debit')->sum()}}</th>
                                            <th>{{$account_ledgers->pluck('credit')->sum()}}</th>
                                            <th></th>
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
        var table = $('#example').DataTable( {
        responsive: true
    } );
 
    new $.fn.dataTable.FixedHeader( table );
} );

var flatpicker_form_date = null,
    flatpicker_to_date = null;
$(document).ready(function() {

    flatpicker_to_date = $("#to_date").flatpickr({
        mode: "range",
        altInput: !0,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });

    flatpicker_form_date = $("#form_date").flatpickr({
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
        let form_date = $('#form_date').val();
        let to_date = $('#to_date').val();

        let data = '?';
        if (to_date) {
            let generated_at_date_range = to_date.split(' ');

            if (generated_at_date_range[0]) {
                filter_date_from = generated_at_date_range[0];
                filter_date_to = generated_at_date_range[0];
            }

            if (generated_at_date_range[2]) {
                filter_date_to = generated_at_date_range[2];
            }

            data += '&filter_generated_from=' + filter_date_from + '&filter_generated_to=' +
                filter_date_to;
        }
        if (form_date) {
            var approved_date_range = form_date.split(' ');

            if (approved_date_range[0]) {
                filter_date_from = approved_date_range[0];
                filter_date_to = approved_date_range[0];
            }

            if (approved_date_range[2]) {
                filter_date_to = approved_date_range[2];
            }

            data += '&filter_approved_from=' + filter_date_from + '&filter_approved_to=' +
                filter_date_to;
        }

        salesPlanDataTable.ajax.url(data).load();
    });
});

function resetFilter() {

$('#form_date').val('');
$('#to_date').val('');
flatpicker_to_date.clear();
flatpicker_form_date.clear();

$('#apply_filter').trigger('click');

}
    </script>
@endsection
