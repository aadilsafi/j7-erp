@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'dashboard') }}
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

{{-- @section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'dashboard') }}
@endsection --}}

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
                            <div class="form-check mb-1">
                                <input type="checkbox" class="form-check-input select-all" id="select-all" checked />
                                <label class="form-check-label" for="select-all">View All</label>
                            </div>
                            <div class="calendar-events-filter">
                                {{-- <div class="form-check form-check-danger mb-1">
                                    <input type="checkbox" class="form-check-input input-filter" id="personal"
                                        data-value="personal" checked />
                                    <label class="form-check-label" for="personal">Personal</label>
                                </div> --}}
                                <div class="form-check form-check-primary mb-1">
                                    <input type="checkbox" class="form-check-input input-filter" id="units"
                                        data-value="units" checked />
                                    <label class="form-check-label" for="units">Due Installments</label>
                                </div>
                                {{-- <div class="form-check form-check-warning mb-1">
                                    <input type="checkbox" class="form-check-input input-filter" id="family"
                                        data-value="family" checked />
                                    <label class="form-check-label" for="family">Family</label>
                                </div>
                                <div class="form-check form-check-success mb-1">
                                    <input type="checkbox" class="form-check-input input-filter" id="holiday"
                                        data-value="holiday" checked />
                                    <label class="form-check-label" for="holiday">Holiday</label>
                                </div>
                                <div class="form-check form-check-info">
                                    <input type="checkbox" class="form-check-input input-filter" id="etc"
                                        data-value="etc" checked />
                                    <label class="form-check-label" for="etc">ETC</label>
                                </div> --}}
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
                        <h5 class="modal-title">Installment  Details</h5>
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
                                <input disabled type="text" class="form-control" id="remaining_amount" name="remaining_amount"
                                    placeholder="Remaining Amount" required />
                            </div>
                            <div class="mb-1 position-relative">
                                <label for="start-date" class="form-label">Due Date</label>
                                <input disabled type="text" class="form-control" id="start-date" name="start-date"
                                    placeholder="Start Date" />
                            </div>
                            {{-- <div class="mb-1 position-relative">
                                <label for="end-date" class="form-label">End Date</label>
                                <input disabled type="text" class="form-control" id="end-date" name="end-date"
                                    placeholder="End Date" />
                            </div> --}}
                            {{-- <div class="mb-1">
                                <div class="form-check form-switch">
                                    <input disabled type="checkbox" class="form-check-input allDay-switch" id="customSwitch3" />
                                    <label class="form-check-label" for="customSwitch3">All Day</label>
                                </div>
                            </div> --}}
                            {{-- <div class="mb-1">
                                <label for="event-url" class="form-label">Event URL</label>
                                <input disabled type="url" class="form-control" id="event-url"
                                    placeholder="https://www.google.com/" />
                            </div> --}}
                            {{-- <div class="mb-1 select2-primary">
                                <label for="event-guests" class="form-label">Add Guests</label>
                                <select  disabled class="select2 select-add-guests form-select w-100" id="event-guests" multiple>
                                    <option data-avatar="1-small.png" value="Jane Foster">Jane Foster</option>
                                    <option data-avatar="3-small.png" value="Donna Frank">Donna Frank</option>
                                    <option data-avatar="5-small.png" value="Gabrielle Robertson">Gabrielle Robertson
                                    </option>
                                    <option data-avatar="7-small.png" value="Lori Spears">Lori Spears</option>
                                    <option data-avatar="9-small.png" value="Sandy Vega">Sandy Vega</option>
                                    <option data-avatar="11-small.png" value="Cheryl May">Cheryl May</option>
                                </select>
                            </div> --}}
                            {{-- <div class="mb-1">
                                <label for="event-location" class="form-label">Location</label>
                                <input disabled type="text" class="form-control" id="event-location"
                                    placeholder="Enter Location" />
                            </div> --}}
                            <div class="mb-1">
                                <label class="form-label">Description</label>
                                <textarea disabled name="event-description-editor" id="event-description-editor" class="form-control"></textarea>
                            </div>
                            {{-- <div class="mb-1 d-flex">
                                <button type="submit" class="btn btn-primary add-event-btn me-1">Add</button>
                                <button type="button" class="btn btn-outline-secondary btn-cancel"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit"
                                    class="btn btn-primary update-event-btn d-none me-1">Update</button>
                                <button class="btn btn-outline-danger btn-delete-event d-none">Delete</button>
                            </div> --}}
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
    {{-- <script src="{{ asset('app-assets') }}/js/scripts/pages/app-calendar-events.min.js"></script> --}}
    <script src="{{ asset('app-assets') }}/js/scripts/pages/app-calendar.min.js"></script>
@endsection

@section('custom-js')

    <script>
        "use strict";
        var date = new Date,
            nextDay = new Date((new Date).getTime() + 864e5),
            nextMonth = 11 === date.getMonth() ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date
                .getMonth() + 1, 1),
            prevMonth = 11 === date.getMonth() ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date
                .getMonth() - 1, 1),
                events =JSON.parse( '{!! $events !!}');
    </script>

@endsection
