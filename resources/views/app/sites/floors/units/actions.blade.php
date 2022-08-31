{{-- <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light me-1" style="margin: 5px"
    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Floor"
    href="{{ route('sites.floors.units.edit', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($floor_id), 'id' => encryptParams($id)]) }}">
    <i class="bi bi-pencil" style="font-size: 1.1rem" class="m-10"></i>
</a> --}}
{{-- href="{{ route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($floor_id), 'unit_id' => encryptParams($id)]) }}" --}}

<a data-bs-toggle="modal" data-bs-target="#template-modal{{ $id  }}" class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px"
    data-bs-toggle="tooltip" data-bs-placement="top" title="Sales Plan"
    >
    <i class="bi bi-printer-fill" style="font-size: 1.1rem" class="m-10"></i>
</a>

 <!-- Modal to add new user starts-->
 <div class="modal modal-slide-in new-user-modal fade" id="template-modal{{ $id }}">
    <div class="modal-dialog">
      <form class="modal-content pt-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
        <div class="modal-header mb-1">
          <h5 class="modal-title" id="exampleModalLabel">Select Sales Plan Template</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="mb-1">
            @foreach ($sales_plan_templates as $sales_plan_template )
                <a href="/print_sales_plan/{{ $id }}/{{ $sales_plan_template->id }}">
                    <div class="card mb-4 " style="border: solid 2px blue; ">
                        <img class="card-img-top"  src="{{ asset('app-assets') }}{{ $sales_plan_template->image }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{ $sales_plan_template->name }}</h5>
                            </div>
                    </div>
                </a>
            @endforeach
          </div>
         </div>
      </form>
    </div>
  </div>
  <!-- Modal to add new user Ends-->
