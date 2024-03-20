@php
    $admin = auth()->user();
    $admin_class = (new ReflectionClass($admin))->getName();
    $admin_class = str_replace("\\", "\\\\", $admin_class);
@endphp
<section id="post-detail">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="mb-3 mb-xl-0 mb-lg-0">
                    <h6>{{$post->title}}</h6>
                    <p class="text-muted small mb-0">{{$post->description ?: __('messages.no_description')}}</p>
                </div>
            </div>
            <hr>
            <!-- Author Information -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center mb-3">
                    <div class="overflow-hidden rounded-circle me-2" style="height: 48px; width: 48px">
                        <img src="{{adminAsset('img/no_profile.gif')}}" class="w-100 h-100 object-fit-cover" ale="avatar">
                    </div>
                    <div>
                        <div class="fw-bold">{{ optional($post->member)->name ?: ($post->anonymous_name ?: __('messages.guest')) }}</div>
                        <div class="text-muted small">
                            <span><i class="fas fa-comment"></i> {{$post->total_comment}}</span>
                            <span class="mx-1">|</span>
                            <span><i class="fas fa-eye"></i> {{$post->total_read}}</span>
                            <span class="mx-1">|</span>
                            <span><i class="fas fa-thumbs-up"></i> {{$post->total_like}}</span>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="text-muted small mb-1">
                        {{$post->created_at}}
                    </div>
                    <div id="like-button-area">
                        @if($post->isUserLiked($admin))
                            <button class="btn btn-sm btn-outline-dark btn-unlike">
                                <i class="fas fa-thumbs-down"></i>
                                {{__('messages.unlike')}}
                            </button>
                        @else
                            <button class="btn btn-sm btn-outline-warning btn-like">
                                <i class="fas fa-thumbs-up"></i>
                                {{__('messages.like_this_post')}}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <!-- Post Content -->
            <div class="w-100 overflow-scroll mb-3" style="max-width: 100%; max-height: 500px;">
                {!! $post->content !!}
            </div>
            <!-- Attachments -->
            <h6>{{__('messages.attachments')}}</h6>
            <hr>
            <ul class="list-group mb-3">
                @forelse($post->files as $file)
                    <li class="list-group-item border border-gray-200 small">
                        <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                            <div class="d-flex align-items-center w-auto overflow-hidden">
                                <div class="me-2 overflow-hidden rounded-2 text-center bg-gray-200 shadow-sm flex-shrink-0" style="width: 60px; height: 60px">
                                    @if(strpos($file->type, "image/") !== false)
                                        <img src="{{$file->url}}" style="width: 100%; height: 100%; object-fit: cover; display: inline-block">
                                    @endif
                                </div>
                                <div class="w-100">
                                    <div class="fw-bold">{{$file->file_name}}</div>
                                    <div class="text-muted small">{{round($file->size / 1024, 2)}}Kb</div>
                                    <div class="text-muted small">{{$file->type}}</div>
                                </div>
                            </div>
                            <div class="mt-2 mt-xl-0 mt-lg-0">
                                <a href="{{route('bulletin-board.download', $file->id)}}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted small p-0">
                        {{__('messages.no_data_available')}}
                    </li>
                @endforelse
            </ul>
            @if($post->childrens->count() > 0)
                <!-- Replies -->
                <h6>{{__('messages.replies')}}</h6>
                <hr>
                <ul class="list-group mb-3">
                    @foreach($post->childrens as $next_post)
                        <li class="list-group-item p-0 small">
                            <a href="{{ route('admin.bulletin.posts.show', ['board' => $board->id, 'post' => $next_post->id]) }}">
                               <i class="fas fa-turn-down-right"></i> {{ $next_post->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
            <!-- Comments -->
            <h6>{{__('messages.comments')}}</h6>
            <hr>
            <ul class="list-group mb-3">
            @forelse($post->comments as $cmt)
                @component('admin.pages.bulletin.post-comment', ['post' => $post, 'cmt' => $cmt, 'admin' => $admin])@endcomponent
            @empty
                <li class="list-group-item text-muted small p-0">
                    {{__('messages.no_data_available')}}
                </li>
            @endforelse
            </ul>
            <form method="POST" action="{{ route('admin.bulletin.posts.comment', $post->id) }}">
                @csrf
                <x-forms::group
                    mode="textarea"
                    name="content"
                    :placeholder="__('messages.enter_comment_content')"
                    required
                />
                <div class="d-flex flex-wrap">
                    <button type="submit" class="btn btn-info text-white me-2">
                        {{__('messages.submit')}}
                    </button>
                    <a
                        href="{{ route('admin.bulletin.posts.create', ['board' => $board->id, 'reply_to' => $post->id]) }}"
                        class="btn btn-info"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Reply"
                    >
                        <i class="fas fa-reply"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    window.onload = function () {
        function toggleLikePost(element, comment_id = null, onSuccess = null) {
            $.ajax({
                method: "POST",
                url: "{{route('admin.bulletin.posts.like', $post->id)}}",
                headers: {"X-CSRF-TOKEN": '{{csrf_token()}}'},
                data: {
                    reference_type: encodeURIComponent("{{ $admin_class }}"),
                    reference_id: {{$admin->id}},
                    comment_id
                },
                beforeSend: function() {
                    element.currentTarget.style.pointerEvents = 'none';
                    element.currentTarget.classList.add('disabled');
                },
                complete: function() {
                    if(typeof onSuccess === 'function') {
                        onSuccess();
                    }
                }
            })
        }

        $(document).on('click', '.btn-like', function(e) {
            toggleLikePost(e, null, function() {
                $('#like-button-area').html(`
                <button class="btn btn-sm btn-outline-dark btn-unlike">
                    <i class="fas fa-thumbs-down"></i>
                    {{__('messages.unlike')}}
                </button>`);
            });
        });

        $(document).on('click', '.btn-unlike', function(e) {
            toggleLikePost(e, null, function() {
                $('#like-button-area').html(`
                <button class="btn btn-sm btn-outline-warning btn-like">
                    <i class="fas fa-thumbs-up"></i>
                    {{__('messages.like_this_post')}}
                </button>`);
            });
        });

        $(document).on('click', '.btn-like-comment', function(e) {
            const cmt_id = e.currentTarget.getAttribute('data-comment-id');
            toggleLikePost(e, cmt_id, function() {
                e.currentTarget.parentElement.innerHTML = `
                <a href="javascript:" class="small me-2 btn-unlike-comment" data-comment-id="${cmt_id}">
                    <i class="fas fa-thumbs-down"></i>
                    {{__('messages.unlike')}}
                </a>`;
            });
        });

        $(document).on('click', '.btn-unlike-comment', function(e) {
            const cmt_id = e.currentTarget.getAttribute('data-comment-id');
            toggleLikePost(e, cmt_id, function() {
                e.currentTarget.parentElement.innerHTML = `
                <a href="javascript:" class="small me-2 btn-like-comment" data-comment-id="${cmt_id}">
                    <i class="fas fa-thumbs-up"></i>
                    {{__('messages.like')}}
                </a>`;
            });
        });

        $(document).on('click', '.btn-answer-comment', function(e) {
            const cmt_id = e.currentTarget.getAttribute('data-comment-id');
            $('#reply_to').val(cmt_id);
        });
    };
</script>
