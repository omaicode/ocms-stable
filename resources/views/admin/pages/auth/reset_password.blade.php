<main class="auth-background">
    <!-- Section -->
    <section class="vh-100 my-5 my-lg-0 bg-soft d-flex align-items-center">
        <div class="container-fluid">
            <div class="row justify-content-center form-bg-image">                   
                <div class="col-12 col-xl-6 col-lg-6 d-flex align-items-center justify-content-center">
                    <div class="bg-white shadow border-0 rounded-5 border-light p-4 p-lg-5 w-100 fmxw-500">
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3 fw-bolder">{{ __('Reset Password') }}</h1>
                                <p>{{ __('Set your new password') }}</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.auth.reset.post', ['token' => request()->route('token')]) }}" method="POST" class="mt-4 @if($errors->any()) was-validated @endif">
                            @csrf
                            <x-forms::group
                                type="password"
                                :label="__('messages.auth.new_password')"
                                name="password" 
                                :placeholder="__('messages.auth.new_password_placeholder')"
                                :required="true"
                            />    
                            <x-forms::group
                                type="password"
                                :label="__('messages.auth.new_password_confirmation')"
                                name="password_confirmation" 
                                :placeholder="__('messages.auth.new_password_confirmation_placeholder')"
                                :required="true"
                            />    
                            <div class="d-grid">
                                <button type="submit" class="btn btn-gray-800">@lang('messages.auth.reset_password')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>