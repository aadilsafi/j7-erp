@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.accounts.recovery.calender', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Calender')

@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/calendars/fullcalendar.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/pickers/form-flat-pickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/app-calendar.min.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Calendar</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.accounts.recovery.calender', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Full calendar start -->
    <section>
        <div class="app-calendar overflow-hidden border">
            <div class="row g-0">
                <!-- Sidebar -->
                <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column"
                    id="app-calendar-sidebar">
                    <div class="sidebar-wrapper">

                        <div class="card-body pb-0">
                            <h5 class="section-label mb-1">
                                <span class="align-middle">Filter</span>
                            </h5>
                            {{-- <div class="form-check mb-1">
                                <input type="checkbox" class="form-check-input select-all " id="select-all" checked />
                                <label class="form-check-label" for="select-all">View All</label>
                            </div> --}}
                            <div class="calendar-events-filter">
                                <div class="form-check form-check-danger mb-2">

                                    <label class="form-check-label" for="personal">Select Units</label>
                                    <select name="units" multiple class="select2 form-select unitSelect" id="">
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">
                                                {{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-check form-check-danger mb-2">
                                    <label class="form-check-label" for="personal">Select Customers</label>
                                    <select name="units" multiple class="select2 form-select customerSelect"
                                        id="">
                                        @foreach ($stakeholders as $stakeholders)
                                            <option value="{{ $stakeholders->id }}">
                                                {{ $stakeholders->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-check form-check-danger mb-2">
                                    <label class="form-check-label" for="personal">Select Floors</label>
                                    <select name="units" multiple class="select2 form-select floorSelect" id="">
                                        @foreach ($floors as $floors)
                                            <option value="{{ $floors->id }}">
                                                {{ $floors->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-check form-check-danger mb-2">
                                    <label class="form-check-label" for="personal">Select Dealer</label>
                                    <select name="units" multiple class="select2 form-select dealerSelect" id="">
                                        @foreach ($dealers as $dealers)
                                            <option value="{{ $dealers->stakeholder->id }}">
                                                {{ $dealers->stakeholder->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-check form-check-danger mb-2">
                                    <label class="form-check-label" for="personal">Select Sales Person</label>
                                    <select name="units" multiple class="select2 form-select salesPersonSelect"
                                        id="">
                                        @foreach ($users as $users)
                                            <option value="{{ $users->id }}">
                                                {{ $users->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-check form-check-danger mb-2">
                                    <label class="form-check-label" for="personal">Select Unit Types</label>
                                    <select name="units" multiple class="select2 form-select categorySelect"
                                        id="">
                                        @foreach ($types as $types)
                                            <option value="{{ $types->id }}">
                                                {{ $types->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <img src="{{ asset('app-assets') }}/images/pages/calendar-illustration.png"
                            alt="Calendar illustration" class="img-fluid" />
                    </div>
                </div>
                <!-- /Sidebar -->
                <!-- Calendar -->
                <div class="col position-relative">
                    <div class="card shadow-none border-0 mb-0 rounded-0">
                        <div class="card-body pb-0">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
                <!-- /Calendar -->
                <div class="body-content-overlay"></div>
            </div>
        </div>
        <!-- Calendar Add/Update/Delete event modal-->
        <div class="modal modal-slide-in event-sidebar fade" id="add-new-sidebar">
            <div class="modal-dialog sidebar-lg">
                <div class="modal-content p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">Installment Details</h5>
                    </div>
                    <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                        <form class="event-form needs-validation" data-ajax="false" novalidate>
                            <div class="mb-1">
                                <label for="title" class="form-label">Title</label>
                                <input disabled type="text" class="form-control" id="title" name="title"
                                    placeholder="Event Title" required />
                            </div>
                            <div class="mb-1">
                                <label for="title" class="form-label">Total Amount</label>
                                <input disabled type="text" class="form-control" id="amount" name="amount"
                                    placeholder="Total Amount" required />
                            </div>
                            <div class="mb-1">
                                <label for="title" class="form-label">Paid Amount</label>
                                <input disabled type="text" class="form-control" id="paid_amount" name="paid_amount"
                                    placeholder="Paid Amount" required />
                            </div>
                            <div class="mb-1">
                                <label for="title" class="form-label">Remaining Amount</label>
                                <input disabled type="text" class="form-control" id="remaining_amount"
                                    name="remaining_amount" placeholder="Remaining Amount" required />
                            </div>
                            <div class="mb-1 position-relative">
                                <label for="start-date" class="form-label">Due Date</label>
                                <input disabled type="text" class="form-control" id="start-date" name="start-date"
                                    placeholder="Start Date" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Calendar Add/Update/Delete event modal-->
    </section>
    <!-- Full calendar end -->
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/calendar/fullcalendar.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    start: "prevYear,prev,next,nextYear,today",
                    center: " title",
                    end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                },
                contentHeight: 600,
                displayEventTime:true,
                // footerToolbar: {
                //     start: "prevYear,prev,next,nextYear, title",
                //     end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth,today",
                // },
                events: JSON.parse('{!! $events !!}'),
                // eventColor: '#7367f0',
                eventClick: function(info) {
                    $('#add-new-sidebar').modal('show');
                    $('#title').val(info.event.title);
                    $('#start-date').val(info.event.start);
                    $("#amount").val(info.event.extendedProps.amount);
                    $("#paid_amount").val(info.event.extendedProps.paid_amount);
                    $("#remaining_amount").val(info.event.extendedProps.remaining_amount);
                }

            });
            calendar.render();
        });
    </script>

    {{-- ajax --}}
    <script>
        // unit wise  filter
        $(".unitSelect").change(function() {
            let selectedValue = [];
            $('.unitSelect :selected').each(function() {
                selectedValue.push($(this).val());
            });
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.accounts.recovery.ajax-get-filtered-calender-events', ['site_id' => $site_id]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    ids: selectedValue,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        // eventScript(response.events)
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                start: "prevYear,prev,next,nextYear,today",
                                center: " title",
                                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                            },

                            events: response.events,
                            eventClick: function(info) {
                                $('#add-new-sidebar').modal('show');
                                $('#title').val(info.event.title);
                                $('#start-date').val(info.event.start);
                                $("#amount").val(info.event.extendedProps.amount);
                                $("#paid_amount").val(info.event.extendedProps.paid_amount);
                                $("#remaining_amount").val(info.event.extendedProps
                                    .remaining_amount);
                            }
                        });
                        calendar.render();

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
        });


        // Customer Wise filter
        $(".customerSelect").change(function() {
            let selectedValue = [];
            $('.customerSelect :selected').each(function() {
                selectedValue.push($(this).val());
            });
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.accounts.recovery.ajax-get-filtered-calender-events', ['site_id' => $site_id]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    customer_ids: selectedValue,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        // eventScript(response.events)
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                start: "prevYear,prev,next,nextYear,today",
                                center: " title",
                                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                            },

                            events: response.events,
                            eventClick: function(info) {
                                $('#add-new-sidebar').modal('show');
                                $('#title').val(info.event.title);
                                $('#start-date').val(info.event.start);
                                $("#amount").val(info.event.extendedProps.amount);
                                $("#paid_amount").val(info.event.extendedProps.paid_amount);
                                $("#remaining_amount").val(info.event.extendedProps
                                    .remaining_amount);
                            }
                        });
                        calendar.render();

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
        });

        // Floor Wise filter
        $(".floorSelect").change(function() {
            let selectedValue = [];
            $('.floorSelect :selected').each(function() {
                selectedValue.push($(this).val());
            });
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.accounts.recovery.ajax-get-filtered-calender-events', ['site_id' => $site_id]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    floor_ids: selectedValue,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        // eventScript(response.events)
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                start: "prevYear,prev,next,nextYear,today",
                                center: " title",
                                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                            },

                            events: response.events,
                            eventClick: function(info) {
                                $('#add-new-sidebar').modal('show');
                                $('#title').val(info.event.title);
                                $('#start-date').val(info.event.start);
                                $("#amount").val(info.event.extendedProps.amount);
                                $("#paid_amount").val(info.event.extendedProps.paid_amount);
                                $("#remaining_amount").val(info.event.extendedProps
                                    .remaining_amount);
                            }
                        });
                        calendar.render();

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
        });

        // Dealer Wise filter
        $(".dealerSelect").change(function() {
            let selectedValue = [];
            $('.dealerSelect :selected').each(function() {
                selectedValue.push($(this).val());
            });
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.accounts.recovery.ajax-get-filtered-calender-events', ['site_id' => $site_id]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    dealer_ids: selectedValue,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        // eventScript(response.events)
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                start: "prevYear,prev,next,nextYear,today",
                                center: " title",
                                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                            },

                            events: response.events,
                            eventClick: function(info) {
                                $('#add-new-sidebar').modal('show');
                                $('#title').val(info.event.title);
                                $('#start-date').val(info.event.start);
                                $("#amount").val(info.event.extendedProps.amount);
                                $("#paid_amount").val(info.event.extendedProps.paid_amount);
                                $("#remaining_amount").val(info.event.extendedProps
                                    .remaining_amount);
                            }
                        });
                        calendar.render();

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
        });

        // salesPerson filter
        $(".salesPersonSelect").change(function() {
            let selectedValue = [];
            $('.salesPersonSelect :selected').each(function() {
                selectedValue.push($(this).val());
            });
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.accounts.recovery.ajax-get-filtered-calender-events', ['site_id' => $site_id]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    salesPerson_ids: selectedValue,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        // eventScript(response.events)
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                start: "prevYear,prev,next,nextYear,today",
                                center: " title",
                                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                            },

                            events: response.events,
                            eventClick: function(info) {
                                $('#add-new-sidebar').modal('show');
                                $('#title').val(info.event.title);
                                $('#start-date').val(info.event.start);
                                $("#amount").val(info.event.extendedProps.amount);
                                $("#paid_amount").val(info.event.extendedProps.paid_amount);
                                $("#remaining_amount").val(info.event.extendedProps
                                    .remaining_amount);
                            }
                        });
                        calendar.render();

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
        });

        // categorySelect
        $(".categorySelect").change(function() {
            let selectedValue = [];
            $('.categorySelect :selected').each(function() {
                selectedValue.push($(this).val());
            });
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.accounts.recovery.ajax-get-filtered-calender-events', ['site_id' => $site_id]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    type_ids: selectedValue,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        // eventScript(response.events)
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                start: "prevYear,prev,next,nextYear,today",
                                center: " title",
                                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                            },

                            events: response.events,
                            eventClick: function(info) {
                                $('#add-new-sidebar').modal('show');
                                $('#title').val(info.event.title);
                                $('#start-date').val(info.event.start);
                                $("#amount").val(info.event.extendedProps.amount);
                                $("#paid_amount").val(info.event.extendedProps.paid_amount);
                                $("#remaining_amount").val(info.event.extendedProps
                                    .remaining_amount);
                            }
                        });
                        calendar.render();

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
        });
    </script>


@endsection
