@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.file-resale.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create File Title Transfer')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
    <style>
        .filepond--drop-label {
            color: #7367F0 !important;
        }

        .filepond--item-panel {
            background-color: #7367F0;
        }

        .filepond--panel-root {
            background-color: #e3e0fd;
        }
        #stakeholderNextOfKin {
            display: none;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create File Title Transfer</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.file-title-transfer.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="fileRefundForm" enctype="multipart/form-data"
        action="{{ route('sites.file-managements.file-title-transfer.store', ['site_id' => encryptParams($site_id)]) }}"
        method="post" class="">
        @csrf
        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-12 position-relative">
                {{ view('app.sites.file-managements.files.files-actions.file-title-transfer.form-fields', [
                    'site_id' => $site_id,
                    'unit' => $unit,
                    'customer' => $customer,
                    'file' => $file,
                    'stakeholders' => $stakeholders,
                    'stakeholderTypes' => $stakeholderTypes,
                    'emptyRecord' => $emptyRecord,
                    'rebate_incentive' => $rebate_incentive,
                    'total_paid_amount' => $total_paid_amount,
                    'rebate_total' => $rebate_total,
                    'salesPlan' => $salesPlan,
                    'customFields' => $customFields,
                ]) }}
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">
                        <input type="hidden" name="file_id" value="{{ $file->id }}">
                        <div class="d-block mb-1">
                            <div class="form-check form-check-primary">
                                <input type="checkbox" checked name="checkAttachment" class="form-check-input"
                                    value="1" id="colorCheck3">
                                <label class="form-check-label" for="colorCheck3">
                                    Attachment Attached
                                </label>
                            </div>
                        </div>

                        <a id="saveButton" href="#"
                            class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                            <i data-feather='save'></i>
                            Save File Title Transfer
                        </a>

                        <a href="{{ route('sites.file-managements.file-title-transfer.index', ['site_id' => encryptParams($site_id)]) }}"
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
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment-range.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
@endsection

