<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('messages.installed_themes')</h5>
    </div>
    <div class="card-body p-0">
        @foreach($themes as $key => $theme)
        @php
            $disabled = $current_theme ? $current_theme->name == $theme->name : ($themes->count() <= 1 ? true : false);
            $thumbnail = '/images/default-theme.png';
            if(\Illuminate\Support\Facades\File::exists($theme->getPath('thumbnail.png'))) {
                $thumbnail = "data:image/png;base64,".base64_encode(File::get($theme->getPath('thumbnail.png')));
            }
        @endphp
            <div class="p-3 mb-3 border-bottom">
                <div class="d-flex">
                    <div class="overflow-hidden rounded border" style="height: 120px; width: 170px">
                        <img src="{{$thumbnail}}" class="w-100 h-100 rounded" style="object-fit: cover">
                    </div>
                    <div class="ps-3 d-flex flex-column justify-content-between">
                        <div class="mb-2">
                            <div class="h6 mb-1">{{ \Illuminate\Support\Str::headline($theme->getName()) }}</div>
                            <div class="text-muted">{{ $theme->description }}</div>
                            <div class="text-muted">{{ $theme->version }}</div>
                        </div>
                        <div>
                            @if($disabled)
                                <button type="button" class="btn btn-secondary text-white btn-sm" disabled>
                                    <i class="fas fa-check"></i>
                                    @lang('messages.activated')
                                </button>                                
                            @else
                                <form method="POST" action="{{ route('admin.appearance.themes.set') }}">
                                    @csrf
                                    <button type="submit" name="theme" value="{{ $theme->name }}" class="btn btn-success text-white btn-sm">
                                        <i class="fas fa-check"></i>
                                        @lang('messages.active')
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>