<div class="row">
    <div class="col-md-4 d-none d-md-block">
        <div class="card">
            <div class="card-body">
                <nav class="nav flex-column nav-pills nav-gap-y-1">
                    <a href="#backend" data-bs-toggle="tab" class="nav-item nav-link has-icon nav-link-faded mb-2 active">
                        <i class="fas fa-globe icon icon-sm"></i>
                        @lang('messages.general.backend')
                    </a>
                    <a href="#google_analytics" data-bs-toggle="tab" class="nav-item nav-link has-icon nav-link-faded mb-2">
                        <i class="fab fa-google icon icon-sm"></i>
                        @lang('messages.general.google_analytics')
                    </a>
                    <a href="#maintenance" data-bs-toggle="tab" class="nav-item nav-link has-icon nav-link-faded mb-2">
                        <i class="fas fa-plug icon icon-sm"></i>
                        @lang('messages.general.maintenance')
                    </a>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom mb-3 d-flex d-md-none">
                <ul class="nav nav-tabs card-header-tabs nav-gap-x-1" role="tablist">
                    <li class="nav-item">
                        <a href="#backend" data-bs-toggle="tab" class="nav-link has-icon active"><i class="fas fa-image icon icon-sm"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="#google_analytics" data-bs-toggle="tab" class="nav-link has-icon"><i class="fab fa-google icon icon-sm"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="#maintenance" data-bs-toggle="tab" class="nav-link has-icon"><i class="fas fa-plug icon icon-sm"></i></a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane active" id="backend">
                    <h6>{{__("BACKEND")}}</h6>
                    <hr>
                    <x-forms::base-form method="POST" :action="route('admin.settings.backend.post')" :multipart="true">
                        <x-forms::group
                            label="{{__('App Name')}}"
                            name="app__name"
                            help="{{__('This name is shown in the title area of the back-end')}}"
                            placeholder="MyApp"
                            :required="true"
                            :value="config('app.name', 'OCMS')"
                        />
                        <x-forms::thumbnail
                            label="{{__('App Logo')}}"
                            name="app__logo"
                            :value="uploadPath(config('app.logo'))"
                        />
                        <x-forms::thumbnail
                            label="{{__('App Favicon')}}"
                            name="app__favicon"
                            :value="uploadPath(config('app.favicon'))"
                        />
                        <x-forms::group
                            mode="select"
                            name="app__language"
                            label="{{__('Language')}}"
                            :required="true"
                        >
                            <option value="en" @if(config('app.locale', 'en') == 'en')selected @endif>English (EN)</option>
                            <option value="vi" @if(config('app.locale', 'en') == 'vi')selected @endif>Vietnamese (VI)</option>
                        </x-forms::group>
                        <x-forms::group
                            mode="select"
                            name="app__timezone"
                            label="{{__('Timezone')}}"
                            :required="true"
                        >
                            @foreach (timezones() as $timezone)
                                <optgroup label="{{ $timezone['group'] }}">
                                    @foreach($timezone['zones'] as $zone)
                                        <option value="{{ $zone['value'] }}" @if(config('app.timezone', 'UTC') == $zone['value'])selected @endif>{{ $zone['value'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </x-forms::group>
                        <x-forms::group
                            mode="select"
                            name="app__notification_method"
                            label="{{__('Notification Method')}}"
                            :required="true"
                        >
                            <option value="email" @if(config('app.notification_method') == 'email') selected @endif>Email</option>
                            <option value="sms" @if(config('app.notification_method') == 'sms') selected @endif>SMS</option>
                        </x-forms::group>
                        <x-forms::group
                            mode="switch"
                            name="app__debug"
                            label="{{__('Debug Mode')}}"
                            help="개발모드 에서만 사용하세요"
                            :checked="config('app.debug', true)"
                        />
                        <x-forms::group
                            mode="switch"
                            name="app__cache"
                            label="{{__('Cache Mode')}}"
                            help="{{__('Optimize for database query and page load speed')}}"
                            :checked="config('app.cache', false)"
                        />
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            @lang('messages.save_changes')
                        </button>
                    </x-forms::base-form>
                </div>
                <div class="tab-pane" id="google_analytics">
                    <h6>GOOGLE ANALYTICS</h6>
                    <hr>
                    <x-forms::base-form method="POST" :action="route('admin.settings.analytics.post')">
                        <x-forms::group
                            label="Tracking ID"
                            name="analytics__tracking_id"
                            placeholder="Ex: GA-123456778-9"
                            :value="config('analytics.tracking_id', '')"
                        />
                        <x-forms::group
                            label="View ID"
                            name="analytics__view_id"
                            placeholder="View ID"
                            :value="config('analytics.view_id', '')"
                        />
                        <x-forms::group
                            label="{{__('Service Account Path')}}"
                            name="analytics__service_account_credentials_json"
                            placeholder="{{__('Service Account Path')}}"
                            :value="config('analytics.service_account_credentials_json', '')"
                        />
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            @lang('messages.save_changes')
                        </button>
                    </x-forms::base-form>
                </div>
                <div class="tab-pane" id="maintenance">
                    <h6>{{__('MAINTENANCE')}}</h6>
                    <hr>
                    <x-forms::base-form method="POST" :action="route('admin.settings.maintenance.post')">
                        <x-forms::group
                            mode="switch"
                            name="app__maintenance"
                            label="{{__('Maintenance Mode')}}"
                            help="{{__('Enable maintenance mode, visitors will see maintenance page when they try to access.')}}"
                            :checked="config('app.maintenance', true)"
                        />
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            @lang('messages.save_changes')
                        </button>
                    </x-forms::base-form>
                </div>
            </div>
        </div>
    </div>
</div>
