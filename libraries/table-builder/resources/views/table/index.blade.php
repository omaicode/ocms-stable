@php
    $table_container_id = "table_".substr(\Illuminate\Support\Str::uuid(), 0, 8);
@endphp

<div class="omc-table-builder" id="{{ $table_container_id }}">
    @include("omc::table.{$theme}.table")
    <vue-confirm
        title="{{ __('omc::table.confirm_delete') }}"
        content="{{ __('omc::table.confirm_delete_content') }}"
        confirm_text="{{ __('omc::table.confirm_delete') }}"
        cancel_text="{{ __('omc::table.cancel') }}"        
        v-model="showConfirmDelete"
        :rounded="true"
        v-on:confirm="onDeleteComfirmed"
    />
</div>

@push('scripts')
<script>
    (function() {
        const {defineComponent, ref, reactive, createApp, watch, onMounted} = Vue;
        const data = {!! $table->getJsonTable() ?: "{}" !!};
        const {components} = window.TableBuilder
        const app = createApp(defineComponent({
            name: 'TableBuilder',
            setup() {
                const $toast          = components.Toastification.useToast()
                const columns         = ref(data.columns),
                    items             = ref(data.items),
                    last_page         = ref(data.last_page),
                    total             = ref(data.total),
                    first_item        = ref(data.first_item),
                    last_item         = ref(data.last_item),
                    queryParams       = reactive(data.queryParams),
                    options           = ref(data.options),
                    actions           = ref(data.actions),
                    per_page_options  = ref([5, 10, 25, 50, 75, 100]),
                    table_loading     = ref(false),
                    search_timer      = ref(null),
                    isComponentLoaded = ref(false),
                    showConfirmDelete = ref(false),
                    delete_url        = ref(data.delete_url),
                    deletingRows      = ref([]),
                    handlePageChange  = function() {
                        table_loading.value = true
                        axios.get('{{ request()->url() }}', {params: queryParams})
                        .then(function(resp) {
                            if(resp.status == 200 && resp.data.success) {
                                items.value = resp.data.data.items
                                last_page.value = resp.data.data.last_page
                                total.value = resp.data.data.total
                                first_item.value = resp.data.data.first_item
                                last_item.value = resp.data.data.last_item
                            }
                        })
                        .finally(function() {
                            table_loading.value = false
                        })
                    },
                    onDelete = function(row) {
                        showConfirmDelete.value = true
                        if(!deletingRows.value.includes(row.id)) {
                            deletingRows.value.push(row.id)
                        }
                    }
                    onDeleteComfirmed = function() {
                        table_loading.value = true
                        axios.post(delete_url.value, {rows: deletingRows.value})
                        .then(function(resp) {
                            if(resp.status == 200 && resp.data.success) {
                                handlePageChange()
                                $toast.success(`{{ __('omc::table.deleted') }}`)
                                deletingRows.value = []
                            } else {
                                $toast.error(resp.data.message)
                            }
                        })
                        .catch(function() {
                            $toast.error(`{{ __('omc::table.delete_error') }}`)
                        })
                        .finally(function() {
                            table_loading.value = false
                        })
                    },
                    parseUrl = function(url, row) {
                        let result_url = url

                        if(result_url) {
                            const splited = url.split('/')

                            splited.forEach(function(path) {
                                if(path.charAt(0) == ':') {
                                    result_url = result_url.replace(path, row[path.substring(1, path.length)])
                                }
                            })
                        }

                        return result_url
                    };

                watch(
                    function() {
                        return queryParams?.search
                    },
                    function() {
                        clearTimeout(search_timer.value);
                        search_timer.value = setTimeout(function() {
                            handlePageChange()
                        }, 500)
                    }
                );

                onMounted(function() {
                    isComponentLoaded.value = true
                })

                return {
                    columns,
                    items,
                    last_page,
                    total,
                    queryParams,
                    options,
                    first_item,
                    last_item,
                    per_page_options,
                    table_loading,
                    isComponentLoaded,
                    actions,
                    deletingRows,
                    showConfirmDelete,
                    handlePageChange,
                    onDelete,
                    onDeleteComfirmed,
                    parseUrl
                }
            }
        }));

        app.component('Paginate', components.Paginate)
        app.use(components.SimpleConfirm)
        app.use(components.Toastification.default, {timeout: 3500, position: 'bottom-right'})
        app.mount('#{{$table_container_id}}')
    })()
</script>
@endpush
