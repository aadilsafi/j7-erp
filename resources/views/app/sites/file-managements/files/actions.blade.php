<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.file-managements.customers.units.files.index')
        <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="View File Details "
            href="{{ route('sites.file-managements.customers.units.files.show', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_id' => encryptParams($file_id)]) }}">
            <i class="bi bi-file-person-fill" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

    {{-- File Refund Actions --}}
    @if (Route::current()->getName() == 'sites.file-managements.file-refund.index')
        @php
            $checkFileRefund = DB::table('file_refunds')
                ->where('file_id', $file_id)
                ->first();
        @endphp
        @if (isset($checkFileRefund))
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="View File Refund"
                href="{{ route('sites.file-managements.file-refund.preview', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_refund_id' => encryptParams($checkFileRefund->id)]) }}">
                <i class="bi bi-view-stacked" style="font-size: 1.1rem" class="m-10"></i>
            </a>
            <a href="javascript:void(0);"
                class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Print File Refund"
                onclick="openTemplatesModal('{{ encryptParams($checkFileRefund->id) }}');">
                <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @else
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Create File Refund"
                href="{{ route('sites.file-managements.file-refund.create', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id) , 'file_id' => encryptParams($file_id)]) }}">
                <i class="bi bi-folder-plus" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endif

    @endif

    {{-- File Buy Back --}}
    @if (Route::current()->getName() == 'sites.file-managements.file-buy-back.index')
        @php
            $checkFileBuyBack = DB::table('file_buy_backs')
            ->where('file_id', $file_id)
                ->first();
        @endphp
        @if (isset($checkFileBuyBack))
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="View File Buy Back"
                href="{{ route('sites.file-managements.file-buy-back.preview', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_buy_back_id' => encryptParams($checkFileBuyBack->id)]) }}">
                <i class="bi bi-view-stacked" style="font-size: 1.1rem" class="m-10"></i>
            </a>
            <a href="javascript:void(0);"
                class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Print Buy Back File"
                onclick="openTemplatesModal('{{ encryptParams($checkFileBuyBack->id) }}');">
                <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
            </a>
            @else
                <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Create File Buy Back"
                    href="{{ route('sites.file-managements.file-buy-back.create', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id) , 'file_id' => encryptParams($file_id)]) }}">
                    <i class="bi bi-folder-plus" style="font-size: 1.1rem" class="m-10"></i>
                </a>
        @endif

    @endif

    {{-- File Cancellation --}}
    @if (Route::current()->getName() == 'sites.file-managements.file-cancellation.index')
        @php
            $checkFileCancellation = DB::table('file_cancellations')
            ->where('file_id', $file_id)
                ->first();
        @endphp
        @if (isset($checkFileCancellation))
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="View File Cancellation"
                href="{{ route('sites.file-managements.file-cancellation.preview', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_cancellation_id' => encryptParams($checkFileCancellation->id)]) }}">
                <i class="bi bi-view-stacked" style="font-size: 1.1rem" class="m-10"></i>
            </a>
            <a href="javascript:void(0);"
                class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Print File Cancellation"
                onclick="openTemplatesModal('{{ encryptParams($checkFileCancellation->id) }}');">
                <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @else
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Create File Cancellation"
                href="{{ route('sites.file-managements.file-cancellation.create', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id) , 'file_id' => encryptParams($file_id)]) }}">
                <i class="bi bi-folder-plus" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endif

    @endif

    {{-- File Resale --}}
    @if (Route::current()->getName() == 'sites.file-managements.file-resale.index')
        @php
            $checkFileResale = DB::table('file_resales')
            ->where('file_id', $file_id)
            ->first();
        @endphp
        @if (isset($checkFileResale))
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="View File Resale"
                href="{{ route('sites.file-managements.file-resale.preview', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_resale_id' => encryptParams($checkFileResale->id )]) }}">
                <i class="bi bi-view-stacked" style="font-size: 1.1rem" class="m-10"></i>
            </a>
            {{-- <a href="javascript:void(0);"
                class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Print File Resale"
                onclick="openTemplatesModal('{{ encryptParams($checkFileResale->id) }}');">
                <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
            </a> --}}
            @else
                <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Create File Resale"
                    href="{{ route('sites.file-managements.file-resale.create', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id) , 'file_id' => encryptParams($file_id)]) }}">
                    <i class="bi bi-folder-plus" style="font-size: 1.1rem" class="m-10"></i>
                </a>
        @endif

    @endif

    {{-- File title transfer --}}
    @if (Route::current()->getName() == 'sites.file-managements.file-title-transfer.index')
        @php
            $file = DB::table('file_management')->where('id',$file_id)->first();
            $checkFileTitleTransfer = DB::table('file_title_transfers')
            ->where('file_id', $file_id)
                // ->where('stakholder_id', $file->)
                ->where('status', 0)
                ->first();
        @endphp
        @if (isset($checkFileTitleTransfer))
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="View File Title Transfer"
                href="{{ route('sites.file-managements.file-title-transfer.preview', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_title_transfer_id' => encryptParams($checkFileTitleTransfer->id)]) }}">
                <i class="bi bi-view-stacked" style="font-size: 1.1rem" class="m-10"></i>
            </a>
            <a href="javascript:void(0);"
                class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Print File Title Transfer"
                onclick="openTemplatesModal('{{ encryptParams($checkFileTitleTransfer->id) }}');">
                <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @else
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Create File Title Transfer"
                href="{{ route('sites.file-managements.file-title-transfer.create', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id) , 'file_id' => encryptParams($file_id)]) }}">
                <i class="bi bi-folder-plus" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endif

    @endif
</div>
