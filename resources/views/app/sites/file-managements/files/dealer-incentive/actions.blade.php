<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.file-managements.rebate-incentive.edit')
        <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
            title="Edit Rebate Form"
            href="{{ route('sites.file-managements.rebate-incentive.edit', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}">
            <i class="bi bi-pencil" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan
</div>