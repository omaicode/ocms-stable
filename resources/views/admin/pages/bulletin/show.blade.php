<div class="row">
    @include('admin.pages.bulletin.show-posts')
    @include('admin.pages.bulletin.show-filter')
</div>

<!-- Modal -->
<div class="modal fade" id="deletePostModal" tabindex="-1" role="dialog" aria-labelledby="deletePostModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="deletePostForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h4 class="modal-title" id="deletePostModalTitle">{{__('messages.delete_post_title')}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('messages.delete_confirm') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.close')}}</button>
                    <button type="submit" class="btn btn-danger">{{__('messages.delete')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        const deleteBaseUrl = '{{route('admin.bulletin.posts.index', $board->id)}}';
        const deletePostModal = bootstrap.Modal.getOrCreateInstance('#deletePostModal');

        $('.btn-delete-post').on('click', function(e) {
            const postId = e.currentTarget.getAttribute('data-post-id')
            $('#deletePostForm').attr('action', deleteBaseUrl + '/' + postId);
            deletePostModal.show();
        });
    };
</script>
