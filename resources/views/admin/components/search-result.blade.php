<div class="my-3">
    <ul class="m-0 p-0" style="list-style-type: none">
        <li class="py-1"><h5 class="text-white">{{ __('Kết quả tìm kiếm') }}</h5></li>
        @forelse ($result as $row)
            <li class="py-1">
                @if($row['type'] == 'page')
                    <span class="me-2">{{ __('Trang') }}</span>
                @elseif($row['type'] == 'category')
                    <span class="me-2">{{ __('Danh mục bài viết') }}</span>
                @else
                    <span class="me-2">{{ __('Bài viết') }}</span>
                @endif
                <span class="me-2">{!! svg('icons/bold/arrow-right-icon') !!}</span>
                <a href="/{{ $row['slug'] }}" target="_blank" class="text-decoration-none fw-semibold">
                    {{$row['name']}}
                </a>
            </li>
        @empty
            <li class="text-center text-white py-1 fw-semibold">
                <i>{{ __('Không tìm thấy kết quả.') }}</i>
            </li>
        @endforelse
    </ul>
</div>