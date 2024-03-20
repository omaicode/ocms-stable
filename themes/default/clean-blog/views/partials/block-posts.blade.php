@php
    $per_page = 5;
    $page = request()->get('page', 1);
    $posts = get_blog_posts($per_page, $page);
@endphp
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            @foreach ($posts as $post)
                [partial
                name="post-preview"
                url="/{{ $post->slug }}"
                title="{{ $post->title }}"
                subtitle="{{ $post->short_description }}"
                date="{{ $post->created_at }}"
                ][/partial]
            @endforeach
            <!-- Pager -->
            <div class="clearfix">
                @if ($posts->previousPageUrl())
                    <a class="btn btn-primary" href="{{ $posts->previousPageUrl() }}">Previous &rarr;</a>
                @endif

                @if ($posts->hasPages())
                    <a class="btn btn-primary float-right" href="{{ $posts->url($posts->currentPage() + 1) }}">Next &rarr;</a>
                @endif
            </div>                
        </div>
    </div>
</div>