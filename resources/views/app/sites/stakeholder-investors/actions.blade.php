<div class="d-flex justify-content-cetner align-items-center">



    @if ($status == 'pending')
        @can('sites.investors-deals.approve-investor')
            <a onclick="approve({{ $id }})" id="approve"
                class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Approve" href="#">
                <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endcan
    @endif

    {{-- @if ($status == 'posted')
        @can('sites.settings.journal-vouchers.journal-vouchers-entries.revert-voucher')
            <a onclick="revertJournalVoucher({{ $id }})" id="revertVoucher"
                class="btn btn-relief-outline-danger waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Revert Journal Voucher Entries" href="#">
                <i class="bi bi-arrow-counterclockwise" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endcan
    @endif --}}


    {{-- @can('sites.settings.journal-vouchers.edit')
        @if ($status == 'pending')
            <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Journal Vouchers"
                href="{{ route('sites.settings.journal-vouchers.edit', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}">
                <i class="bi bi-pencil" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endif
    @endcan --}}

    {{-- @can('sites.settings.journal-vouchers.show')
        <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="View Journal Voucher Entries"
            href="{{ route('sites.settings.journal-vouchers.show', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}">
            <i class="bi bi-view-list" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan --}}

</div>
