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
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                {{ view('app.sites.file-managements.files.dealer-incentive.form-fields', [
                    'site_id' => $site_id,
                    'units' => $units,
                    'dealer_data' => $dealer_data,
                    'rebate_files' => $rebate_files,
                    'stakeholders' => $stakeholders,
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
                        <a href="{{ route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)]) }}"
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
                    if (response.success) {
                        $.each(response.units, function(i, item) {
                            $('.unit_id').append($('<option>', {
                                value: item.id + '_' + item.gross_area,
                                text: item.name,
                            }));
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something Went Wrong!!',
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function CalculateTotalArea(selectdUnits) {
            var selectedValues = $('#unit_id').val();
            let element = [];
            let total_area = 0.0;
            for (let index = 0; index < selectedValues.length; index++) {
                element = selectedValues[index].split("_");
                total_area = parseFloat(total_area) + parseFloat(element[1]);
            }

            $('#total_unit_area').val(total_area);

        }

        function CalculateTotalDealerIncentive() {
            var inputValue = $('#dealer_incentive').val();
            var total_unit_area = $('#total_unit_area').val();
            var total_incentive = parseFloat(inputValue) * parseFloat(total_unit_area);

            $('#total_dealer_incentive').val(total_incentive);

        }

        $('#rebate_percentage').on('change', function() {
            showBlockUI('#rebate-form');
            let rebate_percentage = parseInt($('#rebate_percentage').val());
            rebate_percentage = (rebate_percentage > 100) ? 100 : rebate_percentage;
            rebate_percentage = (rebate_percentage < 0) ? 0 : rebate_percentage;

            let unit_total = parseFloat($('#unit_total').val());
            let rebate_value = parseFloat((rebate_percentage * unit_total) / 100);

            $('#td_rebate').html(rebate_percentage + '%');

            $('#td_rebate_value').html(rebate_value.toLocaleString());

            $('#rebate_total').val(rebate_value);
            $('.hideDiv').css("display", "block");
            hideBlockUI('#rebate-form');
        });

        var e = $("#dealer");
        e.wrap('<div class="position-relative"></div>');
        e.select2({
            dropdownAutoWidth: !0,
            dropdownParent: e.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).on("change", function(e) {
            let dealer = parseInt($(this).val());
            showBlockUI('#stakeholders_card');
            let stakeholderData = {
                id: 0,
                full_name: '',
                father_name: '',
                occupation: '',
                designation: '',
                cnic: '',
                ntn: '',
                contact: '',
                address: '',
            };

            $.ajax({
                url: "{{ route('sites.stakeholders.ajax-get-by-id', ['site_id' => encryptParams($site_id), 'id' => ':id']) }}"
                    .replace(':id', dealer),
                type: 'GET',
                data: {},
                success: function(response) {

                    if (response.status) {
                        if (response.data) {
                            stakeholderData = response.data;
                        }
                        // $('#stackholder_id').val(stakeholderData.id);
                        $('#stackholder_full_name').val(stakeholderData.full_name).attr('disabled', (
                            stakeholderData.full_name.length > 0));
                        $('#stackholder_father_name').val(stakeholderData.father_name).attr('disabled',
                            (stakeholderData.father_name.length > 0));
                        $('#stackholder_occupation').val(stakeholderData.occupation).attr('disabled', (
                            stakeholderData.occupation.length > 0));
                        $('#stackholder_designation').val(stakeholderData.designation).attr('disabled',
                            (stakeholderData.designation.length > 0));

                        $('#stackholder_cnic').val(format('XXXXX-XXXXXXX-X', stakeholderData.cnic))
                            .attr('disabled', (stakeholderData.cnic.length > 0));
                        $('#stackholder_contact').val(stakeholderData.contact).attr('disabled', (
                            stakeholderData.contact.length > 0));
                        $('#stackholder_ntn').val(stakeholderData.ntn).attr('disabled', (stakeholderData
                            .ntn.length > 0));
                        $('#stackholder_comments').val(stakeholderData.comments).attr('disabled', (
                            stakeholderData.comments.length > 0));
                        $('#stackholder_address').text(stakeholderData.address).attr('disabled', (
                            stakeholderData.address.length > 0));
                    }
                    hideBlockUI('#stakeholders_card');
                },
                error: function(errors) {
                    console.error(errors);
                    hideBlockUI('#stakeholders_card');
                }
            });
            // if (dealer === "0") {
            //     $('#div_new_dealer').show();
            // } else {
            //     $('#div_new_dealer').hide();
            // }
        });

        var validator = $("#rebateForm").validate({
            rules: {
                'rebate_percentage': {
                    required: true,
                    digits: true,
                },
                'dealer[full_name]': {
                    required: true
                },
                'dealer[father_name]': {
                    required: true
                },
                'dealer[occupation]': {
                    required: true
                },
                'dealer[designation]': {
                    required: true
                },
                'dealer[contact]': {
                    required: true,
                    digits: true,
                },
                'dealer[cnic]': {
                    required: true,
                    digits: true,
                    maxlength: 13,
                    minlength: 13
                },
                'dealer[ntn]': {
                    required: true,
                },
                'dealer[address]': {
                    required: true,
                },
                'deal_type': {
                    required: true,
                },

            },
            messages: {
                'dealer[cnic]': {
                    maxlength: "Cnic can't be greater then {0} digits without dashes",
                    minlength: "Cnic can't be less then {0} digits without dashes",
                }
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
