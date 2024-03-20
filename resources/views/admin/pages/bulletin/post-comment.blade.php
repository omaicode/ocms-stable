<li class="list-group-item border-bottom border-gray-200 small @if(isset($is_child)) ps-5 @endif" id="comment_{{$cmt->id}}">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex">
            <div class="overflow-hidden rounded-circle me-2" style="height: 40px; width: 40px">
                <img src="{{adminAsset('img/no_profile.gif')}}" class="w-100 h-100 object-fit-cover" alt="avatar">
            </div>
            <div>
                <div class="fw-bold">
                    <span class="me-2">{{ optional($cmt->member)->name ?: ($cmt->anonymous_name ?: __('messages.guest')) }}</span>
                    <span class="text-muted small">
                        <i class="fas fa-clock"></i>
                        {{$cmt->created_at}}
                    </span>
                </div>
                <div class="w-100" style="white-space: pre;">{{$cmt->content}}</div>
                <div>
                    <span class="like-comment-area">
                        @if($cmt->isUserLiked($admin))
                            <a href="javascript:" class="small me-2 btn-unlike-comment" data-comment-id="{{$cmt->id}}">
                                <i class="fas fa-thumbs-down"></i>
                                {{__('messages.unlike')}}
                            </a>
                        @else
                            <a href="javascript:" class="small me-2 btn-like-comment" data-comment-id="{{$cmt->id}}">
                                <i class="fas fa-thumbs-up"></i>
                                {{__('messages.like')}}
                            </a>
                        @endif
                    </span>
                    @if(!isset($is_child))
                        <a class="small" data-bs-toggle="collapse" href="#replyTo{{$cmt->id}}" aria-expanded="false" aria-controls="replyTo{{$cmt->id}}">
                            <i class="fas fa-reply"></i>
                            {{__('messages.answer')}}
                        </a>
                    @endif
                </div>
                <div class="collapse mt-2" id="replyTo{{$cmt->id}}">
                    <form method="POST" action="{{ route('admin.bulletin.posts.comment', $post->id) }}">
                        @csrf
                        <input type="hidden" name="reply_to" id="reply_to" value="{{ $cmt->id }}">
                        <x-forms::group
                            mode="textarea"
                            name="content"
                            :placeholder="__('messages.enter_comment_content')"
                            required
                        />
                        <button type="submit" class="btn btn-info text-white">
                            {{__('messages.submit')}}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</li>
@if(count($cmt->childrens) > 0)
    @foreach($cmt->childrens as $child)
        @component('admin.pages.bulletin.post-comment', ['post' => $post, 'cmt' => $child, 'is_child' => true, 'admin' => $admin])@endcomponent
    @endforeach
@endif