@section('custom-js')

    <script>
        // FilePond.registerPlugin(
        //     FilePondPluginImagePreview,
        //     FilePondPluginFileValidateType,
        //     FilePondPluginFileValidateSize,
        //     FilePondPluginImageValidateSize,
        //     FilePondPluginImageCrop,
        // );

        // FilePond.create(document.getElementById('attachment'), {
        //     styleButtonRemoveItemPosition: 'right',
        //     imageCropAspectRatio: '1:1',
        //     acceptedFileTypes: ['image/png', 'image/jpeg'],
        //     maxFileSize: '1536KB',
        //     ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
        //     storeAsFile: true,
        //     allowMultiple: true,
        //     maxFiles: 1,
        //     checkValidity: true,
        //     credits: {
        //         label: '',
        //         url: ''
        //     }
        // });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#payment_due_date").flatpickr({
                defaultDate: "today",
                minDate: '{{ $salesPlan->approved_date }}',
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            var e = $("#stackholders");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).on("change", function(e) {

                showBlockUI('#stakeholders_card');

                let stakeholder_id = $(this).val();

                let stakeholderData = {
                    id: 0,
                    full_name: '',
                    father_name: '',
                    occupation: '',
                    designation: '',
                    cnic: '',
                    contact: '',
                    address: '',
                }
                let div_stakeholder_type = $('#div_stakeholder_type');
                div_stakeholder_type.hide();
                $.ajax({
                    url: "{{ route('sites.stakeholders.ajax-get-by-id', ['site_id' => encryptParams($site_id), 'id' => ':id']) }}"
                        .replace(':id', stakeholder_id),
                    type: 'GET',
                    data: {},
                    success: function(response) {

                        if (response.status) {
                            if (response.data) {
                                stakeholderData = response.data[0];
                            }

                            $('#stackholder_next_of_kin').empty();
                            if (response.data[1].length > 0) {
                                $('#stakeholderNextOfKin').show();
                                $.each(response.data[1], function(i, item) {

                                    $('#stackholder_next_of_kin').append($('<option>', {
                                        value: item.id,
                                        text: item.full_name + ' s/o ' +
                                            item.father_name + ' ,' + item
                                            .cnic,
                                    }));

                                });
                            } else {
                                $('#stakeholderNextOfKin').hide();
                            }

                            $('#stackholder_full_name').val(stakeholderData.full_name).attr(
                                'readonly', (stakeholderData.full_name.length > 0));
                            $('#stackholder_father_name').val(stakeholderData.father_name).attr(
                                'readonly', (stakeholderData.father_name.length > 0));
                            $('#stackholder_occupation').val(stakeholderData.occupation).attr(
                                'readonly', (stakeholderData.occupation.length > 0));
                            $('#stackholder_designation').val(stakeholderData.designation).attr(
                                'readonly', (stakeholderData.designation.length > 0));
                            $('#stackholder_cnic').val(stakeholderData.cnic).attr('readonly', (
                                stakeholderData.cnic.length > 0));
                            $('#stackholder_contact').val(stakeholderData.contact).attr(
                                'readonly', (stakeholderData.contact.length > 0));
                            $('#stackholder_address').text(stakeholderData.address).attr(
                                'readonly', (stakeholderData.address.length > 0));
                            $('#stackholder_ntn').val(stakeholderData.ntn).attr('readonly', (
                                stakeholderData.ntn.length > 0));
                            hideBlockUI('#stakeholders_card');
                            $('#stackholder_comments').text(stakeholderData.comments).attr(
                                'readonly', (stakeholderData.comments.length > 0));




                            let stakeholderType = '';
                            (stakeholderData.stakeholder_types).forEach(types => {
                                if (types.status) {
                                    stakeholderType +=
                                        '<p class="badge badge-light-success fs-5 ms-auto me-1">' +
                                        types.stakeholder_code + '</p>';
                                } else {
                                    stakeholderType +=
                                        '<p class="badge badge-light-danger fs-5 ms-auto me-1">' +
                                        types.stakeholder_code + '</p>';
                                }
                            });

                            div_stakeholder_type.html(stakeholderType);
                            div_stakeholder_type.show();
                        }

                    },
                    error: function(errors) {
                        console.error(errors);
                        hideBlockUI('#stakeholders_card');
                    }
                });
            });
        });

        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        $(".expenses-list").repeater({
            initEmpty: true,
            show: function(e) {
                $(this).slideDown();
                feather && feather.replace({
                    width: 14,
                    height: 14
                });
                initializeFilePond();
                // console.log(e);
            },
            hide: function(e) {
                $(this).slideUp(e)
            }
        });
        // const input = $('.attachment');
        // $('#add-new-attachment').on('click', function() {
        //     initializeFilePond();
        // });

        function initializeFilePond() {
            const inputElements = document.querySelectorAll('input.filepond');
            console.log(inputElements.length);
            Array.from(inputElements).forEach(inputElement => {

                // create a FilePond instance at the input element location
                FilePond.create(inputElement, {
                    styleButtonRemoveItemPosition: 'right',
                    imageCropAspectRatio: '1:1',
                    acceptedFileTypes: ['image/png', 'image/jpeg'],
                    maxFileSize: '1536KB',
                    ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
                    storeAsFile: true,
                    allowMultiple: true,
                    maxFiles: 1,
                    checkValidity: true,
                    credits: {
                        label: '',
                        url: ''
                    }
                });
            });
        }

        var checkbtn = $('#colorCheck3').is(':checked')
        formValidations();

        $('#colorCheck3').change(function() {
            checkbtn = $('#colorCheck3').is(':checked');
            formValidations();
        })

        function formValidations() {
            var validator = $("#fileRefundForm").validate({
                rules: {
                    'transfer_rate': {
                        required: true,
                        digits: true,
                    },
                    'payment_due_date': {
                        required: true
                    },
                    'amount_to_be_paid': {
                        required: true,

                    },
                    'amount_remarks': {
                        required: true
                    },
                    'attachments[0][attachment_label]': {
                        required: function() {
                            return checkbtn;
                        }
                    },
                    'attachment[0][image]': {
                        required: function() {
                            return checkbtn;
                        }
                    },
                    'stackholder[full_name]': {
                        required: true
                    },
                    'stackholder[father_name]': {
                        required: true
                    },

                    'stackholder[cnic]': {
                        required: true,
                        // digits: true,
                        // maxlength: 13,
                        // minlength: 13
                    },
                    'stackholder[contact]': {
                        required: true
                    },
                    'stackholder[address]': {
                        required: true
                    },
                },
                // messages: {
                //     'stackholder[cnic]': {
                //         maxlength: "Cnic can't be greater then {0} digits without dashes",
                //         minlength: "Cnic can't be less then {0} digits without dashes",
                //     }
                // },
                errorClass: 'is-invalid text-danger',
                errorElement: "span",
                wrapper: "div",
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }

        function calculateTransferAmount() {
            let paid_amount = '{{ $total_paid_amount }}';
            let transfer_rate = $('#transfer_rate').val();
            let unit_gross_area = '{{ $unit->gross_area }}';

            let amount_paid = 0.0;
            let profitCharges = $('#profit_charges').val();
            amount_paid = parseFloat(transfer_rate) * parseFloat(unit_gross_area);
            $('#amount_to_be_paid').val(amount_paid.toLocaleString());
        }

        $("#saveButton").click(function() {
            $("#fileRefundForm").removeClass('is-invalid text-danger')
            $("#fileRefundForm").submit();
        });
    </script>
@endsection
