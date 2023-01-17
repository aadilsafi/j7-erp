<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.accounts.general-ledger.filter-trial-blance')
        <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
            title="View Detail"
            href="{{ route('sites.accounts.general-ledger.filter-trial-blance', ['site_id' => encryptParams($site_id), 'account_head_code_id' => encryptParams($account_head_code)]) }}">
            <i class="bi bi-view-list" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan
</div>
