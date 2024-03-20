@php
    $positions = \App\Enums\MenuPositionEnum::asSelectArray();
@endphp

<style>
    .list-group-item:not(:first-child) {
        border-top: 0 !important;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('messages.menus') }}</h5>
                    <div class="d-flex align-items-center">
                        <select
                            class="form-select form-select-sm me-2"
                            name="position"
                            onchange="location.href = '{{ request()->route('admin.appearance.menus.index') }}?position=' + this.value"
                            style="min-width: 160px"
                        >
                            @foreach($positions as $value => $name)
                            <option value="{{ $value }}" @if(request()->query('position', 0) == $value)selected @endif>
                                {{ __('messages.'.\Illuminate\Support\Str::slug($name)) }}
                            </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-success text-white btn-sm w-100" id="createNewMenu" @if(config('admin.is_demo')) disabled @endif>
                            <i class="fas fa-plus"></i>
                            {{ __('messages.create') }}
                        </button>
                    </div>
                </div>
            </div>
            <ul class="list-group list-group-flush menu" data-parent="root">
                @forelse ($menus as $menu)
                    @component('admin.pages.menu.menu-item', ['menu' => $menu])@endcomponent
                @empty
                    <li class="list-group-item hover-none border-light rounded-none">
                        <div class="alert alert-info text-center">
                            {{ __('messages.no_menu_item') }}
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
