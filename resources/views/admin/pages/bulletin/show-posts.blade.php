<div class="col-12 col-xl-9 col-lg-8">
    <div class="card mb-3">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center p-3">
                <h5 class="mb-0">
                    {{$board->name}}
                </h5>
                <a href="{{ route('admin.bulletin.posts.create', ['board' => $board->id]) }}" class="btn btn-success text-white">
                    <i class="fas fa-plus"></i>
                    {{__('messages.add_new_post')}}
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('messages.title')}}</th>
                            <th>{{__('messages.author')}}</th>
                            <th>{{__('messages.status')}}</th>
                            <th>{{__('messages.created_at')}}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td class="align-middle">{{ $post->id }}</td>
                                <td>
                                    <a href="{{ route('admin.bulletin.posts.show', ['board' => $board->id, 'post' => $post->id]) }}" class="text-lines-3 text-info fw-bold" style="width: 300px; text-decoration: underline">{{$post->title }}</a>
                                </td>
                                <td class="align-middle">{{ optional($post->member)->name ?: ($post->anonymous_name ?: 'Guest') }}</td>
                                <td class="align-middle">{!! optional($post->status)->toHtml() ?: '-' !!}</td>
                                <td class="align-middle">{{ $post->created_at }}</td>
                                <td class="align-middle">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.bulletin.posts.edit', ['board' => $board->id, 'post' => $post->id]) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:" class="btn btn-sm btn-danger btn-delete-post" data-post-id="{{$post->id}}">
                                            <i class="fas fa-trash text-white"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @if($post->childrens->count() > 0)
                                @foreach($post->childrens as $post_child)
                                    <tr>
                                        <td class="align-middle"></td>
                                        <td>
                                            <a href="{{ route('admin.bulletin.posts.show', ['board' => $board->id, 'post' => $post_child->id]) }}" class="text-lines-3 text-info fw-bold" style="width: 300px; text-decoration: underline">{{$post_child->title }}</a>
                                        </td>
                                        <td class="align-middle">{{ optional($post_child->member)->name ?: ($post_child->anonymous_name ?: 'Guest') }}</td>
                                        <td class="align-middle">{!! optional($post_child->status)->toHtml() ?: '-' !!}</td>
                                        <td class="align-middle">{{ $post_child->created_at }}</td>
                                        <td class="align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.bulletin.posts.edit', ['board' => $board->id, 'post' => $post_child->id]) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:" class="btn btn-sm btn-danger btn-delete-post" data-post-id="{{$post_child->id}}">
                                                    <i class="fas fa-trash text-white"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @if($post_child->childrens->count() > 0)
                                        @foreach($post_child->childrens as $post_child2)
                                            <tr>
                                                <td class="align-middle"></td>
                                                <td>
                                                    <a href="{{ route('admin.bulletin.posts.show', ['board' => $board->id, 'post' => $post_child2->id]) }}" class="text-lines-3 text-info fw-bold" style="width: 300px; text-decoration: underline">{{$post_child2->title }}</a>
                                                </td>
                                                <td class="align-middle">{{ optional($post_child2->member)->name ?: ($post_child2->anonymous_name ?: 'Guest') }}</td>
                                                <td class="align-middle">{!! optional($post_child2->status)->toHtml() ?: '-' !!}</td>
                                                <td class="align-middle">{{ $post_child2->created_at }}</td>
                                                <td class="align-middle">
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.bulletin.posts.edit', ['board' => $board->id, 'post' => $post_child2->id]) }}" class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:" class="btn btn-sm btn-danger btn-delete-post" data-post-id="{{$post_child2->id}}">
                                                            <i class="fas fa-trash text-white"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td class="align-middle" colspan="6">
                                    <div class="text-center text-muted small">
                                        <div class="mb-2"><i class="fas fa-border fa-box-open fa-2x"></i></div>
                                        @lang('messages.no_data_available')
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small">
                        @lang('pagination.page_of', ['number' => $posts->currentPage(), 'total' => $posts->lastPage()])
                    </div>
                    {!! $posts->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
