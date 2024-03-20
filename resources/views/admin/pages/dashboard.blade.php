<div class="row my-4">
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-3 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-success rounded me-4 me-sm-0">
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                        </div>
                        <div class="d-sm-none">
                            <h2 class="h5">@lang('messages.dashboard.total_visitors')</h2>
                            <h3 class="fw-extrabold mb-1">{{ $analytics->sum('totalUsers') }}</h3>
                        </div>
                    </div>
                    <div class="col-12 col-xl-9 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h6 text-gray-400 mb-0">@lang('messages.dashboard.total_visitors')</h2>
                            <h3 class="fw-extrabold mb-2">{{ $analytics->sum('totalUsers') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-3 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-warning rounded me-4 me-sm-0">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 48 48"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4"><path stroke-linejoin="round" d="M15 26V15a3 3 0 1 1 6 0v11"/><path stroke-linejoin="round" d="M39 25v6.5C39 37.851 33.851 43 27.5 43h-1C20.149 43 15 37.851 15 31.5V25"/><path stroke-linejoin="round" d="M21 29v-5a3 3 0 1 1 6 0v5m0 0v-5a3 3 0 1 1 6 0v5m0 0v-5a3 3 0 1 1 6 0v5"/><path d="M28 15a9.967 9.967 0 0 0-1.959-5.945A9.986 9.986 0 0 0 18 5a9.986 9.986 0 0 0-8.042 4.055A9.968 9.968 0 0 0 8 15"/></g></svg>
                        </div>
                        <div class="d-sm-none">
                            <h2 class="h5">@lang('messages.dashboard.total_views')</h2>
                            <h3 class="fw-extrabold mb-1">{{ $analytics->sum('screenPageViews') }}</h3>
                        </div>
                    </div>
                    <div class="col-12 col-xl-9 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h6 text-gray-400 mb-0">@lang('messages.dashboard.total_views')</h2>
                            <h3 class="fw-extrabold mb-2">{{ $analytics->sum('screenPageViews') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-3 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-info rounded me-4 me-sm-0">
                            <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="currentColor"><path d="M5.507 4.048A3 3 0 0 1 7.785 3h8.43a3 3 0 0 1 2.278 1.048l1.722 2.008A4.533 4.533 0 0 0 19.5 6h-15c-.243 0-.482.02-.715.056l1.722-2.008Z"/><path fill-rule="evenodd" d="M1.5 10.5a3 3 0 0 1 3-3h15a3 3 0 1 1 0 6h-15a3 3 0 0 1-3-3Zm15 0a.75.75 0 1 1-1.5 0a.75.75 0 0 1 1.5 0Zm2.25.75a.75.75 0 1 0 0-1.5a.75.75 0 0 0 0 1.5ZM4.5 15a3 3 0 1 0 0 6h15a3 3 0 1 0 0-6h-15Zm11.25 3.75a.75.75 0 1 0 0-1.5a.75.75 0 0 0 0 1.5ZM19.5 18a.75.75 0 1 1-1.5 0a.75.75 0 0 1 1.5 0Z" clip-rule="evenodd"/></g></svg>
                        </div>
                        <div class="d-sm-none">
                            <h2 class="h5">@lang('messages.dashboard.cms_version')</h2>
                            <h3 class="fw-extrabold mb-1">{{ $module->get('version', '1.0.0') }}</h3>
                        </div>
                    </div>
                    <div class="col-12 col-xl-9 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h6 text-gray-400 mb-0">@lang('messages.dashboard.cms_version')</h2>
                            <h3 class="fw-extrabold mb-2">{{ $module->get('version', '1.0.0') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5>{{ __('messages.statistics') }}</h5>
            </div>
            <div class="card-body" style="position: relative; height: 300px; width: 100%">
                <canvas id="session-chart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6 col-lg-6 mb04">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('messages.dashboard.recent_activities')</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{!! $activity->action_text !!}</td>
                                <td>{!! $activity->created_at->format('Y-m-d H:i:s') !!}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">@lang('messages.dashboard.no_activities')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6 col-lg-6 mb04">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('messages.dashboard.changelog')</h5>
            </div>
            <div class="card-body">
                {!! $changelog !!}
            </div>
        </div>
    </div>
</div>
