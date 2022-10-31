@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.import', $site_id) }}
@endsection

@section('page-title', 'Import Floor')

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
                <h2 class="content-header-title float-start mb-0">Import Floor</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.import', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    @if (Session::get('data'))
        @php
            $errorData = Session::get('data');
        @endphp
    @endif

    <div class="row">
        @if (isset($errorData))
            <div class="col-9 card">
                <h4 class="text-danger mt-1 p-1"> * Resolve Conflicts and Upload File again</h4>

                <table id="kt_table_1" class="table table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th class="text-danger">Line #</th>
                            <th class="text-danger">Error's</th>

                            @foreach ($errorData[0]->values() as $key => $value)
                                <th class="title">{{ $key }}</th>
                            @endforeach

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($errorData as $key => $row)
                            <tr>
                                <td class="text-danger">{{ $row->row() }}</td>
                                <td class="text-danger">
                                    @foreach ($row->errors() as $value)
                                        {{ $value }}
                                    @endforeach
                                </td>
                                @foreach ($row->values() as $k => $value)
                                    {{-- @if ($row->attribute() == $k)
                                        <td>
                                            <div class="unit_p_input_div bg-danger" data-field="{{ $k }}"
                                                data-value="{{ $value }}"
                                                data-inputtype="{{ $k == 'floor_area' ? 'number' : 'text' }}">
                                                {{ $value }}

                                                <input type="hidden" value='{{ json_encode($row->values()) }}'
                                                    class="dataToSave">
                                            </div>
                                        </td>
                                    @else --}}
                                    <td @if ($row->attribute() == $k) class="text-danger" @endif>{{ $value }}
                                    </td>
                                    {{-- @endif --}}
                                @endforeach

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                {{-- <div class="row">
                    <div class="col"></div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <a href="#" class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                            <i data-feather='x'></i>
                            {{ __('lang.commons.cancel') }}
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <button value="save" onclick="PreviewFile()"
                            class="btn btn-md w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                            <i data-feather='save'></i>
                            Preview Import File
                        </button>
                    </div>
                </div> --}}

            </div>
        @endif
        <div class="col">
            <form class="form form-vertical"
                action="{{ route('sites.floors.importFloorsPreview', ['site_id' => $site_id]) }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row mt-5">
                    <div class="col position-relative">
                        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-body">
                                <div class="d-block mb-1">
                                    <label class="form-label fs-5" for="type_name">Import </label>
                                    <input id="attachment" type="file" class="filepond" name="attachment" />
                                </div>
                                <hr>
                                <button type="submit" value="save"
                                    class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                                    <i data-feather='save'></i>
                                    Preview Import File
                                </button>

                                <a href="{{ route('sites.floors.index', ['site_id' => $site_id]) }}"
                                    class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                    <i data-feather='x'></i>
                                    {{ __('lang.commons.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 position-relative"></div>
                </div>
            </form>
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

    <script>
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
            searching: false,
            responsive: false,
            scrollX: true,
            lengthMenu: [50, 100, 500],
        });

        $(document).on('click', '.unit_p_input_div', function(e) {
            if (!$(this).hasClass('filedrendered')) {
                field = $(this).data('field');
                value = $(this).data('value');
                inputtype = $(this).data('inputtype');
                el = $(this);
                if (!$(this).data('id')) {
                    datatoSave = JSON.parse(el.children('.dataToSave').val());
                    id = 0;
                    CreateErrorValue = true;
                } else {
                    CreateErrorValue = false;
                    id = $(this).data('id');
                }
                // console.log( JSON.parse(tt));
                var url = "{{ route('ajax-import-floor.error.inputs') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        datatoSave: datatoSave,
                        id: id,
                        value: value,
                        field: field,
                        inputtype: inputtype,
                        updateValue: false,
                        CreateErrorValue: CreateErrorValue
                    },
                    success: function(response) {
                        // console.log(response['data']);
                        if (response['status']) {
                            // console.log('insuccess');
                            el.empty();
                            el.append(response['data']);
                            el.addClass('filedrendered');
                        }
                    },
                    error: function(response) {

                    },
                });
            }
        });


        $(document.body).on('focusout', '.unit-p-text-input', function(e) {
            if (!$(this).hasClass('filedrendered')) {
                id = $(this).data('id');
                field = $(this).data('field');
                showBlockUI('#unit_p_input_div_' + field + id);
                value = $(this).data('value');
                inputtype = $(this).data('inputtype');
                el = $(this);
                console.log(el.parent)

                var url = "{{ route('ajax-import-floor.error.inputs') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        value: el.val(),
                        id: id,
                        field: field,
                        inputtype: inputtype,
                        updateValue: true,
                        updateErrorValue: true
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
                            el.removeClass('bg-danger')
                        } else {

                            toastr.error(response['message']['error']);

                        }
                        hideBlockUI('#unit_p_input_div_' + field + id);
                    },
                    error: function(response) {
                        hideBlockUI('#unit_p_input_div_' + field + id);
                    },
                });
            }
        });


        function PreviewFile() {
            if (!$('.unit_p_input_div').hasClass('bg-danger')) {
                window.location.href = "{{ route('sites.floors.storePreview', ['site_id' => $site_id]) }}"
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Resolve Conflicts to Procede to next step',
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    confirmButtonClass: 'btn-danger',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-relief-outline-warning waves-effect waves-float waves-light me-1',
                    },
                }).then((result) => {
                    if (result.isConfirmed) {

                    }
                });

            }

        }
    </script>
@endsection
