<div class="modal modal-slide-in new-user-modal fade" id="modal-sales-plan-template">
    <div class="modal-dialog">
        <div class="modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="salesTemplateModalLabel">Select Sales Plan Template</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="mb-1">
                    <input type="hidden" name="sales_plan_id" id="sales_plan_id" value="0">
                    @foreach ($salesPlanTemplates as $template)
                        <a href="javascript:void(0);" onclick="printSalesPlanTemplate('{{ encryptparams($template->id) }}')">
                            <div class="card mb-4 border border-hover-primary">
                                <div class="card-body text-center">
                                    <img class="card-img-top" src="{{ asset('app-assets') }}{{ $template->image }}"
                                        alt="Card image cap">
                                        <hr>
                                    <h5 class="card-title m-0">{{ $template->name }}</h5>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
