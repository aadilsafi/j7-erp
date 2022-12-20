<div class="d-flex justify-content-cetner align-items-center">

    @if ($status == 'pending')
        @can('sites.settings.journal-vouchers.journal-vouchers-entries.check-voucher')
            <a onclick="checkJournalVoucher({{ $id }})" id="checkVoucher"
                class="btn btn-relief-outline-secondary waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Check Voucher" href="#">
                <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endcan
    @endif

    @if ($status == 'checked')
        @can('sites.settings.journal-vouchers.journal-vouchers-entries.post-voucher')
            <a onclick="postJournalVoucher({{ $id }})" id="postVoucher"
                class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Post Voucher" href="#">
                <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endcan
    @endif


    {{-- @can('sites.settings.journal-vouchers.edit')
        @if ($status == 'pending')
            <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Journal Vouchers"
                href="{{ route('sites.settings.journal-vouchers.edit', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}">
                <i class="bi bi-pencil" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endif
    @endcan --}}

    @can('sites.settings.journal-vouchers.show')
        <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="View Journal Voucher Entries"
            href="{{ route('sites.settings.journal-vouchers.show', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}">
            <i class="bi bi-view-list" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

</div>
