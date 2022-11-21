@extends('app.layout.layout')




@section('page-title', 'Sales Plan')

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
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/dashboard-ecommerce.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/charts/chart-apex.min.css">
@endsection

@section('custom-css')
    <style>
        .dataTable .selected {
            background-color: #E3E1FC !important;
        }
        label {
            /* color: Lime; */
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0"> Inventory Aging</h2>
            </div>
        </div>
    </div>
@endsection

@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/charts/apexcharts.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/dashboard-ecommerce.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/charts/chart-apex.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/cdb.min.css" />
@endsection

@section('custom-css')
@endsection


@section('content')
    <p class="mb-2">
    </p>

    <div class="row">
        <div class="col-12">

            <div class="row">

            <div class="card">
                <div class="card-body">
                   <div class="container col-md-12" id="main">
                        <div class="row" id="main_heading">
                            <div class="col-md-12">
                                <h3 class="text-center">
                                    Inventory Aging
                                </h3>
                            </div>
                        </div>
                        <div class="row mt-3" id="dropdow_installment">
                            <div class="col-md-4 col-xl-4 col-sm-12 align-content-center">
                                <label for="installments"> <b> Installments</b></label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Open this select menu</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-xl-4 col-sm-12 align-content-center">
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="checkboxes"><b> Year. Quarter. Month ...</b></label>
                                     @foreach ([1=>'year 2020',2=>'year 2021',3=>'year 2022',4=>'year 2023'] as $check )
                                        <div class="checkbox">
                                            <label for="checkboxes-0">
                                            <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
                                            {{$check}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <div class="col-md-4 col-xl-4 col-sm-12 align-content-center">
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="checkboxes"><b> Floor</b></label>
                                    <div class="col-md-6">
                                        @foreach ([1=>'Ground Floor 1',2=>'Ground Floor 2',3=>'Ground Floor 3',4=>'Ground Floor 4'] as $check )
                                            <div class="checkbox">
                                                <label for="checkboxes-0">
                                                <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
                                                {{$check}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3" id="sub_manu">
                            <div class="col-md-4 sm-md-12">
                                <p>
                                    <b> Due Amt</b>
                                </p>
                                <h6>
                                    726,001,501
                                </h6>
                            </div>
                            <div class="col-md-4 sm-md-12">
                                <p>
                                    <b> Received </b>
                                </p>
                                <h6>
                                    1,458,832
                                </h6>
                            </div>
                            <div class="col-md-4 sm-md-12">
                                <p>
                                  <b>  Net Receivable </b>
                                </p>
                                <h6>
                                    724,542,675
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="card chart-container">
                                <canvas id="chart"></canvas>
                            </div>
                        </div>

                        <div class="row table-responsive">
                            <table id="example" class="table table-striped dt-complex-header table"
                                    style="width:100%">
                                <thead>
                                    <tr>
                                    <th class="text-nowrap" scope="col">#</th>
                                    <th class="text-nowrap" scope="col">Due Date</th>
                                    <th class="text-nowrap" scope="col">Unit</th>
                                    <th class="text-nowrap" scope="col">Customer</th>
                                    <th class="text-nowrap" scope="col">Customer Number</th>
                                    <th class="text-nowrap" scope="col">Instalment</th>
                                    <th class="text-nowrap" scope="col">Due Amt</th>
                                    <th class="text-nowrap" scope="col">Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 1000; $i++) 
                                        <tr>
                                            <th scope="row">{{$i}}</th>
                                            <td class="text-nowrap" >{{ fake()->date() }}</td>
                                            <td class="text-nowrap" >{{ fake()->numerify('7F- ###') }}</td>
                                            <td class="text-nowrap" >{{ fake()->name() }}</td>
                                            <td class="text-nowrap" >{{ fake()->phoneNumber() }}</td>
                                            <td class="text-nowrap" >{{ fake()->randomDigit() }}</td>
                                            <td class="text-nowrap" >{{ fake()->numberBetween($min = 1000, $max = 9000) }}</td>
                                            <td class="text-nowrap" >{{ fake()->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL) }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>  
                   </div>

                </div>
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
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.select.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/charts/chart.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/core/app-menu.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/core/app.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/customizer.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/charts/chart-chartjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cdbootstrap/js/cdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cdbootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/9d1d9a82d2.js" crossorigin="anonymous"></script>
@endsection

@section('page-js')

@endsection

@section('custom-js')
    <script>        
        $(document).ready(function() {
            var table = $('#example').DataTable({
                responsive: true
            });
        });
    </script>
    <script>
      const ctx = document.getElementById("chart").getContext('2d');
      const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["Due Amt", "Received", "Not Received", "Customer",
          "Balance", "Unit", "Installment"],
          datasets: [{
            label: 'Payment',
            backgroundColor: 'rgba(161, 198, 247, 1)',
            borderColor: 'rgb(47, 128, 237)',
            data: [300, 400, 200, 500, 800, 900, 200],
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
              }
            }]
          }
        },
      });
</script>
@endsection
