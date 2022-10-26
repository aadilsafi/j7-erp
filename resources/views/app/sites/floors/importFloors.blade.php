@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.create', encryptParams($site_id)) }}
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
                    {{ Breadcrumbs::render('sites.floors.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    @if (!$preview)
        <form class="form form-vertical"
            action="{{ route('sites.floors.importFloorsPreview', ['site_id' => encryptParams($site_id)]) }}"
            enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
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

                            <a href="#"
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
    @else
        <div class="row">
            <div class="col">
                <form class="form form-vertical"
                    action="{{ route('sites.floors.storePreview', ['site_id' => encryptParams($site_id)]) }}"
                    method="POST">
                    @csrf
                    <table id="kt_table_1" class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                @foreach ($data[0] as $key => $value)
                                    <th>
                                        <select class="form-control text-capitalize text-nowrap required"
                                            style="width: 230px;" name="fields[{{ $key }}]">
                                            <option value="">...No match,select a field...</option>
                                            @foreach ($db_fields as $k => $db_field)
                                                @if ($db_field == 'name' || $db_field == 'floor_area' || $db_field == 'short_label')
                                                    <option class="text-danger" value="{{ $db_field }}"
                                                        @if ($key == $db_field) selected @endif>
                                                        {{ $db_field }}<span class="text-danger">*</span></option>
                                                @else
                                                    <option value="{{ $db_field }}"
                                                        @if ($key == $db_field) selected @endif>
                                                        {{ $db_field }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $row)
                                <tr>
                                    @foreach ($row as $k => $value)
                                        <td class="text-nowrap">{{ $value }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <a href="#"
                                class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                <i data-feather='x'></i>
                                {{ __('lang.commons.cancel') }}
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <button type="submit" value="save"
                                class="btn btn-md w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                                <i data-feather='save'></i>
                                Save Import File
                            </button>
                        </div>

                    </div>
                    <input type="hidden" name="file_path" value="{{$file_path}}"> 
                    @foreach ($data as $key => $row)
                        @foreach ($row as $k => $value)
                            <input type="hidden" value="{{ $value }}"
                                name="values[{{ $key }}][{{ $db_fields[$loop->index] }}]">
                        @endforeach
                    @endforeach
                </form>
            </div>
        </div>

    @endif
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

        });

      
    </script>
@endsection
