<!DOCTYPE html>

<html class="loading" lang="{{ LaravelLocalization::getCurrentLocale() }}"
    data-textdirection="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    {{-- <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @yield('seo-breadcrumb')

    <title>@yield('page-title') - {{ env('APP_NAME') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets') }}/images/ico/apple-icon-120.html">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets') }}/images/ico/favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/css/plugins/extensions/ext-component-sweet-alerts.min.css">




    <style class="INLINE_PEN_STYLESHEET_ID">
        body {
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
    </style>



</head>

<body>

</body>
<script src="{{ asset('app-assets') }}/vendors/js/extensions/sweetalert2.all.min.js"></script>

<script>
    function download() {
      
    }

    Swal.fire({
        title: 'Enter your Pin Code',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off',
            maxlength: 8,
            minlength: 8,
            required: true,
            placeholder: 'Enter your Pin Code'
        },
        showCancelButton: false,
        confirmButtonText: 'Verify Pin',
        showLoaderOnConfirm: true,
        preConfirm: (pin) => {
            let url =
                "{{ route('verifyPin', ['file_name' => $file_name, 'stakeholder_id' => $stakeholder_id]) }}";
            var _token = '{{ csrf_token() }}';

            return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        pin: pin,
                        _token: _token

                    })
                })
                .then(response => {
                    console.log(response.data)
                    if (!response.ok) {
                        throw new Error('In Valid Code.')
                    }
                    return response.json()
                })
                .catch(error => {
                    // console.log(error)
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
        },
        allowOutsideClick: false
    }).then((result) => {
        console.log(result)
        if (result.isConfirmed) {
            Swal.fire({
                title: `Your file is ready to download`,
            })

            window.location.href = "{{ route('download-payment-plan', ['file_name' => $file_name]) }}"
        }
    })
</script>

</html>
