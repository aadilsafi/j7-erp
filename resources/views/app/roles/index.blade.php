@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'roles.index') }}
@endsection

@section('page-title', __('lang.roles.role_plural'))

@section('page-vendor')
    {{-- <link rel="stylesheet" type="text/css"
href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css"
href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
<link rel="stylesheet" type="text/css"
href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css"> --}}


    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/pickers/flatpickr/flatpickr.min.css">

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
                <h2 class="content-header-title float-start mb-0">{{ __('lang.roles.role_plural') }}</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('roles.index') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <p class="mb-2">
        {{ __('lang.roles.pages.index.description') }}
    </p>

    <!-- Role cards -->
    <div class="row">
        @forelse ($roles as $role)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="d-flex justify-content-between">
                            <span>Total 4 users</span>
                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    title="Vinnie Mostowy" class="avatar avatar-sm pull-up">
                                    <img class="rounded-circle" src="{{ asset('app-assets') }}/images/avatars/2.png"
                                        alt="Avatar" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    title="Allen Rieske" class="avatar avatar-sm pull-up">
                                    <img class="rounded-circle" src="{{ asset('app-assets') }}/images/avatars/12.png"
                                        alt="Avatar" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    title="Julee Rossignol" class="avatar avatar-sm pull-up">
                                    <img class="rounded-circle" src="{{ asset('app-assets') }}/images/avatars/6.png"
                                        alt="Avatar" />
                                </li>
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                    title="Kaith D'souza" class="avatar avatar-sm pull-up">
                                    <img class="rounded-circle" src="{{ asset('app-assets') }}/images/avatars/11.png"
                                        alt="Avatar" />
                                </li>
                            </ul>
                        </div> --}}
                        <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                            <div class="role-heading">
                                <h4 class="fw-bolder">
                                    {{ __('lang.roles.pages.index.rolecard_title', ['value' => $role->name]) }} (
                                    {{ __('lang.roles.pages.index.rolecard_guard', ['value' => $role->guard_name]) }} )
                                </h4>
                                @can('roles.edit')
                                    <a href="{{ route('roles.edit', ['id' => encryptParams($role->id)]) }}"
                                        class="role-edit-modal">
                                        <small class="fw-bolder">{{ __('lang.roles.edit_role') }}</small>
                                    </a>
                                @endcan
                            </div>
                            {{-- <a href="javascript:void(0);" class="text-body">
                                <i data-feather="copy" class="font-medium-5"></i>
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
        @can('roles.create')
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-end justify-content-center h-100">
                                <img src="{{ asset('app-assets') }}/images/illustration/faq-illustrations.svg"
                                    class="img-fluid mt-2" alt="Image" width="85" />
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="card-body text-sm-end text-center ps-sm-0">
                                <a href="{{ route('roles.create') }}" class="btn btn-relief-outline-primary mb-1">
                                    <span class=""><i data-feather="plus"></i>
                                        {{ __('lang.roles.add_new_role') }}</span>
                                </a>
                                <p class="mb-0">{{ __('lang.roles.pages.extras.add_role_if_it_does_not_exist') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
    <!--/ Role cards -->

    <div class="card">
        <div class="card-body">
            {{-- <table class="datatable table table-hover table-striped">
                <thead>
                    <tr>
                        <th>{{ __('lang.commons.fields.hash') }}</th>
                        <th>{{ __('lang.roles.pages.fields.role_name') }}</th>
                        <th>{{ __('lang.roles.pages.fields.guard_name') }}</th>
                        <th>{{ __('default') }}</th>
                        <th>{{ __('lang.commons.fields.created_at') }}</th>
                        <th>{{ __('lang.commons.fields.updated_at') }}</th>
                        <th>{{ __('lang.commons.fields.actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <thead>
                    <tr>
                        <th>{{ __('lang.commons.fields.hash') }}</th>
                        <th>{{ __('lang.roles.pages.fields.role_name') }}</th>
                        <th>{{ __('lang.roles.pages.fields.guard_name') }}</th>
                        <th>{{ __('default') }}</th>
                        <th>{{ __('lang.commons.fields.created_at') }}</th>
                        <th>{{ __('lang.commons.fields.updated_at') }}</th>
                        <th>{{ __('lang.commons.fields.actions') }}</th>
                    </tr>
                </thead>
            </table> --}}
            <form action="{{ route('roles.destroy-selected') }}" id="roles-table-form" method="get">
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
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.select.min.js"></script>
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
                        $('#roles-table-form').submit();
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
                    location.href = '{{ route('roles.destroy', ['id' => ':id']) }}'.replace(':id', id);
                }
            });
        }
    </script>
@endsection
