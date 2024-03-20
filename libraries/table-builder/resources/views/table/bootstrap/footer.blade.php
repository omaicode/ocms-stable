@if($table->hasFooter())
    <div class="omc-table-footer">
        <div class="d-flex justify-content-between">
            <div class="text-muted small">
                {!! __('omc::table.showing') !!}
            </div>
            <div>
                <paginate
                    class="mb-0"
                    :page-count="last_page"
                    :click-handler="handlePageChange"
                    v-model="queryParams.page"
                />
            </div>
        </div>
    </div>
@endif