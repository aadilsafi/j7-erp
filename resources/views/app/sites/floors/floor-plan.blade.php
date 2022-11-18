@extends('app.layout.layout')

@section('seo-breadcrumb')
  {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.floor-plan', $site_id) }}
@endsection

@section('page-title', 'Floors List')

@section('page-vendor')
  <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/maps/leaflet.min.css">



@endsection

@section('page-css')
  {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css"> --}}
@endsection

@section('custom-css')
  <style>
    #map {
      height: 400px;
    }

    /*Legend specific*/
    .legend {
      padding: 6px 8px;
      font: 14px Arial, Helvetica, sans-serif;
      background: white;
      background: rgba(255, 255, 255, 0.8);
      /*box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);*/
      /*border-radius: 5px;*/
      line-height: 24px;
      color: #555;
    }

    .legend h4 {
      text-align: center;
      font-size: 16px;
      margin: 2px 12px 8px;
      color: #777;
    }

    .legend span {
      position: relative;
      bottom: 3px;
    }

    .legend i {
      width: 18px;
      height: 18px;
      float: left;
      margin: 0 8px 0 0;
      opacity: 0.7;
    }

    .legend i.icon {
      background-size: 18px;
      background-color: rgba(255, 255, 255, 1);
    }

    /* Tooltip of the marker */
    .marker_tooltip {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
      color: #fdfdfd !important;
      font-weight: 600;
      font-size: 12px;
    }
  </style>
@endsection

@section('breadcrumbs')
  <div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
      <div class="col-12">
        <h2 class="content-header-title float-start mb-0">Floors</h2>
        <div class="breadcrumb-wrapper">
          {{ Breadcrumbs::render('sites.floors.floor-plan', $site_id) }}
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <form class="form form-vertical" enctype="multipart/form-data"
    action="{{ route('sites.floors.floor-plan.upload', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}"
    method="POST">

    <div class="row">
      <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

        @csrf
        <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
          <div class="card-body">
            <div class="row mb-1">
              <div class="col-lg-12 col-md-12 col-sm-12 position-relative">

                <label class="form-label fs-5" for="floorplan_file">Floorplan File <span class="text-danger">*</span>
                </label>

                <input id="floorplan_file" accept=".geojson" type="file" name="floorplan_file" />

                @error('floorplan_file')
                  <span class="text-danger">{{ $message }}</span>
                @enderror

              </div>

            </div>

          </div>
        </div>

      </div>

      <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
        <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto">
          <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
              <div class="row g-1">
                <div class="col-md-12">
                  <button type="submit"
                    class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                    <i data-feather='save'></i>
                    Save Floor Plan
                  </button>
                </div>
                <div class="col-md-12">
                  <a href="{{ route('sites.floors.index', ['site_id' => encryptParams($site_id)]) }}"
                    class="btn btn-relief-outline-danger w-100 waves-effect waves-float waves-light">
                    <i data-feather='x'></i>
                    {{ __('lang.commons.cancel') }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </form>
  @if ($floor_plan)
    <div id="map">
      <div class="leaflet-bottom leaflet-left">
        <div class="legend">
          <h4>Room Status</h4>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('vendor-js')
  <script src="{{ asset('app-assets') }}/vendors/js/maps/leaflet.min.js"></script>
@endsection

@section('page-js')
  {{-- <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/buttons.server-side.js"></script> --}}
@endsection

@section('custom-js')
  <script>
    @if ($floor_plan)

      const jsonData = {!! $floor_plan !!};
      const center = jsonData.features[0].geometry.coordinates[0][0][0];

      const roomStatus = {
        "Open": {
          name: "Open",
          color: "green"
        },
        "Hold": {
          name: "Hold",
          color: "yellow"
        },
        "Token": {
          name: "Token",
          color: "orange"
        },
        "Sold": {
          name: "Sold",
          color: "red"
        },
        "PartialDP": {
          name: "Partial DP",
          color: "pink"
        },
      };

      const statuses = Object.values(roomStatus);
      let visibleStatuses = statuses.map((item) => item.name);

      const displayLegend = () => {
        let html = ``;
        statuses.map((status, i) => (
          html += `<div class="d-flex" style="pointer-events: all">
                
             <i style="background: ${status.color} "></i>
                <span>${status.name}</span> &nbsp;
                <div class="form-check"> 
                    <input class="form-check-input" type="checkbox" checked onchange="changeVisibleStatus(this, '${status.name}')"  name="statusCheckboxes[]">
                 </div>
                <br />
              </div>`
        ));

        $(".legend").append(html);
      }

      displayLegend();

      let faltu = `<Form.Check
                  defaultChecked={true}
                  onChange={(e) => {
                    const index = visibleStatuses.indexOf(status.name);
                    if(index >= 0){
                      let tmp = visibleStatuses.filter((item) => item != status.name);
                      setVisibleStatuses(tmp);
                    } else {
                      setVisibleStatuses([...visibleStatuses, status.name]) ;
                    }
                  }}
                />`;


      const changeVisibleStatus = (object, status) => {
        console.log(object, status);
      }

      const floorStyle = (feature) => {
        let status = feature.properties.status;
        let color = "blue";

        color = statuses.filter((item) => item.name === status)[0]?.color;
        let fillOpacity = 0;
        let checked = false;
        if (visibleStatuses.indexOf(status) >= 0) {
          checked = true;
          fillOpacity = 0.2;
        }
        return {
          fillColor: color,
          fillOpacity: fillOpacity,
          color: checked ? color : 'transparent',
        };
      };

      const map = L.map('map').setView([center[1], center[0]], 17);

      // L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      //   maxZoom: 30,
      //   attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      // }).addTo(map);

      map.on("click", async function(e) {
        console.log(e.latlng);
      });

      const floorLayer = L.geoJSON(jsonData);
      floorLayer.eachLayer((layer) => {
        layer.bindTooltip(`Unit: ${layer.feature.properties.unit_no} <br/> 
                          Status: ${layer.feature.properties.status}
                          `);

        layer.on("click", function(e) {
          const unit_no =  layer.feature.properties.unit_no;
          getUnitDetails(unit_no);
        });
      });

      floorLayer.addTo(map);

      const updateFloorStyle = () => {
        floorLayer.setStyle(floorStyle)
      }

      updateFloorStyle();

      function getUnitDetails(unit_no) {
        var _token = '{{ csrf_token() }}';
        let url =
          "{{ route('sites.floors.units.details', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($id)]) }}";
        $.ajax({
          url: url,
          type: 'get',
          dataType: 'json',
          data: {
            'unit_no': unit_no,
            '_token': _token
          },
          success: function(response) {
            if (response.data) {
              console.log(response.data);

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
    @endif
  </script>
@endsection
