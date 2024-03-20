<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('messages.theme_options')</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-xl-2 col-lg-3">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item mb-2 pe-0">
                        <a 
                            class="nav-link @if(request()->routeIs('admin.appearance.theme-options'))active @endif" 
                            href="{{ route('admin.appearance.theme-options') }}"
                        >
                            @lang('messages.general')
                        </a>
                    </li>
                    <li class="nav-item mb-2 pe-0">
                        <a 
                            class="nav-link @if(request()->routeIs('admin.appearance.theme-options.seo'))active @endif" 
                            href="{{ route('admin.appearance.theme-options.seo') }}"
                        >
                            @lang('messages.seo')
                        </a>
                    </li>
                    <li class="nav-item mb-2 pe-0">
                        <a 
                            class="nav-link @if(request()->routeIs('admin.appearance.theme-options.socials'))active @endif" 
                            href="{{ route('admin.appearance.theme-options.socials') }}"
                        >
                            @lang('messages.social_links')
                        </a>
                    </li>
                    <li class="nav-item pe-0">
                        <a 
                            class="nav-link @if(request()->routeIs('admin.appearance.theme-options.images'))active @endif" 
                            href="{{ route('admin.appearance.theme-options.images') }}"
                        >
                            @lang('messages.images')
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-xl-10 col-lg-9">
                <form method="POST" action="{{ route('admin.appearance.theme-options.save') }}" enctype="multipart/form-data">
                @csrf                
                {{ $slot }}
                <button type="submit" class="btn btn-success text-white">
                    <i class="fas fa-save"></i>
                    @lang('messages.form.save_options')
                </button>
                </form>
            </div>
        </div>
    </div>
</div>