<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="{{ route('admin.dashboard') }}">
        <img class="navbar-brand-dark" src="{{ uploadPath(config('app.logo')) }}" alt="logo" />
        <img class="navbar-brand-light" src="{{ uploadPath(config('app.logo-dark')) }}" alt="logo" />
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
        <div
            class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">
                    <img src="{{ auth()->user()->avatar_url }}"
                        class="card-img-top rounded-circle border-white" alt="{{ auth()->user()->name }}">
                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">Hi, {{ auth()->user()->name }}</h2>
                    <a href="{{ route('admin.logout') }}"
                        class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                        <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Sign Out
                    </a>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item d-none d-xl-block d-lg-block">
                <a href="javacsript:;" class="nav-link d-flex align-items-center disabled">
                    <span class="sidebar-text">
                        <img src="{{ uploadPath(config('app.logo')) }}" class="w-auto" height="28" alt="{{ config('app.name') }} Logo">
                    </span>
                </a>
            </li>
            @foreach(\Menu::all() as $menu)
                @if(count($menu['children']) > 0)
                    <li class="nav-item">
                        <span
                            class="nav-link d-flex justify-content-between align-items-center {{ !$menu['active'] ? 'collapsed' : '' }}"
                            data-bs-toggle="collapse"
                            data-bs-target="#submenu-{{ $menu['id'] }}"
                            aria-expanded="{{ $menu['active'] ? 'true' : 'false' }}"
                        >
                            <span>
                                <span class="sidebar-icon">
                                    <i class="{{ $menu['icon'] }} icon icon-xs me-2"></i>
                                </span>
                                <span class="sidebar-text">{{ __($menu['name']) }}</span>
                            </span>
                            <span class="link-arrow">
                                <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            </span>
                        </span>
                        <div class="multi-level collapse {{ $menu['active'] ? "show" : "" }}" role="list" id="submenu-{{ $menu['id'] }}" aria-expanded="{{ $menu['active'] ? "true" : "false" }}">
                            <ul class="flex-column nav">
                                @foreach($menu['children'] as $sub_menu)
                                <li class="nav-item {{ $sub_menu['active'] ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ $sub_menu['url'] }}">
                                        <span class="sidebar-text">{{ __($sub_menu['name']) }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item  {{ $menu['active'] ? 'active' : '' }}">
                        <a href="{{ $menu['url'] }}" class="nav-link d-flex justify-content-between align-items-center">
                            <span>
                                @if($menu['icon'])
                                    <span class="sidebar-icon">
                                        <i class="{{ $menu['icon'] }} icon icon-xs me-2"></i>
                                    </span>
                                @endif
                                <span class="sidebar-text">{{ __($menu['name']) }}</span>
                            </span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</nav>
