<ul class="nav nav-pills mb-2">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('sites.settings.custom-fields.index') ? 'active' : null }}"
            id="custom-field-tab" data-bs-toggle="tab" role="tab" aria-selected="true"
            href="{{ route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)]) }}" aria-controls="home" >
            <i data-feather="list" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Custom Field</span>
        </a>
    </li>
</ul>
