<script src="{{ asset('vendor/sortable/sortable.min.js') }}"></script>
<script>
    function updateMenu(ordered_list, item_id, parent_id = null) {
        axios
        .post('{{ route('admin.appearance.menus.update-order') }}', {
            item_id,
            ordered_list,
            parent_id: ['root', '0'].includes(parent_id || '') ? null : parent_id,
            position: '{{ request()->get('position', 0)  }}'
        })
        .then(function(resp) {
            if(resp.data && resp.data.success) {
                console.log(`Saved menu order`);
            }
        })
    }

    (function() {
        "use strict"
        let editing = false;
        const root_menus = {!! json_encode($root_menus->toArray()) !!};
        const language  = '{{ request()->getLocale() }}';
        const editItems = document.querySelectorAll(".btn-menu-edit");
        const deleteItems = document.querySelectorAll(".btn-menu-delete");
        const deleteUrl = '{{ route('admin.appearance.menus.destroy') }}';
        const menuElements = {};

        const nameEl = document.querySelector('.form-control[name="name"]');
        const urlEl = document.querySelector('.form-control[name="url"]');
        const templateEl = document.querySelector('.form-control[name="template"]');
        const parentEl = document.querySelector('.form-select[name="parent_id"]');
        const orderEl = document.querySelector('.form-control[name="order"]');
        const activeEl = document.querySelector('.form-check-input[name="active"]');
        const menuIdEl = document.getElementById('menu_id');
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('menuModal'));
        let draggedDepth = 0;

        function toggleEditing(is_edit = true) {
            editing = is_edit;
            if(editing) {
                document.getElementById('cancelEdit').style.display = "block";
            } else {
                document.getElementById('cancelEdit').style.display = "none";
            }

            let options = `<option value="0">Root</option>`;
            for(const menu of root_menus) {
                if(editing && menuIdEl.value == menu.id) {
                    continue;
                } else {
                    options += `<option value="${menu.id}" ${parentEl.value == menu.id ? 'selected' : ''}>${menu.name}</option>`;
                }
            }

            parentEl.innerHTML = options;
            parentEl.tomselect.sync();
        }

        document.getElementById('cancelEdit').addEventListener('click', function(e) {
            editing = false;
            e.target.style.display = "none";

            nameEl.value = "";
            urlEl.value = "";
            parentEl.value = "";
            orderEl.value = 0;
            activeEl.checked = true;
            menuIdEl.value = null;
            templateEl.value = "";

            toggleEditing(false);
            modal.hide();
        });

        document.getElementById('createNewMenu').addEventListener('click', function(e) {
            nameEl.value = "";
            urlEl.value = "";
            parentEl.value = "";
            orderEl.value = 0;
            activeEl.checked = true;
            menuIdEl.value = null;
            templateEl.value = "";

            toggleEditing(false);
            modal.show();
        });

        document.querySelectorAll('.menu').forEach(function(menu) {
            menuElements['menu_' + menu.getAttribute('data-parent')] = new Sortable(menu, {
                group: {
                    name: 'nested',
                    pull: function (to, from) {
                        const toLvl = $(to.el).parents('.menu').length;
                        if(toLvl > 1) {
                            return false;
                        }
                        return draggedDepth <= 0;
                    },
                },
                handle: '.menu-arrow',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onSort: function (event) {
                    const source = event.from;
                    const target = event.to;
                    const item = event.item;
                    const ordered_list = menuElements['menu_' + target.getAttribute('data-parent')].toArray();
                    updateMenu(ordered_list, item.getAttribute('data-id'), target.getAttribute('data-parent'));
                },
                onMove: function(evt) {
                    draggedDepth = $(evt.dragged).find('.menu > *').length;
                }
            });
        });

        editItems.forEach(function(item) {
            item.addEventListener("click", function(el) {
                const data = JSON.parse(el.currentTarget.getAttribute('data-json'));

                nameEl.value = data.name;
                urlEl.value = data.url;
                parentEl.value = data.parent_id || "0";
                orderEl.value = data.order;
                menuIdEl.value = data.id;
                activeEl.checked = data.active;
                templateEl.value = data.template;

                toggleEditing();
                modal.show();
            })
        });

        deleteItems.forEach(function(item) {
            item.addEventListener("click", function(el) {
                const id = el.currentTarget.getAttribute('data-id');

                Swal.fire({
                    title: "{{ __('messages.are_you_sure') }}",
                    text: "{{ __('messages.delete_confirm') }}",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('messages.confirm') }}",
                    cancelButtonText: "{{ __('messages.cancel') }}",
                }).then(function(result) {
                    if(result.isConfirmed) {
                        axios.delete(deleteUrl + `/${id}`)
                        .then(resp => {
                            if(resp.data && resp.data.success) {
                                Notyf.success(`{{ __('messages.deleted') }}`);
                                document.getElementById('menu_item_' + id).remove();
                            } else {
                                Notyf.error(`{{ __('messages.something_went_wrong') }}`);
                            }
                        })
                    }
                })
            });
        });
    })()
</script>
