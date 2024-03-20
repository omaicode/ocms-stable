<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.appearance.menus.store') }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuModalLabel">{{ __('messages.create_or_edit_menu') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="menu_id" id="menu_id">
                    <input type="hidden" name="position" id="position" value="{{ $position }}">
                    <x-forms::group
                        mode="input"
                        :label="__('messages.form.menu_name')"
                        :placeholder="__('messages.form.menu_name_placeholder')"
                        name="name"
                        :value="old('name', '')"
                        required
                    />
                    <x-forms::group
                        mode="input"
                        :label="__('messages.form.menu_url')"
                        :placeholder="__('messages.form.menu_url_placeholder')"
                        name="url"
                        :value="old('url', '')"
                        required
                    />
                    <x-forms::group
                        mode="input"
                        :label="__('messages.form.template')"
                        :placeholder="__('messages.form.template_placeholder')"
                        name="template"
                        :value="old('template', '')"
                    />
                    <x-forms::group
                        mode="select"
                        :label="__('messages.form.menu_parent')"
                        name="parent_id"
                    >
                        <option value="0">Root</option>
                        @foreach($root_menus as $menu)
                            <option value="{{ $menu->id }}">
                                {{ $menu->name }}
                            </option>
                        @endforeach
                    </x-forms::group>
                    <x-forms::group
                        mode="input"
                        type="number"
                        :label="__('messages.form.menu_order')"
                        name="order"
                        :value="old('order', 0)"
                    />
                    <x-forms::group
                        mode="switch"
                        name="active"
                        :label="__('messages.form.menu_active')"
                        :checked="old('active', 'on') == 'on'"
                    />
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-warning" id="cancelEdit" style="display: none">
                            <i class="fas fa-times"></i>
                            {{__('Cancel Edit')}}
                        </button>
                    </div>
                    <button type="submit" class="btn btn-success text-white">
                        <i class="fas fa-save"></i>
                        @lang('messages.save')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
