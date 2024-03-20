<article class="post-preview">
    <a href="{{ $data->url ?: '#' }}">
        <h2 class="post-title">{{ $data->title }}</h2>
        <h3 class="post-subtitle">{{ $data->subtitle }}</h3>
    </a>
    <p class="post-meta">
        {{ $data->date }}
    </p>
</article>