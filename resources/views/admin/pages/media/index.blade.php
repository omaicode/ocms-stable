<div id="cms-media">
    <div class="media-wrapper">
        <div class="media-wrapper--header">
            <div class="media-wrapper--header_left">
                <div>
                    <button type="button" class="btn btn-sm btn-success text-white me-2" data-type="media-button" data-trigger="selectFiles">
                        <i class="fas fa-upload"></i>
                        {{__('Upload')}}
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success text-white me-2" data-bs-toggle="custom-modal" data-bs-target="#mediaModalCreateFolder">
                        <i class="fas fa-folder"></i>
                        {{__('Add Folder')}}
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-secondary me-2" data-type="media-button" data-trigger="refreshData">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
                <div>
                    <div class="input-group w-auto me-2">
                        <button type="button" class="btn btn-sm btn-secondary" id="mediaMoveButton" data-bs-toggle="custom-modal" data-bs-target="#mediaModalMove" disabled>
                            <i class="fas fa-arrows-left-right"></i>
                            {{__('Move')}}
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" id="mediaDeleteButton" data-bs-toggle="custom-modal" data-bs-target="#mediaModalDelete" disabled>
                            <i class="fas fa-trash"></i>
                            {{__('Delete')}}
                        </button>
                    </div>
                </div>
                <div style="display: none">
                    <div class="input-group w-auto">
                        <button type="button" class="btn btn-sm btn-secondary btn-change-view" data-type="media-button" data-trigger="viewMode" data-view-mode="grid">
                            <i class="fas fa-list"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary btn-change-view" data-type="media-button" data-trigger="viewMode" data-view-mode="list">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary btn-change-view" data-type="media-button" data-trigger="viewMode" data-view-mode="tiles">
                            <i class="fas fa-th-large"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div  class="media-wrapper--header_right">
                <div class="input-group w-auto">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control form-control-sm" placeholder="{{__('Search')}}" id="mediaSearchIpn">
                </div>
            </div>
        </div>
        <div class="media-wrapper--notify" id="notify" style="display: none">
            <div>
                <i class="fas fa-exclamation-circle text-danger" v-if="!notify.success"></i>
                <i class="fas fa-check-circle text-success" v-else></i>
                notify.message
            </div>
        </div>
        <div class="media-wrapper--loading" id="mediaLoading" style="display: none">
            <div class="d-flex align-items-center justify-content-center w-100 h-100">
                <div class="text-center">
                    <div class="spinner-border text-warning"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div id="mediaLoadingText" class="ms-2">Loading...</div>
                </div>
            </div>
        </div>
        <div class="media-wrapper--content">
            @include('admin.pages.media.section-display')
            @include('admin.pages.media.section-grid')
        </div>
        <input id="mediaFileInput" type="file" style="display: none" multiple>
    </div>
</div>

@if(!isset($no_modals))
    @include('admin.pages.media.modals')
@endif
