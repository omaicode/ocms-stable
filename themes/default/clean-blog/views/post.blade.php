@extends('layout')

@section('content')
    [partial name="navbar"][/partial]

    [partial 
        name="header" 
        title="{{$title}}"
        subtitle="{{$short_description}}"
        image="/themes/default/clean-blog/img/bg-post.jpg"
    ][/partial]  

    <div class="container">
        <div class="row">
            <div class="col-lg- 8 col-md-10 mx-auto">
                {!! isset($content) ? $content : 'This is post content' !!}
            </div>
        </div>
    </div>
    
    [partial name="footer"][/partial]
@endsection
