@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.index') }}
@endsection

@section('page-title', 'Floors Preview')

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
                <h2 class="content-header-title float-start mb-0">Floors Preview</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.preview') }}
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
            <div class="accordion accordion-margin" id="accordionMargin">
                @foreach($floors as $key => $value)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#accordianFloor{{ $value->id }}"
                            aria-expanded="false"
                            aria-controls="accordianFloor{{ $value->id }}"
                        >
                            Floor {{ $value->id }}
                        </button>
                        </h2>
                        <div
                        id="accordianFloor{{ $value->id }}"
                        class="accordion-collapse collapse"
                        data-bs-parent="#accordionMargin"
                        >
                        <div class="accordion-body">
                            <div class="table-responsive">

                                <table
                                    class="table table-light table-striped table_style floor-preview-datatable-{{ $value->id }} data-table "
                                    id="dataTables">
                                    <thead>
                                        <tr class="text-center">
                                            <td>UNITS</td>
                                            <td>Type</td>
                                            <td>Status</td>
                                            <td>Width</td>
                                            <td>Length</td>
                                            <td>Net Area</td>
                                            <td>Gross Area</td>
                                            <td>Price Sqft</td>
                                            <td>Corner</td>
                                            <td>Facing</td>
                                            <td>Created at</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        </div>
                    </div>
                @endforeach


              </div>
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
    <script>
        $(document).ready(function(){
            $(function () {
                $.ajax({
                    type: "GET",
                    url: "{{ route('floors.pending.get') }}",
                    success: function (res) {
                        {{--  console.log(res);  --}}

                        res.forEach(element => {
                            console.log(element.id);
                            console.log(element.site_id);
                            loadFloorPreviewDatatable(element.id,element.site_id);
                        });
                    }
                });
            });

            function loadFloorPreviewDatatable(id,site_id) {

                var table = $('.floor-preview-datatable-' + id).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('sites.floors.preview',['id'=>':id','site_id'=>':site_id']) }}'.replace(':id',id).replace(':site_id',site_id),
                        data: { id: id },
                    },
                    columns: [


                        {
                            data: 'name',
                            name: 'name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'type_id',
                            name: 'type_id',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'status_id',
                            name: 'status_id',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'width',
                            name: 'width',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'length',
                            name: 'length',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'net_area',
                            name: 'net_area',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'gross_area',
                            name: 'gross_area',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'price_sqft',
                            name: 'price_sqft',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'is_corner',
                            name: 'is_corner',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'is_facing',
                            name: 'is_facing',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            orderable: true,
                            searchable: true
                        },

                    ]
                });
            };

        });


        $(document).on('focusout', '.unit-p-text-input', function (e) {

            field = $(this).data('field');
            value = $(this).val();
            id = $(this).data('id');
            el = $(this);
            if (value != '') {
                showBlockUI('#unit_p_input_div_'+field+id);
                updateUnitField(id,value,field);
                {{--  hideBlockUI('#unit_p_input_div_'+field+id);  --}}
                parent = el.parent();
                parent.empty();
                parent.append('<span>'+value+'</span>');
                parent.data('value', value);
                parent.removeClass('filedrendered');
            }
        });

        $(document).on('change', '.unit-p-type-select', function(e){

            value = $(this).val();
            selected = $(this).find('option:selected');
            selectedText = selected.text();
            splitedText = selectedText.split(' - ');
            id = $(this).data('id');
            field = $(this).data('field');
            el = $(this);
            if (value != '') {
                showBlockUI('#unit_p_input_div_'+field+id);
                updateUnitField(id,value,field);
                {{--  hideBlockUI('#unit_p_input_div_'+field+id);  --}}
                parent = el.parent();
                if(field == 'facing_id'){
                    boxel = parent.children('.unit-p-checkbox');
                    parent.empty();
                    parent.append(boxel);
                }
                else{
                    parent.empty();
                }

                parent.append('<span>'+splitedText[1]+'</span>');
                parent.data('value', splitedText[1]);
                parent.removeClass('filedrendered');
            }

        });

        $(document).on('change', '.unit-p-checkbox', function(e){

            value = 1;
            id = $(this).data('id');
            field = $(this).data('field');
            showBlockUI('#unit_p_input_div_'+field+id);
            updateUnitField(id,value,field,$(this));
        });

        function updateUnitField(id, value, field, element = null) {
            var url = "{{ route('unit.name.update') }}";
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
                                url: "{{ route('facing.field.draw') }}",
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

        $(document).on('click', '.unit_p_input_div', function(e){
            if(!$(this).hasClass('filedrendered')){
                id = $(this).data('id');
                field = $(this).data('field');
                showBlockUI('#unit_p_input_div_'+field+id);
                value = $(this).data('value');
                inputtype = $(this).data('inputtype');
                el = $(this);

                var url = "{{ route('unit.get.input') }}";
                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {value:value,
                            id:id,
                            field:field,
                            inputtype:inputtype
                        },
                        success: function (response) {
                            console.log(response['data']);
                            if(response['status']){
                                console.log('insuccess');
                                el.empty();
                                el.append(response['data']);
                                el.addClass('filedrendered');
                                $(".unit-p-type-select").select2();
                            }
                            hideBlockUI('#unit_p_input_div_'+field+id);
                        },
                        error: function (response) {
                            hideBlockUI('#unit_p_input_div_'+field+id);
                        },
                    });
                }
        });

    </script>
@endsection
