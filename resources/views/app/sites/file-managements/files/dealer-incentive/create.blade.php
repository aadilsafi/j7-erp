@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.dealer-incentive.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Dealer Incentive')

@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/forms/wizard/bs-stepper.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-wizard.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
    <style>
        .hideDiv {
            display: none;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Dealer Incentive</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.dealer-incentive.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="rebateForm"
        action="{{ route('sites.file-managements.dealer-incentive.store', ['site_id' => encryptParams($site_id)]) }}"
        method="post" class=" ">
        @csrf

        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-12 position-relative">
                {{ view('app.sites.file-managements.files.dealer-incentive.form-fields', [
                    'site_id' => $site_id,
                    'units' => $units,
                    'dealer_data' => $dealer_data,
                    'rebate_files' => $rebate_files,
                    'stakeholders' => $stakeholders,
                    'incentives' => $incentives,
                    'customFields' => $customFields,
                ]) }}
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">

                        <a id="saveButton" href="#"
                            class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                            <i data-feather='save'></i>
                            Save
                        </a>
                        <a href="{{ route('sites.file-managements.dealer-incentive.index', ['site_id' => encryptParams($site_id)]) }}"
                            class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                            <i data-feather='x'></i>
                            {{ __('lang.commons.cancel') }}
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
@endsection

@section('custom-js')
    <script type="text/javascript">
        function getData(dealer_id) {
            if (dealer_id != 0) {
                var _token = '{{ csrf_token() }}';
                let url =
                    "{{ route('sites.file-managements.dealer-incentive.ajax-get-data', ['site_id' => encryptParams($site_id)]) }}";
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'dealer_id': dealer_id,
                        '_token': _token
                    },
                    success: function(response) {
                        showBlockUI('#loader');
                        if (response.success) {
                            $('.hideDiv').css("display", "block");
                            // $.each(response.units, function(i, item) {
                            //     // $('.unit_id').append($('<option>', {
                            //     //     value: item.id + '_' + item.gross_area,
                            //     //     text: item.name,
                            //     // }));

                            //     $('#dynamic_unit_rows').append('<tr>',
                            //         '<td class="checkedInput"><input type="checkbox" ></td>',
                            //         '<td>'+item.name+'</td>',
                            //         '<td>'+item.floor_unit_number+'</td>',
                            //         '<td>'+item.gross_area.toLocaleString()+'</td>',
                            //         '<td>'+item.price_sqft.toLocaleString()+'</td></tr>',
                            //     );

                            // });
                            $('#dynamic_unit_rows').empty();
                            for (var i = 0; i <= response.units.length; i++) {
                                if (response.units[i] != null) {
                                    $('#dynamic_unit_rows').append(
                                        '<tr class="text-nowrap text-center">',
                                            '<td class="text-nowrap text-center">' + (i + 1) + '</td>',
                                        '<td class="text-nowrap text-center"><div class="d-flex flex-column"><div class="form-check form-switch form-check-primary"><input type="checkbox" class="form-check-input form-switch" onchange="CalculateTotalArea()" name="unit_ids[]" area="' +
                                        response.units[i]['gross_area'] + '" value="' +
                                         response.units[i]['id'] +
                                          '" id="unit_'+ response.units[i]['id'] +'" /><label class="form-check-label" for="unit_'+
                                           response.units[i]['id'] +'"><span class="switch-icon-left"><i data-feather="check"></i></span><span class="switch-icon-right"><i data-feather="x"></i></span></label></div></div></td>',
                                        '<td class="text-nowrap text-center">' + response
                                        .units[i]['name'] + '</td>',
                                        // '<td class="text-nowrap text-center">'+response.total_calculated_installments[i]['date']+'</td>',
                                        '<td class="text-nowrap text-center">' + response
                                        .units[i]['floor_unit_number'] + '</td>',
                                        '<td class="text-nowrap text-center">' + response
                                        .units[i]['gross_area'].toLocaleString('en') +
                                        '</td>',
                                        // '<td class="text-nowrap text-center">' + response
                                        // .units[i]['price_sqft'].toLocaleString('en') +
                                        // '</td>',
                                        '</tr>', );
                                }
                            }

                            $('#dynamic_paid_unit_rows').empty();
                            $('#dynamic_paid_unit_rows').html(response.paidTable)
                            hideBlockUI('#loader');
                        } else {
                            hideBlockUI('#loader');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something Went Wrong!!',
                            });
                        }
                    },
                    error: function(error) {
                        hideBlockUI('#loader');
                        console.log(error);
                    }
                });
            }

        }


        function CalculateTotalArea(e) {
            $('.hideDiv').css("display", "block");
            showBlockUI('#loader');
            // var selectedValues = $('#unit_id').val();
            let element = [];
            let ids = [];
            let total_area = 0.0;
            // for (let index = 0; index < selectedValues.length; index++) {
            //     element = selectedValues[index].split("_");
            //     total_area = parseFloat(total_area) + parseFloat(element[1]);
            // }

            $("input:checkbox[name='unit_ids\\[\\]']:checked").each(function() {
                element.push($(this).attr('area'));
            });

            $.each(element, function() {
                total_area += parseFloat(this) || 0;
            });

            $('#total_unit_area').val(total_area.toLocaleString());

            var inputValue = $('#dealer_incentive').val();
            // var total_incentive = parseFloat(inputValue) * parseFloat(total_area);

            // $('#total_dealer_incentive').val(total_incentive);
            hideBlockUI('#loader');
        }

        function CalculateTotalDealerIncentive() {
            $('.hideDiv').css("display", "block");
            showBlockUI('#loader');
            var inputValue = $('#dealer_incentive').val();
            var total_unit_area = $('#total_unit_area').val();
            var total_incentive = parseFloat(inputValue) * parseFloat(total_unit_area);

            $('#total_dealer_incentive').val(total_incentive.toLocaleString());
            hideBlockUI('#loader');
        }

        var validator = $("#rebateForm").validate({
            rules: {
                'dealer_id': {
                    required: true,
                },
                'dealer_incentive': {
                    required: true,
                    digits: true
                },
                'total_unit_area': {
                    required: true
                },
                'total_dealer_incentive': {
                    required: true
                },
            },
            errorClass: 'is-invalid text-danger',
            errorElement: "span",
            wrapper: "div",
            submitHandler: function(form) {
                form.submit();
            }
        });



        $("#saveButton").click(function() {
            $("#rebateForm").submit();
        });
    </script>
@endsection
