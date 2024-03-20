<main class="auth-background">
    <!-- Section -->
    <section class="vh-100 my-5 my-lg-0 bg-soft d-flex align-items-center">
        <div class="container-fluid">
            <div class="row justify-content-center form-bg-image">
                <div class="col-12 col-xl-6 col-lg-6 d-flex align-items-center justify-content-center">
                    <div class="bg-white shadow border-0 rounded-5 border-light p-4 p-lg-5 w-100 fmxw-500">
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <h1 class="mb-0 h3 fw-bolder">{{ __('Sign In') }}</h1>
                            <p>{{ __('Welcome to', ['name' => config('app.name')]) }}</p>
                        </div>
                        <form action="{{ route('admin.auth.login.post') }}" method="POST" class="mt-4 @if($errors->any()) was-validated @endif">
                            @csrf
                            <div class="form-group mb-4 has-validation">
                                <label for="username">@lang('messages.auth.username')</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-user icon icon-xs text-gray-600"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="@lang('messages.auth.username_placeholder')" @if(config('admin.is_demo'))value="administrator"@endif name="username" id="username" autofocus required>
                                    <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-4 has-validation">
                                    <label for="password">@lang('messages.auth.password')</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon2">
                                            <i class="fas fa-lock icon icon-xs text-gray-600"></i>
                                        </span>
                                        <input type="password" placeholder="@lang('messages.auth.password_placeholder')" class="form-control" @if(config('admin.is_demo'))value="123456"@endif name="password" id="password" required>
                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-top mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="on" name="remember_me">
                                        <label class="form-check-label" for="remember_me">
                                          {{ __('Remember me') }}
                                        </label>
                                    </div>
                                    <div>
                                        <a href="{{route('admin.auth.forgot')}}" class="small text-right">@lang('messages.auth.forgot_password')</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-gray-800">@lang('messages.auth.sign_in')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
