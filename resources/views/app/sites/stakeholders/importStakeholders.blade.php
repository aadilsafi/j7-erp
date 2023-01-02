@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.stakeholders.import', $site_id) }}
@endsection

@section('page-title', 'Import Stakeholders')

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
                <h2 class="content-header-title float-start mb-0">Import Stakeholders</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.stakeholders.import', $site_id) }}
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
                                    <td @if ($row->attribute() == $k) class="text-danger" @endif>{{ $value }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
            </div>
        @endif
        <div class="col">
            <form class="form form-vertical" id="importPreviewForm"
                action="{{ route('sites.stakeholders.importStakeholdersPreview', ['site_id' => $site_id]) }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row mt-1">
                    <div class="col position-relative">
                        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                            <div class="card-body">
                                <div class="row mb-1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                        <label class="form-label" style="font-size: 15px"
                                            for="stakeholder_as"><strong>Importing Stakeholder
                                                As</strong>
                                            <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg select2" id="stakeholder_as"
                                            name="stakeholder_as">
                                            <option value="0" selected>Select Stakeholder As</option>
                                            <option value="i" {{ old('stakeholder_as') == 'i' ? 'selected' : '' }}>
                                                Individual</option>
                                            <option value="c" {{ old('stakeholder_as') == 'c' ? 'selected' : '' }}>
                                                Company</option>
                                        </select>
                                        @error('stakeholder_as')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
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

                                <a href="{{ route('sites.stakeholders.index', ['site_id' => $site_id]) }}"
                                    class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                    <i data-feather='x'></i>
                                    {{ __('lang.commons.cancel') }}
                                </a>

                                <a href="{{ route('sites.import.sample-download', ['site_id' => $site_id, 'order' => 1]) }}"
                                    class="mt-1 btn w-100 btn-relief-outline-info waves-effect waves-float waves-light">
                                    <i data-feather='download'></i>
                                    Download Sample for Individual Stakeholders
                                </a>

                                <a href="{{ route('sites.import.sample-download', ['site_id' => $site_id, 'order' => 0]) }}"
                                    class="mt-1 btn w-100 btn-relief-outline-info waves-effect waves-float waves-light">
                                    <i data-feather='download'></i>
                                    Download Sample for Company Stakeholders
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 position-relative"></div>
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
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.select.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
@endsection


@section('custom-js')

    <script>
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
            checkValidity: false,
            acceptedFileTypes: ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            credits: {
                label: '',
                url: ''
            }
        });

        $('#kt_table_1').DataTable({
            order: [
                [0, "asc"]
            ],
            scrollX: true,
            responsive: false,
            searching: true,
            lengthMenu: [50, 100, 500],
        });
    </script>
@endsection
