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
                                Import Floor File
                            </button>

                            <a href="#"
                                class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                <i data-feather='x'></i>
                                {{ __('lang.commons.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 position-relative">


                </div>
            </div>

        </form>
    @else
        {{-- <form action="{{ route('sites.floors.destroy-selected', ['site_id' => $site_id]) }}" id="floors-table-form"
            method="get">
            <div class="table-responsive">
                <table class="dt-complex-header table table-striped table-hover" id="table">
                    <thead>
                        <tr class="text-center">
                            <th rowspan="2">FLOORS</th>
                            <th rowspan="2">AREA</th>
                            <th rowspan="2">SHORT LABEL</th>
                        </tr>
                    </thead>
                    @foreach ($floors[0] as $key => $floor)
                        <tr>
                           <td>{{$floor[0]}}</td>
                           <td>{{$floor[1]}}</td>
                           <td>{{$floor[2]}}</td>

                        </tr>
                    @endforeach

                </table>
            </div>
        </form> --}}
        <table id="kt_table_1" class="table table-sm table-responsive dt-simple-header">
            <thead>
                <tr>
                    @foreach ($db_fields as $dbf)
                        <th>
                            <select class="form-control text-capitalize text-nowrap required" style="width: 230px;"
                                name="fields[]">
                                @foreach ($db_fields as $db_field)
                                    <option value="{{ $db_field }}">
                                        {{ $db_field }}</option>
                                @endforeach
                            </select>
                        </th>
                    @endforeach

                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $row)
                    <tr>
                        @foreach ($row as $key => $value)
                            <input type="hidden" value="{{ $value }}" name="values[]">
                            <td class="text-nowrap">{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
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

        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
