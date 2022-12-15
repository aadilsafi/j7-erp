<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.receipts.templates.print')
        {{-- <a href="javascript:void(0);" class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1"
            style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Receipt"
            onclick="openTemplatesModal('{{ $id }}');">
            <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
        </a> --}}
    @endcan
    @can('sites.receipts.show')
        <a href="{{ route('sites.file-transfer-receipts.show', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}"
            class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Receipt Details">
            <i class="bi bi-receipt-cutoff" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan
</div>
