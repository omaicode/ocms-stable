@php
    $role   = isset($role) ? $role : null;
    $action = route('admin.system.roles.store');

    if($role) {
        $action = route('admin.system.roles.update', ['role' => $role->id]);
    }
@endphp

<x-forms::default-form method="POST" :action="$action">
    @if($role)
    <input type="hidden" name="_method" value="PUT">
    @endif
    <div class="card">
        <div class="card-body">
            <x-forms::group
                mode="input"
                name="name"
                :label="__('messages.roles.name')"
                placeholder="Eg: Admin, Publisher,..."
                required
                :value="old('name', $role ? $role->name : '')"
            />
            <div class="fw-bold mb-2">Roles</div>
            <div class="px-3 py-2 rounded border">
                @foreach($permissions as $key => $values)
                    <div class="mb-2">{{$key}}</div>
                    <div class="list-group">
                    @foreach($values as $permission)
                        <label class="list-group-item">
                            <input
                                class="form-check-input me-1" 
                                type="checkbox" 
                                value="{{ $permission }}" 
                                name="permissions[]"
                                @if($role && in_array($permission, $role_permissions))
                                checked
                                @endif
                            >
                            @lang('messages.roles.permissions.'.$permission)
                        </label>
                    @endforeach 
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-forms::default-form>