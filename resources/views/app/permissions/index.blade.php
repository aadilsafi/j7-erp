@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'permissions.index') }}
@endsection

@section('page-title', __('lang.permissions.permission_plural'))

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
                <h2 class="content-header-title float-start mb-0">{{ __('lang.permissions.permission_plural') }}</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('permissions.index') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <p class="mb-2">
        {{ __('lang.permissions.pages.index.description') }}
    </p>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('permissions.destroy.selected') }}" id="permissions-table-form" method="get">
                {{ $dataTable->table() }}
            </form>
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
                    confirmButtonClass: 'btn-danger',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#permissions-table-form').submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: '{{ __('lang.commons.please_select_at_least_one_item') }}',
                });
            }
        }

        function deleteByID(id) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: '{{ __('lang.commons.are_you_sure') }}',
                showCancelButton: true,
                cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
                confirmButtonText: '{{ __('lang.commons.yes_delete') }}',
                confirmButtonClass: 'btn-danger',
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{ route('permissions.destroy', ['id' => ':id']) }}'.replace(':id', id);
                }
            });
        }

        function changeRolePermission(role_id, permission_id) {

            var checkBoxState = $('#chkRolePermission_' + role_id + '_' + permission_id).is(':checked');
            var url = "";
            if (checkBoxState) {
                url = '{{ route('permissions.assign-permission') }}';
            } else {
                url = '{{ route('permissions.revoke-permission') }}';
            }


            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    role_id: role_id,
                    permission_id: permission_id,
                },
                success: function(response) {
                    // console.log(response);
                    if (response.success) {
                        toastr.success(response.message,
                            "Success!", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 2e3,
                                closeButton: !0,
                                tapToDismiss: !1,
                            });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                }
            });
        }
    </script>
@endsection
