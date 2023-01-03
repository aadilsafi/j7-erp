<script>
    $(document).ready(function() {

        $('#nationality').val(167).change();
        @php
            $data = old();
        @endphp

        @if (!is_null(old('stakeholder_type')))
            $('#stakeholderType').trigger('change');
        @endif

        @if (!is_null(old('residential.country')))
            $('#residential_country').val({{ old('residential.country') }});
            $('#residential_country').trigger('change')
        @endif

        @if (!is_null(old('mailing.country')))
            $('#mailing_country').val({{ old('mailing.country') }});
            $('#mailing_country').trigger('change')
        @endif

        var stakeholder_type = $("#stakeholder_type");
        stakeholder_type.wrap('<div class="position-relative"></div>');
        stakeholder_type.select2({
            dropdownAutoWidth: !0,
            dropdownParent: stakeholder_type.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            showBlockUI('#stakeholder_as');

            if ($(this).val() == 'L') {
                $('.showRequired').hide();
            }  else {
                $('.showRequired').show();

            }
            hideBlockUI('#stakeholder_as');
        });

        var stakeholder_as = $("#stakeholder_as");
        stakeholder_as.wrap('<div class="position-relative"></div>');
        stakeholder_as.select2({
            dropdownAutoWidth: !0,
            dropdownParent: stakeholder_as.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            showBlockUI('#stakeholder_as');

            if ($(this).val() == 0) {
                $('#stakeholderType').hide();
                $('#companyForm').hide();
                $('#individualForm').hide();
                $('#common_form').hide();
            } else if ($(this).val() == 'c') {
                $('#stakeholderType').show();
                $('#companyForm').show();
                $('#individualForm').hide();
                $('#common_form').show();
                $('#change_residential_txt').html('<u>Billing Address</u>')
                $('#change_mailing_txt').html('<u>Shipping Address</u>')
                $('#change_mailing_btn').html('Same as Billing Address')
            } else if ($(this).val() == 'i') {
                $('#stakeholderType').show();
                $('#companyForm').hide();
                $('#individualForm').show();
                $('#common_form').show();
                $('#change_residential_txt').html('<u>Residential Address</u>')
                $('#change_mailing_txt').html('<u>Mailing Address</u>')
                $('#change_mailing_btn').html('Same as Residential Address')
            }
            hideBlockUI('#stakeholder_as');
        });

        stakeholder_as.trigger('change');
        // Individual Contact no fields
        var mobileContact = document.querySelector("#mobile_contact");
        intlMobileContact = window.intlTelInput(mobileContact, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));

        $('#mobileContactCountryDetails').val(JSON.stringify(intlMobileContact.getSelectedCountryData()));

        mobileContact.addEventListener("countrychange", function() {
            $('#mobileContactCountryDetails').val(JSON.stringify(intlMobileContact
                .getSelectedCountryData()))
        });

        // Individual office contact no
        var officeContact = document.querySelector("#office_contact");
        intlOfficeContact = window.intlTelInput(officeContact, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));
        $('#OfficeContactCountryDetails').val(JSON.stringify(intlOfficeContact.getSelectedCountryData()))

        officeContact.addEventListener("countrychange", function() {
            $('#OfficeContactCountryDetails').val(JSON.stringify(intlOfficeContact
                .getSelectedCountryData()))
        });

        // Company Contact no fields
        var companyOfficeContact = document.querySelector("#company_office_contact");
        intlCompanyMobileContact = window.intlTelInput(companyOfficeContact, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));

        $('#CompanyOfficeContactCountryDetails').val(JSON.stringify(intlCompanyMobileContact
            .getSelectedCountryData()));

        companyOfficeContact.addEventListener("countrychange", function() {
            $('#CompanyOfficeContactCountryDetails').val(JSON.stringify(intlCompanyMobileContact
                .getSelectedCountryData()))
        });

        // company optional contact no
        var companyoptionalContact = document.querySelector("#company_optional_contact");
        intlcompanyOptionalContact = window.intlTelInput(companyoptionalContact, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));
        $('#companyMobileContactCountryDetails').val(JSON.stringify(intlcompanyOptionalContact
            .getSelectedCountryData()))

        companyoptionalContact.addEventListener("countrychange", function() {
            $('#companyMobileContactCountryDetails').val(JSON.stringify(intlcompanyOptionalContact
                .getSelectedCountryData()))
        });


        @if (!is_null(old('mobileContactCountryDetails')))
            var mbCountry = {!! old('mobileContactCountryDetails') !!}
            $('#mobileContactCountryDetails').val({!! old('mobileContactCountryDetails') !!})
            intlMobileContact.setCountry(mbCountry['iso2']);
        @endif
        @if (!is_null(old('OfficeContactCountryDetails')))
            // var officeCountry = {!! old('OfficeContactCountryDetails') !!}
            // $('#OfficeContactCountryDetails').val({!! old('OfficeContactCountryDetails') !!})
            // intlOfficeContact.setCountry(officeCountry['iso2']);
            intlOfficeContact.setCountry('pk');
        @endif
        @if (!is_null(old('CompanyOfficeContactCountryDetails')))
            // var companyContact = {!! old('CompanyOfficeContactCountryDetails') !!}
            // $('#CompanyOfficeContactCountryDetails').val({!! old('CompanyOfficeContactCountryDetails') !!})
            intlCompanyMobileContact.setCountry('pk');
        @endif
        @if (!is_null(old('companyMobileContactCountryDetails')))
            // var officeOptional = {!! old('companyMobileContactCountryDetails') !!}
            // $('#companyMobileContactCountryDetails').val({!! old('companyMobileContactCountryDetails') !!})
            intlcompanyOptionalContact.setCountry('pk');
        @endif

        var residential_country = $("#residential_country");
        residential_country.wrap('<div class="position-relative"></div>');
        residential_country.select2({
            dropdownAutoWidth: !0,
            dropdownParent: residential_country.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {

            $("#residential_city").empty()
            $('#residential_state').empty();
            $('#residential_state').html('<option value=0>Select State</option>');
            $('#residential_city').html('<option value=0>Select City</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-states', ['countryId' => ':countryId']) }}"
                .replace(':countryId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#common_form');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'stateId': $(this).val(),
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success) {

                            $.each(response.states, function(key, value) {
                                $("#residential_state").append('<option value="' +
                                    value
                                    .id + '">' + value.name + '</option>');
                            });
                            hideBlockUI('#common_form');

                            residential_state.val(ra_state);
                            residential_state.trigger('change');



                            @if (!is_null(old('residential.state')))
                                $('#residential_state').val({{ old('residential.state') }});
                                $('#residential_state').trigger('change')
                            @endif
                        } else {
                            hideBlockUI('#common_form');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#common_form');
                    }
                });
            }
        });

        var residential_state = $("#residential_state");
        residential_state.wrap('<div class="position-relative"></div>');
        residential_state.select2({
            dropdownAutoWidth: !0,
            dropdownParent: residential_state.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            $("#residential_city").empty()
            $('#residential_city').html('<option value=0>Select City</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-cities', ['stateId' => ':stateId']) }}"
                .replace(':stateId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#common_form');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'stateId': $(this).val(),
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success) {
                            $.each(response.cities, function(key, value) {
                                $("#residential_city").append('<option value="' +
                                    value
                                    .id + '">' + value.name + '</option>');
                            });
                            residential_city.val(ra_city);
                            residential_city.trigger('change');
                            hideBlockUI('#common_form');
                            @if (!is_null(old('residential.city')))
                                $('#residential_city').val({{ old('residential.city') }});
                                $('#residential_city').trigger('change')
                            @endif
                        } else {
                            hideBlockUI('#common_form');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#common_form');
                    }
                });
            }
        });

        var residential_city = $("#residential_city");
        residential_city.wrap('<div class="position-relative"></div>');
        residential_city.select2({
            dropdownAutoWidth: !0,
            dropdownParent: residential_city.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        });

        var mailing_country = $("#mailing_country");
        mailing_country.wrap('<div class="position-relative"></div>');
        mailing_country.select2({
            dropdownAutoWidth: !0,
            dropdownParent: mailing_country.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {

            $("#mailing_city").empty()
            $('#mailing_state').empty();
            $('#mailing_state').html('<option value=0>Select State</option>');
            $('#mailing_city').html('<option value=0>Select City</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-states', ['countryId' => ':countryId']) }}"
                .replace(':countryId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#common_form');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'stateId': $(this).val(),
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success) {

                            $.each(response.states, function(key, value) {
                                $("#mailing_state").append('<option value="' +
                                    value
                                    .id + '">' + value.name + '</option>');
                            });

                            mailing_state.val(cp_state);
                            mailing_state.trigger('change');

                            @if (isset($data['mailing.state']))
                                mailing_state.val("{{ $data['mailing.state'] }}");
                                mailing_state.trigger('change');
                            @endif

                            hideBlockUI('#common_form');

                        } else {
                            hideBlockUI('#common_form');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#common_form');
                    }
                });
            }
        });


        var mailing_state = $("#mailing_state");
        mailing_state.wrap('<div class="position-relative"></div>');
        mailing_state.select2({
            dropdownAutoWidth: !0,
            dropdownParent: mailing_state.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            $("#mailing_city").empty()
            $('#mailing_city').html('<option value=0>Select City</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-cities', ['stateId' => ':stateId']) }}"
                .replace(':stateId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#common_form');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'stateId': $(this).val(),
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success) {
                            $.each(response.cities, function(key, value) {
                                $("#mailing_city").append('<option value="' +
                                    value
                                    .id + '">' + value.name + '</option>');
                            });
                            mailing_city.val(cp_city);
                            mailing_city.trigger('change');

                            @if (isset($data['mailing.city']))
                                $("#mailing_city").val(
                                    "{{ $data['mailing.city'] }}");
                            @endif

                            hideBlockUI('#common_form');
                        } else {
                            hideBlockUI('#common_form');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#common_form');
                    }
                });
                hideBlockUI('#common_form');
            }
        });

        var mailing_city = $("#mailing_city");
        mailing_city.wrap('<div class="position-relative"></div>');
        mailing_city.select2({
            dropdownAutoWidth: !0,
            dropdownParent: mailing_city.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        });

        var dob = $("#dob").flatpickr({
            defaultDate: new Date(1995, 01, 01),
            minDate: '',
            maxDate: new Date().fp_incr(-750),
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        @if (!is_null(old('individual.dob')))
            dob.setDate('{{ old('individual.dob') }}');
        @endif

        $.validator.addMethod("ContactNoError", function(value, element) {

            if (element.id == 'mobile_contact') {
                return intlMobileContact.isValidNumber();
            }
            if (element.id == 'company_office_contact') {
                return intlCompanyMobileContact.isValidNumber();
            }
        }, "In Valid number");

        $.validator.addMethod("OPTContactNoError", function(value, element) {

            if (value.length > 0) {
                if (element.id == 'office_contact') {
                    return intlOfficeContact.isValidNumber();
                }
                if (element.id == 'company_optional_contact') {
                    return intlcompanyOptionalContact.isValidNumber();
                }
            } else {
                return true;
            }
        }, "In Valid number");

        $('#is_local').on('change', function() {
            if ($(this).is(':checked')) {
                $('#nationality').val(167).change();
            } else {
                $('#nationality').val(0).change();
            }
        });

        $('#cpyAddress').on('change', function() {
            if ($(this).is(':checked')) {
                cp_state = $('#residential_state').val();
                cp_city = $('#residential_city').val();

                $('#mailing_address_type').val($('#residential_address_type').val());
                $('#mailing_country').val($('#residential_country').val());
                mailing_country.trigger('change')
                $('#mailing_postal_code').val($('#residential_postal_code').val());
                $('#mailing_address').val($('#residential_address').val());

            } else {
                $('#mailing_address_type').val('')
                $('#mailing_country').val(0)
                $('#mailing_postal_code').val('');
                $('#mailing_address').val('');
            }
        })

        var validator = $("#stakeholderForm").validate({
            rules: {
                'stakeholder_as': {
                    required: function() {
                        return $("#stakeholder_as").val() == 0;
                    }
                },
                'stakeholder_type': {
                    required: function() {
                        return $("#stakeholder_type").val() == 0;
                    }
                },
                'residential[address_type]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                },
                'residential[country]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                    min: function() {
                        return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                    },
                },
                'residential[state]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                    min: function() {
                        return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                    },
                },
                'residential[city]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                    min: function() {
                        return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                    },
                },
                'residential[address]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                },
                'residential[postal_code]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                },
                'mailing[address_type]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                },
                'mailing[country]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                    min: function() {
                        return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                    },
                },
                'mailing[state]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                    min: function() {
                        return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                    },
                },
                'mailing[city]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                    min: function() {
                        return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                    },
                },
                'mailing[address]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                },
                'mailing[postal_code]': {
                    required: function() {
                        return $('#stakeholder_type').val() != 'L';
                    },
                },
                'company[company_name]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c';
                    },
                },
                'company[registration]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c' && $('#stakeholder_type').val() !=
                            'L';
                    },
                },
                'company[industry]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c' && $('#stakeholder_type').val() !=
                            'L';
                    },
                },
                'company[company_ntn]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c' && $('#stakeholder_type').val() !=
                            'L';
                    },
                },
                'company[strn]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c' && $('#stakeholder_type').val() !=
                            'L';
                    },
                },
                'company[company_office_contact]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c';
                    },
                },
                'individual[full_name]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i';
                    },
                },
                'individual[father_name]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i' && $('#stakeholder_type').val() !=
                            'L';
                    },
                },
                'individual[occupation]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i' && $('#stakeholder_type').val() !=
                            'L';
                    },
                },
                'individual[cnic]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i' && $('#stakeholder_type').val() !=
                            'L';
                    },
                },
                'individual[mobile_contact]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i';
                    },
                },
                'individual[dob]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i' && $('#stakeholder_type').val() !=
                            'L';
                    },
                },
                'individual[nationality]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i' && !$('#is_local').is(
                            ':checked') && $(
                            '#stakeholder_type').val() != 'L';
                    },
                    min: 1,
                },
            },
            messages: {
                'individual[nationality]': {
                    min: "Please select Nationality"
                },
                'stakeholder_as': {
                    required: "Please select stakeholder as",
                },
                'residential[address_type]': {
                    required: "Please select address type",
                },
                'residential[country]': {
                    required: "Please select country",
                    min: "Please select country"
                },
                'residential[state]': {
                    required: "Please select state",
                    min: "Please select state"
                },
                'residential[city]': {
                    required: "Please select city",
                    min: "Please select city"
                },
                'residential[address]': {
                    required: "Please enter address",
                },
                'residential[postal_code]': {
                    required: "Please enter postal code",
                },
                'mailing[address_type]': {
                    required: "Please select address type",
                },
                'mailing[country]': {
                    required: "Please select country",
                    min: "Please select country"
                },
                'mailing[state]': {
                    required: "Please select state",
                    min: "Please select state"
                },
                'mailing[city]': {
                    required: "Please select city",
                    min: "Please select city"
                },
                'mailing[address]': {
                    required: "Please enter address",
                },
                'mailing[postal_code]': {
                    required: "Please enter postal code",
                },
                'mailing_address': {
                    required: "Please enter mailing address",
                },
                'address': {
                    required: "Please enter address",
                },
                'optional_contact': {
                    required: "Please enter optional contact",
                },
                'full_name': {
                    required: "Please enter full name",
                },
                'father_name': {
                    required: "Please enter father name",
                },
                'cnic': {
                    required: "Please enter cnic",
                },
                'registration': {
                    required: "Please enter registration",
                },
                'company_name': {
                    required: "Please enter company name",
                },
                'email': {
                    required: "Please enter email",
                }
            },
            errorClass: 'is-invalid text-danger',
            errorElement: "span",
            wrapper: "div",
            submitHandler: function(form) {
                if ($("#stakeholder_as").val() == 'i' || $("#stakeholder_as").val() == 'c') {
                    form.submit();
                }
            }
        });

    });
</script>
