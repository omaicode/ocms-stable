<!-- Create Folder Modal -->
<div class="modal fade" id="mediaModalCreateFolder" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="mediaModalCreateFolderId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaModalCreateFolderId">{{__('Add Folder')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="mediaAddFolderForm" method="POST" no-loading="true">
                    <div class="form-group mb-3">
                        <label for="folder_name">{{__('Folder name')}} <i class="text-danger">*</i></label>
                        <input class="form-control" placeholder="{{ __('Enter folder name') }}" name="folder_name" required>
                    </div>
                    <div class="d-flex">
                        <div class="me-2">
                            <button type="submit" class="btn btn-success">
                                {{__('Apply')}}
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                {{__('Cancel')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Move file or folder Modal -->
<div class="modal fade" id="mediaModalMove" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="mediaModalMoveTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaModalMoveTitle">{{__('Move files or folders')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="mediaMoveForm" method="POST" no-loading="true">
                    <div class="form-group mb-3">
                        <label for="from">{{__('From')}} <i class="text-danger">*</i></label>
                        <input class="form-control" placeholder="Enter destination folder" name="from" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="to">{{__('To')}} <i class="text-danger">*</i></label>
                        <input class="form-control" name="to" placeholder="/new_name" required>
                    </div>
                    <div class="d-flex">
                        <div class="me-2">
                            <button type="submit" class="btn btn-success">
                                {{__('Apply')}}
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                {{__('Cancel')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete file or folder Modal -->
<div class="modal fade" id="mediaModalDelete" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="mediaModalDeleteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaModalDeleteTitle">{{__('Delete files or folders')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="mediaDeleteForm" method="POST" no-loading="true">
                    <div class="alert alert-danger">
                        <div class="fw-bold"><i class="fas fa-exclamation-circle"></i> {{__('Are you sure?')}}</div>
                        <span>{{__('Are you sure you want to delete these file(s) or folder(s)?')}}</span>
                    </div>
                    <div class="d-flex">
                        <div class="me-2">
                            <button type="submit" class="btn btn-danger">
                                {{__('Delete')}}
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                {{__('Cancel')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
