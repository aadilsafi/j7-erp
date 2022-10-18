@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.rebate-incentive.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Rebate Incentive')

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
                <h2 class="content-header-title float-start mb-0">Create Rebate Incentive</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.rebate-incentive.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="rebateForm"
        action="{{ route('sites.file-managements.rebate-incentive.store', ['site_id' => encryptParams($site_id)]) }}"
        method="post" class=" ">
        @csrf

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                {{ view('app.sites.file-managements.files.rebate-incentive.form-fields', [
                    'site_id' => $site_id,
                    'units' => $units,
                    'dealer_data' => $dealer_data,
                    'rebate_files' => $rebate_files,
                    'customFields' => $customFields

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
        function getData(unit_id) {
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.file-managements.rebate-incentive.ajax-get-data', ['site_id' => encryptParams($site_id)]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'unit_id': unit_id,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        $('#sales_source_lead_source').val(response.leadSource.name);
                        $('#stakeholder_id').val(response.stakeholder.id);
                        $('#customer_name').val(response.stakeholder.full_name);
                        $('#customer_father_name').val(response.stakeholder.father_name);
                        $('#customer_cnic').val(response.cnic);
                        $('#customer_ntn').val(response.stakeholder.ntn);
                        $('#customer_comments').val(response.stakeholder.comments);
                        $('#customer_address').val(response.stakeholder.address);
                        $('#customer_phone').val(response.stakeholder.contact);
                        $('#customer_occupation').val(response.stakeholder.occupation);

                        $('#td_unit_id').html(response.unit.unit_number);
                        $('#td_unit_area').html(response.unit.gross_area);
                        $('#td_unit_rate').html(response.unit.price_sqft.toLocaleString());
                        $('#td_unit_floor').html(response.floor);

                        if (response.facing != null) {
                            $('#td_unit_facing_charges').html(response.facing.unit_percentage + '%');
                        } else {
                            $('#td_unit_facing_charges').html(0 + '%');
                        }

                        let unit_total = response.unit.price_sqft * response.unit.gross_area;
                        $('#unit_total').val(unit_total)

                        $('#td_unit_discount').html(response.salesPlan.discount_percentage + '%');
                        $('#td_unit_total').html(unit_total.toLocaleString());
                        $('#td_unit_downpayment').html(response.salesPlan.down_payment_percentage + '%');

                        if (response.facing != null) {
                            let facing_value = response.salesPlan.discount_percentage * response.salesPlan
                                .total_price;
                            $('#td_unit_facing_charges_value').html(facing_value.toLocaleString())
                        } else {
                            $('#td_unit_facing_charges_value').html(0);
                        }

                        $('#td_unit_discount_value').html(parseFloat(response.salesPlan.discount_total).toLocaleString());
                        $('#td_unit_total_value').html(parseFloat(response.salesPlan.total_price).toLocaleString());
                        $('#td_unit_downpayment_value').html(parseFloat(response.salesPlan.down_payment_total).toLocaleString());


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
                        $('#stackholder_full_name').val(stakeholderData.full_name).attr('disabled', (stakeholderData.full_name.length > 0));
                        $('#stackholder_father_name').val(stakeholderData.father_name).attr('disabled', (stakeholderData.father_name.length > 0));
                        $('#stackholder_occupation').val(stakeholderData.occupation).attr('disabled', (stakeholderData.occupation.length > 0));
                        $('#stackholder_designation').val(stakeholderData.designation).attr('disabled', (stakeholderData.designation.length > 0));

                        $('#stackholder_cnic').val(format('XXXXX-XXXXXXX-X', stakeholderData.cnic)).attr('disabled', (stakeholderData.cnic.length > 0));
                        $('#stackholder_contact').val(stakeholderData.contact).attr('disabled', (stakeholderData.contact.length > 0));
                        $('#stackholder_ntn').val(stakeholderData.ntn).attr('disabled', (stakeholderData.ntn.length > 0));
                        if((stakeholderData.comments != null)){
                            $('#stackholder_comments').val(stakeholderData.comments).attr('disabled', (stakeholderData.comments.length >= 0));
                        }
                        $('#stackholder_address').text(stakeholderData.address).attr('disabled', (stakeholderData.address.length > 0));
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
                'rebate_percentage' : {
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
