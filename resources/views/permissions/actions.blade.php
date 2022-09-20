<div class="dropdown">
    <button type="button" class="btn waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="bi bi-three-dots-vertical" style="font-size: 1rem;"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a class="dropdown-item" href="{{ route('permissions.edit', ['id' => encryptParams($id)]) }}">
            <i class="bi bi-pencil" style="font-size: 1.1rem" class="me-50"></i>
            <span>{{ __('lang.commons.edit') }}</span>
        </a>
        <a class="dropdown-item" onclick="deleteByID('{{ encryptParams($id) }}')" href="javascript:void(0);">
            <i class="bi bi-trash" style="font-size: 1.1rem" class="me-50"></i>
            <span>{{ __('lang.commons.delete') }}</span>
        </a>
    </div>
</div>
