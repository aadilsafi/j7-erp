@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.accounts.trial-balance.index', encryptParams($site->id)) }}
@endsection

@section('page-title', 'Trial Balance')

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
                <h2 class="content-header-title float-start mb-0">Trial Balance</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.accounts.trial-balance.index', encryptParams($site->id)) }}
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
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 position-relative">
                                                <label class="form-label fs-5" for="type_name">Search For User Name</label>
                                                <select class="select2-size-lg form-select col-filter" id="type_name"
                                                name="type_name">
                                                <option value="0" selected>Select User Name</option>
                                                @foreach ($account_head as $account_head_name)
                                                    <option value="{{ $account_head_name->code }}">{{ $account_head_name->name }}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 position-relative">
                                                <label class="form-label" style="font-size: 15px" for="to_date">Select Date
                                                    Range</label>
                                                <input type="text" id="to_date" name="to_date"
                                                    class="form-control flatpickr-range flatpickr-input active filter_date_ranger"
                                                    placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="row mb-1 g-1">
                                            <div class="demo-inline-spacing">
                                                <div class="form-check form-check-inline">
                                                  <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="months"
                                                    id="months3"
                                                    value="3"
                                                  />
                                                  <label class="form-check-label" for="months"> 3 Months</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                  <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="months"
                                                    id="months6"
                                                    value="6"
                                                  />
                                                  <label class="form-check-label" for="months">6 Months</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                  <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="months"
                                                    id="months12"
                                                    value="12"
                                                  />
                                                  <label class="form-check-label" for="months">12 Months</label>
                                                </div>
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


                        <div class="card real_table_Data">
                            <div class="card-body">
                                <form action="#" id="sales-invoice-table-form" method="get">
                                    {{ $dataTable->table() }}
                                </form>
                            </div>
                            <div>
                            </div>
                        </div>

                        <div class="card filter_table_data d-none" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-body">
                                <table id="example" class=" table-responsive table table-striped dt-complex-header table"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" title="#">#</th>
                                            <th class="text-nowrap" title="Account Codes">Account Codes</th>
                                            <th class="text-nowrap" title="Account Name">Account Name</th>
                                            <th class="text-nowrap" title="Opening Balance">Opening Balance</th>
                                            <th class="text-nowrap" title="Debit">Debit</th>
                                            <th class="text-nowrap" title="Credit">Credit</th>
                                            <th class="text-nowrap" title="Closing Balance">Closing Balance</th>
                                            <th class="text-nowrap" title="Transactions At">Transactions At</th>
                                            <th class="text-nowrap" title="Action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>-</th>
                                            <th>-</th>
                                            <th>-</th>
                                            <th>-</th>
                                            <th></th>
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

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.server-side.js"></script>
@endsection

@section('custom-js')
    {{ $dataTable->scripts() }}
    <script>
        $(".removeErrorMessage").on('click', function() {
            $('.invalid-feedback').empty();
            $('.invalid-tooltip').hide();
            $('.alert-danger').empty();
            $('.removeInvalidMessages').find('.is-invalid').removeClass("is-invalid");
        });

        var _token = '{{ csrf_token() }}';
        let url = "{{ route('sites.accounts.ledger.ajax-get-refund-datatable', ['site_id' => encryptParams($site->id)]) }}";

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
                let type_name = $('#type_name').val();
                let months_id = $('input[type=radio][name=months]:checked').attr('id');

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
        let url = "{{ route('sites.accounts.trial-balance.ajax-filter-by-user-data-trial-balance', ['site_id' => encryptParams($site->id)]) }}";
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'date_filter': data_data,
                    'to_date': to_date,
                    'type_name': type_name,
                    'months_id': months_id,
                    'site_id': "{{$site->id}}",
                    '_token': _token,
                },
            success: function(data) {
                
                console.log((data))
                if(data.status==true){
                    $('.real_table_Data').addClass('d-none');
                    $('.filter_table_data').removeClass('d-none').addClass('d-block');
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

    $('#account_holder_name').val('');

    $('#apply_filter').trigger('click');
}
    </script>
@endsection
