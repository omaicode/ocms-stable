<form method="POST" action="{{ route('admin.localization.switch') }}">
    @csrf
    <div class="dropstart open">
        @php
            $languages = \App\Models\Language::where('active', true)->get();
            $current_lang = $languages->where('code', app()->getLocale())->first();
        @endphp
        <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" id="languageSwitch" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="d-flex items-center">
                <div class="me-2"><img src="{{ $current_lang->icon }}" height="20"></div>
                <div>{{ $current_lang->name }}</div>
            </div>
        </button>
        <div class="dropdown-menu" aria-labelledby="languageSwitch">
            @foreach($languages as $lang)
                <button type="submit" name="language" value="{{ $lang->code }}" class="dropdown-item">
                    <div class="d-flex items-center">
                        <div class="me-2"><img src="{{ $lang->icon }}" height="20"></div>
                        <div>{{ $lang->name }}</div>
                    </div>
                </button>
            @endforeach
        </div>
    </div>
</form>