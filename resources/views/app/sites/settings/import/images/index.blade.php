@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.index') }}
@endsection

@section('page-title', 'Import Images')
@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/app-ecommerce.min.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Sites</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.index') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row px-5 py-2">
        <form class="form form-vertical" action="{{route('sites.settings.import.images.store', ['site_id' => $site_id])}}" enctype="multipart/form-data" method="POST">
            @csrf

            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="col-12">
                    <div class="position-relative">
                        <div class="card-body">
                            <div class="d-block mb-1">
                                <label class="form-label fs-5" for="type_name">Import </label>
                                <input id="attachment" type="file" class="filepond" name="attachment[]" />
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col-md-3 col-3 text-right">
                        <div class="position-relative">
                            <button type="submit" value="save"
                                class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                                <i data-feather='save'></i>
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="row">
        <section id="wishlist" class="grid-view wishlist-items">
            <div class="card ecommerce-card">
                <div class="item-img text-center">
                    <a href="app-ecommerce-details.html">
                        <img src="{{ asset('app-assets') }}/images/pages/eCommerce/1.png" class="img-fluid"
                            alt="img-placeholder" />
                    </a>
                </div>
                <div class="card-body">
                    <div class="item-wrapper">
                        <div class="item-rating">
                            <ul class="unstyled-list list-inline">
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                            </ul>
                        </div>
                        <div class="item-cost">
                            <h6 class="item-price">$19.99</h6>
                        </div>
                    </div>
                    <div class="item-name">
                        <a href="app-ecommerce-details.html">Apple Watch Series 5</a>
                    </div>
                    <p class="card-text item-description">
                        On Retina display that never sleeps, so it’s easy to see the time and other important information,
                        without
                        raising or tapping the display. New location features, from a built-in compass to current elevation,
                        help users
                        better navigate their day, while international emergency calling1 allows customers to call emergency
                        services
                        directly from Apple Watch in over 150 countries, even without iPhone nearby. Apple Watch Series 5 is
                        available
                        in a wider range of materials, including aluminium, stainless steel, ceramic and an all-new
                        titanium.
                    </p>
                </div>

            </div>

            <div class="card ecommerce-card">
                <div class="item-img text-center">
                    <a href="app-ecommerce-details.html">
                        <img src="{{ asset('app-assets') }}/images/pages/eCommerce/2.png" class="img-fluid"
                            alt="img-placeholder" />
                    </a>
                </div>
                <div class="card-body">
                    <div class="item-wrapper">
                        <div class="item-rating">
                            <ul class="unstyled-list list-inline">
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                            </ul>
                        </div>
                        <div class="item-cost">
                            <h6 class="item-price">$4999.99</h6>
                        </div>
                    </div>
                    <div class="item-name">
                        <a href="app-ecommerce-details.html">Apple iPhone 11 (64GB, Black)</a>
                    </div>
                    <p class="card-text item-description">
                        The Apple iPhone 11 is a great smartphone, which was loaded with a lot of quality features. It comes
                        with a
                        waterproof and dustproof body which is the key attraction of the device. The excellent set of
                        cameras
                        offer
                        excellent images as well as capable of recording crisp videos. However, expandable storage and a
                        fingerprint
                        scanner would have made it a perfect option to go for around this price range.
                    </p>
                </div>

            </div>

            <div class="card ecommerce-card">
                <div class="item-img text-center">
                    <a href="app-ecommerce-details.html">
                        <img src="{{ asset('app-assets') }}/images/pages/eCommerce/3.png" class="img-fluid"
                            alt="img-placeholder" />
                    </a>
                </div>
                <div class="card-body">
                    <div class="item-wrapper">
                        <div class="item-rating">
                            <ul class="unstyled-list list-inline">
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                            </ul>
                        </div>
                        <div class="item-cost">
                            <h6 class="item-price">$4499.99</h6>
                        </div>
                    </div>
                    <div class="item-name">
                        <a href="app-ecommerce-details.html">Apple iMac 27-inch</a>
                    </div>
                    <p class="card-text item-description">
                        The all-in-one for all. If you can dream it, you can do it on iMac. It’s beautifully & incredibly
                        intuitive and
                        packed with tools that let you take any idea to the next level. And the new 27-inch model elevates
                        the
                        experience in way, with faster processors and graphics, expanded memory and storage, enhanced audio
                        and
                        video
                        capabilities, and an even more stunning Retina 5K display. It’s the desktop that does it all —
                        better
                        and faster
                        than ever.
                    </p>
                </div>

            </div>

        </section>
    </div>

    <!-- Wishlist Ends -->


@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/js/scripts/pages/app-ecommerce-wishlist.min.js"></script>
    <!-- END: Page JS-->
@endsection

@section('custom-js')
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });

        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        FilePond.create(document.getElementById('attachment'), {
            styleButtonRemoveItemPosition: 'right',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: false,
            server: {
                process: '{{route('ajax-import-image.save-file')}}',
                revert: '/admin/revert-file',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            allowMultiple: true,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });
    </script>
@endsection
