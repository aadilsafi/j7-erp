<div class="modal modal-slide-in new-user-modal fade" id="modal-receipt-template">
    <div class="modal-dialog">
        <div class="modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="salesTemplateModalLabel">Select Receipt Template</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="mb-1">
                    <input type="hidden" name="receipt_id" id="receipt_id" value="0">
                    @foreach ($receipt_templates as $template)
                        <a href="javascript:void(0);" onclick="printReceiptTemplate('{{ encryptparams($template->id) }}')">
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
