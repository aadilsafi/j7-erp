@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.import', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Import Sales Plan Additional Costs')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">

@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Import Sales Plan Additional Costs</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.import', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sites.floors.spadcostsImport.saveImport', ['site_id' => encryptParams($site_id)]) }}"
                id="teams-table-form" method="post">
                @csrf
                {{-- <form action="{{ route('storePreviewtest') }}" id="teams-table-form" method="get"> --}}
                {{ $dataTable->table() }}

            </form>
            <hr>

            <div class="row mt-1">
                <div class="col"></div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <a href="{{ route('sites.types.index', ['site_id' => encryptParams($site_id)]) }}"
                        class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                        <i data-feather='x'></i>
                        {{ __('lang.commons.cancel') }}
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button id="finalSubmit"
                        class="btn btn-md w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                        <i data-feather='save'></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.select.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')
    {{ $dataTable->scripts() }}

    <script>
        $(document).ready(function() {
            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            $('.removeTolltip').tooltip('disable');

        });
        showBlockUI();

        $(document).ready(function() {
            hideBlockUI();
        });

        FilePond.registerPlugin(
            FilePondPluginFileValidateSize,

        );
        FilePond.create(document.getElementById('attachment'), {
            styleButtonRemoveItemPosition: 'right',
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            required: true,
            allowMultiple: false,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });

        $('#kt_table_1').DataTable({
            ordering: false,
            sorting: false,

        });


        $(document).on('click', '.unit_p_input_div', function(e) {
            if (!$(this).hasClass('filedrendered')) {
                id = $(this).data('id');
                field = $(this).data('field');
                showBlockUI('#unit_p_input_div_' + field + id);
                value = $(this).data('value');
                inputtype = $(this).data('inputtype');
                el = $(this);
                $('#teams-table-form').css("pointer-events", "none")

                var url = "{{ route('ajax-import-sales-plan.adCosts.get.input') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        value: value,
                        id: id,
                        field: field,
                        inputtype: inputtype,
                        updateValue: false
                    },
                    success: function(response) {
                        // console.log(response['data']);
                        if (response['status']) {
                            // console.log('insuccess');
                            el.empty();
                            el.append(response['data']);
                            el.addClass('filedrendered');
                        }
                        $('#teams-table-form').css("pointer-events", "")
                        hideBlockUI('#unit_p_input_div_' + field + id);
                    },
                    error: function(response) {
                        $('#teams-table-form').css("pointer-events", "")

                        hideBlockUI('#unit_p_input_div_' + field + id);
                    },
                });
            }
        });
        $(document.body).on('focusout', '.unit-p-text-input', function(e) {
            if (!$(this).hasClass('filedrendered')) {
                id = $(this).data('id');
                field = $(this).data('field');
                // showBlockUI('#unit_p_input_div_' + field + id);
                value = $(this).data('value');
                inputtype = $(this).data('inputtype');
                el = $(this);
                $('#teams-table-form').css("pointer-events", "none")
                var url = "{{ route('ajax-import-sales-plan.adCosts.get.input') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        value: el.val(),
                        id: id,
                        field: field,
                        inputtype: inputtype,
                        updateValue: true
                    },
                    success: function(response) {
                        // console.log(response['data']);
                        if (response['status']) {
                            console.log('insuccess');
                            el = el.parent()
                            el.empty();
                            el.append(response['data']);
                            el.addClass('filedrendered');
                            toastr.success('Updated');
                        } else {
                            toastr.error(response['message']['error']);

                        }
                        $('#teams-table-form').css("pointer-events", "")
                        // hideBlockUI('#unit_p_input_div_' + field + id);

                    },
                    error: function(response) {
                        $('#teams-table-form').css("pointer-events", "")

                        // hideBlockUI('#unit_p_input_div_' + field + id);
                    },
                });
            }
        });


        $('#finalSubmit').on('click', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Confirmation',
                text: 'Are you sure to Import File',
                confirmButtonText: 'Yes',
                cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
                showCancelButton: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1',
                    cancelButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1',
                },
            }).then((result) => {
                if (result.isConfirmed) {

                    // var formData = new FormData(document.querySelector('form'))
                    // console.log($('.selectField').val());
                    $('#teams-table-form').submit();
                }
            });
        });
    </script>
@endsection
