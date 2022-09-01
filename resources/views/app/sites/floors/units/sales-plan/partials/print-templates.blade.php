<div class="modal modal-slide-in new-user-modal fade" id="modal-sales-plan-template">
    <div class="modal-dialog">
        <form class="modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Select Sales Plan Template</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="mb-1">
                    {{-- @foreach ($sales_plan_templates as $sales_plan_template)
                        <a href="#">
                            <div class="card mb-4 " style="border: solid 2px blue; ">
                                <img class="card-img-top"
                                    src="{{ asset('app-assets') }}{{ $sales_plan_template->image }}"
                                    alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $sales_plan_template->name }}</h5>
                                </div>
                            </div>
                        </a>
                    @endforeach --}}
                </div>
            </div>
        </form>
    </div>
</div>
