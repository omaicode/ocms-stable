@extends('layout')

@section('content')
    [partial name="navbar"][/partial]
    
    [partial 
        name="header" 
        title="OCMS"
        subtitle="Content Management and Theme System"
        image="{{ theme_asset('img/bg-index.jpg') }}"
    ][/partial]
    
    @php
        $per_page = 5;
        $page = 1;
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                @foreach (get_blog_posts($per_page, $page) as $post)
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
                    <a class="btn btn-primary float-right" href="/posts">View All Posts &rarr;</a>
                </div>                
            </div>
        </div>
    </div>

    [partial name="footer"][/partial]
@endsection
