@if($table->hasHeader())
    <div class="omc-table-header">
        <h5 class="mb-0">{{ $table->headerTitle() }}</h5>
        <div class="d-flex align-items-center">
            @if($table->hasSearch())
            <div class="omc-form-search" style="margin-left: 12px">
                <input class="form-control" placeholder="{{ __('omc::table.search_placeholder') }}" v-model="queryParams.search">
                <div class="omc-form-search__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396l1.414-1.414l-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8s3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6s-6-2.691-6-6s2.691-6 6-6z"/><path fill="currentColor" d="M11.412 8.586c.379.38.588.882.588 1.414h2a3.977 3.977 0 0 0-1.174-2.828c-1.514-1.512-4.139-1.512-5.652 0l1.412 1.416c.76-.758 2.07-.756 2.826-.002z"/></svg>
                </div>
            </div>
            @endif
            @if($table->hasPerPageSelect())
                <select class="form-select" style="width: 80px; margin-left: 12px" v-model="queryParams.per_page" v-on:change="handlePageChange">
                    <option v-for="val in per_page_options" :key="val" :value="val" v-text="val"></option>
                </select>
            @endif
            @if($table->hasCreate())
                <a href="{{ $table->create_url }}" class="btn btn-success text-white" style="margin-left: 12px">
                    <span class="icon icon-xs" style="line-height: 0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 12 12"><path fill="currentColor" d="M6.5 1.75a.75.75 0 0 0-1.5 0V5H1.75a.75.75 0 0 0 0 1.5H5v3.25a.75.75 0 0 0 1.5 0V6.5h3.25a.75.75 0 0 0 0-1.5H6.5V1.75Z"/></svg>                        
                    </span>
                    <span style="margin-left: 6px">@lang('omc::table.create')</span>
                </a>
            @endif
        </div>
    </div>
@endif