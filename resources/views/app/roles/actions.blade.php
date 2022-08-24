<div class="dropdown">
    <button type="button" class="btn waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bi bi-three-dots-vertical" style="font-size: 1rem;"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        @can('roles.edit')
            <a class="dropdown-item" href="{{ route('roles.edit', ['id' => encryptParams($id)]) }}">
                <i class="bi bi-pencil" style="font-size: 1.1rem" class="me-50"></i>
                <span>{{ __('lang.commons.edit') }}</span>
            </a>
        @endcan
        @can('roles.destroy')
            <a class="dropdown-item" onclick="deleteByID('{{ encryptParams($id) }}')" href="javascript:void(0);">
                <i class="bi bi-trash" style="font-size: 1.1rem" class="me-50"></i>
                <span>{{ __('lang.commons.delete') }}</span>
            </a>
        @endcan
        @can('roles.make-default')
            <a class="dropdown-item" href="{{ route('roles.make-default', ['id' => encryptParams($id)]) }}">
                <i class="bi bi-check2-circle" style="font-size: 1.1rem" class="me-50"></i>
                <span>{{ __('lang.commons.make_default') }}</span>
            </a>
        @endcan
    </div>
</div>
