<main class="auth-background">
    <!-- Section -->
    <section class="vh-100 my-5 my-lg-0 bg-soft d-flex align-items-center">
        <div class="container-fluid">
            <div class="row justify-content-center form-bg-image">              
                <div class="col-12 col-xl-6 col-lg-6 d-flex align-items-center justify-content-center">
                    <div class="bg-white shadow border-0 rounded-5 border-light p-4 p-lg-5 w-100 fmxw-500">
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <h1 class="mb-0 h3 fw-bolder">{{ __('Forgot Password') }}</h1>
                            <p>{{ __('Enter your email and receive reset password link') }}</p>
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{session('success')}}
                            </div>
                        @else
                        <form action="{{ route('admin.auth.forgot.post') }}" method="POST" class="mt-4 @if($errors->any()) was-validated @endif">
                            @csrf
                            <!-- Form -->
                            <div class="form-group mb-4 has-validation">
                                <label for="email">@lang('messages.auth.email')</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-envelope icon icon-xs text-gray-600"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="@lang('messages.auth.email_placeholder')" name="email" id="email" value="{{old('email', '')}}" autofocus required>
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                </div>  
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-gray-800">@lang('messages.auth.reset_password')</button>
                            </div>
                        </form>
                        @endif
                        <div class="d-flex justify-content-center align-items-top mt-4">
                            <div>
                                <a href="{{ route('admin.auth.login') }}" class="small text-right">
                                    <i class="fas fa-chevron-left"></i>
                                    @lang('messages.auth.back_to_login')
                                </a>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>