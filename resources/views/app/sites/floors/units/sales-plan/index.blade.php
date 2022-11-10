@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.sales-plans.index', encryptParams($site), encryptParams($floor), encryptParams($unit->id)) }}
@endsection

@section('page-title', 'Sales Plan List')

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
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Sales Plan ({{ $unit->name }})</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.sales-plans.index', encryptParams($site), encryptParams($floor), encryptParams($unit->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <p class="mb-2">
    </p>

    <div class="card" id="salesPlan">
        <div class="card-body">
            <form
                action="{{ route('sites.floors.units.sales-plans.destroy-selected', ['site_id' => encryptParams($site), 'floor_id' => encryptParams($floor), 'unit_id' => encryptParams($unit->id)]) }}"
                id="floors-units-sales-plan-table-form" method="get">
                {{ $dataTable->table() }}
            </form>

            {{-- Printing Modal --}}
            @include('app.sites.floors.units.sales-plan.partials.print-templates', [
                'salesPlanTemplates' => $salesPlanTemplates,
            ])

        </div>
    </div>

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
        function deleteSelected() {
            var selectedCheckboxes = $('.dt-checkboxes:checked').length;
            if (selectedCheckboxes > 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: '{{ __('lang.commons.are_you_sure_you_want_to_delete_the_selected_items') }}',
                    showCancelButton: true,
                    cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
                    confirmButtonText: '{{ __('lang.commons.yes_delete') }}',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1',
                        cancelButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#floors-units-table-form').submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-relief-outline-primary waves-effect waves-float waves-light me-1',
                    },
                    text: '{{ __('lang.commons.please_select_at_least_one_item') }}',
                });
            }
        }

        function addNew() {
            location.href =
                "{{ route('sites.floors.units.sales-plans.create', ['site_id' => encryptParams($site), 'floor_id' => encryptParams($floor), 'unit_id' => encryptParams($unit->id)]) }}";
        }

        function openTemplatesModal(sales_plan_id) {
            $('#sales_plan_id').val(sales_plan_id);
            $('#modal-sales-plan-template').modal('show');
        }

        function printSalesPlanTemplate(template_id) {
            let sales_plan_id = $('#sales_plan_id').val();
            let url =
                "{{ route('sites.floors.units.sales-plans.templates.print', ['site_id' => encryptParams($site), 'floor_id' => encryptParams($floor), 'unit_id' => encryptParams($unit->id), 'sales_plan_id' => ':sales_plan_id', 'id' => ':id']) }}"
                .replace(':sales_plan_id', sales_plan_id)
                .replace(':id', template_id);
            window.open(url, '_blank').focus();
            // location.href =
            //     "{{ route('sites.floors.units.sales-plans.templates.print', ['site_id' => encryptParams($site), 'floor_id' => encryptParams($floor), 'unit_id' => encryptParams($unit->id), 'sales_plan_id' => ':sales_plan_id', 'id' => ':id']) }}"
            //     .replace(':sales_plan_id', sales_plan_id)
            //     .replace(':id', template_id)
        }

        function approveSalesPlan(id) {
            showBlockUI('#salesPlan');
            var _token = '{{ csrf_token() }}';
            let url = "{{ route('sites.floors.units.sales-plans.approve-sales-plan', ['site_id' => encryptParams($site), 'floor_id' => encryptParams($floor), 'unit_id' => encryptParams($unit->id)]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'salesPlanID': id,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message,
                            "Success!", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 2e3,
                                closeButton: !0,
                                tapToDismiss: !1,
                            });
                            $('#sales-plan-table').DataTable().ajax.reload();
                            location.reload(true);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something Went Wrong!!',
                        });
                        hideBlockUI('#salesPlan');
                    }
                },
                error: function(error) {
                    console.log(error);
                    hideBlockUI('#salesPlan');
                }
            });
        }

        function disapproveSalesPlan(id) {
            showBlockUI('#salesPlan');
            var _token = '{{ csrf_token() }}';
            let url = "{{ route('sites.floors.units.sales-plans.disapprove-sales-plan', ['site_id' => encryptParams($site), 'floor_id' => encryptParams($floor), 'unit_id' => encryptParams($unit->id)]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'salesPlanID': id,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message,
                            "Success!", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 2e3,
                                closeButton: !0,
                                tapToDismiss: !1,
                            });
                            $('#sales-plan-table').DataTable().ajax.reload();
                            location.reload(true);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something Went Wrong!!',
                        });
                        hideBlockUI('#salesPlan');
                    }
                },
                error: function(error) {
                    console.log(error);
                    hideBlockUI('#salesPlan');
                }
            });
        }

    </script>
@endsection
