<div class="omc-table-body">
    @if($table->hasLoading())
    <div class="omc-table-loading" v-show="table_loading">
        <div class="d-flex justify-content-center align-items-center text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
        </div>
    </div>
    @endif
    <div class="table-responsive">
        <table class="table table-flush table-hover">
            <thead class="thead-light">
                <tr>
                    <th 
                        class="align-middle"
                        scope="col"
                        v-for="(column, index) in columns"
                        :key="`th_${index}`"
                        v-bind="column.attributes"
                        v-text="column.label"
                    >
                    </th>
                    <th scope="col" class="align-middle text-center" v-if="options.show_actions">
                        <span style="font-size: 18px; line-height: 0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.75 12.25h10.5m-10.5-4h10.5m-10.5-4h10.5"/></svg>                            
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, row_idx) in items" :key="`tr_${row_idx}`">
                    <td 
                        class="align-middle" 
                        scope="row" 
                        v-for="(column, index) in columns"
                        :key="`td_${index}`"                          
                        v-bind="column.attributes"
                        v-html="row[column.field]"
                    >
                    </td>
                    <td scope="row" class="align-middle text-center" v-if="options.show_actions">
                        <div class="dropdown open" style="position: static">
                            <button 
                                class="btn btn-dropdown dropdown-toggle" 
                                type="button" 
                                data-bs-toggle="dropdown" 
                                data-bs-boundary="viewport"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><circle cx="8" cy="2.5" r=".75"/><circle cx="8" cy="8" r=".75"/><circle cx="8" cy="13.5" r=".75"/></g></svg>
                            </button>
                            <div class="dropdown-menu">
                                <template v-for="(action, aidx) in actions" :key="`taction_${aidx}`">
                                <div 
                                    class="dropdown-item" 
                                    style="cursor: pointer"
                                    v-bind="action.attributes"
                                    v-on:click="onDelete(row)"
                                    v-if="action.type == 'delete'"
                                >
                                    <span class="action-icon" v-if="action.icon" v-html="action.icon"></span>
                                    <span v-text="action.text"></span>
                                </div>                                    
                                <a 
                                    class="dropdown-item" 
                                    :href="parseUrl(action.url, row)"
                                    v-bind="action.attributes"
                                    v-else
                                >
                                    <span class="action-icon" v-if="action.icon" v-html="action.icon"></span>
                                    <span v-text="action.text"></span>
                                </a>
                                </template>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="items.length <= 0">
                    <td :colspan="options.total_columns">
                        <div class="text-center">
                            @lang('omc::table.empty')
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>