@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.preview', encryptParams($site->id), encryptParams($floor->id)) }}
@endsection

@section('page-title', 'Units Preview')

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
    <style>
        .unit_p_input_div:hover {
            border: 1px solid rgb(154 152 152 / 30%);
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Units ({{ $floor->name }})</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.preview', encryptParams($site->id), encryptParams($floor->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <p class="mb-2">
    </p>

    <div class="card">
        <div class="card-body">
            {{ $dataTable->table() }}
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
        $(document).on('focusout', '.unit-p-text-input', function(e) {

            field = $(this).data('field');
            value = $(this).val();
            id = $(this).data('id');
            el = $(this);
            if (value != '') {
                showBlockUI('#unit_p_input_div_' + field + id);
                updateUnitField(id, value, field);
                {{-- hideBlockUI('#unit_p_input_div_'+field+id); --}}
                parent = el.parent();
                parent.empty();
                parent.append('<span>' + value + '</span>');
                parent.data('value', value);
                parent.removeClass('filedrendered');
            }
        });

        $(document).on('change', '.unit-p-type-select', function(e) {

            value = $(this).val();
            selected = $(this).find('option:selected');
            selectedText = selected.text();
            splitedText = selectedText.split(' - ');
            id = $(this).data('id');
            field = $(this).data('field');
            el = $(this);
            if (value != '') {
                showBlockUI('#unit_p_input_div_' + field + id);
                updateUnitField(id, value, field);
                {{-- hideBlockUI('#unit_p_input_div_'+field+id); --}}
                parent = el.parent();
                if (field == 'facing_id') {
                    boxel = parent.children('.unit-p-checkbox');
                    parent.empty();
                    parent.append(boxel);
                } else {
                    parent.empty();
                }

                parent.append('<span>' + splitedText[1] + '</span>');
                parent.data('value', splitedText[1]);
                parent.removeClass('filedrendered');
            }

        });

        $(document).on('change', '.unit-p-checkbox', function(e) {

            value = 1;
            id = $(this).data('id');
            field = $(this).data('field');
            showBlockUI('#unit_p_input_div_' + field + id);
            updateUnitField(id, value, field, $(this));
        });

        function updateUnitField(id, value, field, element = null) {
            var url = "{{ route('ajax-unit.name.update') }}";
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-right"
            };

            var data = {
                value: value,
                id: id,
                field: field,
                'fieldsData' : {}
            };

            data['fieldsData'][field] = value;

            $.ajax({
                url: url,
                type: 'GET',
                data: data,
                success: function(response) {
                    if(response['status']){
                        toastr.success(response['message']);
                        if (response['data']['facing'] == 'yes' || response['data']['facing'] == 'no') {
                            $.ajax({
                                url: "{{ route('ajax-facing.field.draw') }}",
                                type: 'GET',
                                data: {
                                    id: id,
                                },
                                success: function(response) {
                                    parent = element.parent().parent();
                                    parent.empty();
                                    parent.append(response['data']);
                                    hideBlockUI('#unit_p_input_div_' + field + id);
                                },
                                error: function(response) {
                                    toastr.error('Failed');
                                    hideBlockUI('#unit_p_input_div_' + field + id);
                                }
                            });

                        }
                        hideBlockUI('#unit_p_input_div_' + field + id);
                    }
                    else{
                        toastr.error(response['message'][field]);
                    }


                },
                error: function(response) {
                    console.log('response');
                    toastr.error('Failed');
                    hideBlockUI('#unit_p_input_div_' + field + id);
                }
            });
        }

        $(document).on('click', '.unit_p_input_div', function(e) {
            if (!$(this).hasClass('filedrendered')) {
                id = $(this).data('id');
                field = $(this).data('field');
                showBlockUI('#unit_p_input_div_' + field + id);
                value = $(this).data('value');
                inputtype = $(this).data('inputtype');
                el = $(this);

                var url = "{{ route('ajax-unit.get.input') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        value: value,
                        id: id,
                        field: field,
                        inputtype: inputtype
                    },
                    success: function(response) {
                        console.log(response['data']);
                        if (response['status']) {
                            console.log('insuccess');
                            el.empty();
                            el.append(response['data']);
                            el.addClass('filedrendered');
                            $(".unit-p-type-select").select2();
                        }
                        hideBlockUI('#unit_p_input_div_' + field + id);
                    },
                    error: function(response) {
                        hideBlockUI('#unit_p_input_div_' + field + id);
                    },
                });
            }
        });

        function saveUnits(){
            location.href = '{{ route('sites.floors.units.changes.save',['site_id'=>':site_id','floor_id'=>':floor_id']) }}'.replace(':site_id',"{{ encryptParams($site->id) }}").replace(':floor_id',"{{ encryptParams($floor->id) }}");
        }

    </script>
@endsection
