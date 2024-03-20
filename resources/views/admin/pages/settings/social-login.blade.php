<x-forms::base-form method="POST" action="">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="card-title">@lang('Social Login Setting')</h3>
                    <p>@lang('Please follow the instructions before fill the form below:')</p>
                    <p class="fw-bold">@lang('How to get Client ID, Client Secret, Redirect URI?')</p>
                    <ol>
                       <li>Facebook: <a class="text-info" href="https://developers.facebook.com/docs/development/create-an-app/">Create an app</a></li>
                       <li>Google: <a class="text-info" href="https://support.google.com/cloud/answer/6158849">Setting up OAUTH 2.0</a></li>
                       <li>Kakao: <a class="text-info" href="https://supabase.com/docs/guides/auth/social-login/auth-kakao">Login with Kakao</a></li>
                       <li>Naver: <a class="text-info" href="https://developers.naver.com/docs/login/api/api.md">Login with Naver</a></li>
                    </ol>
                    <p class="fw-bold">@lang('Remember to add a callback URL or redirect URI to the specific URLs below based on your social provider:')</p>
                    <ol>
                        <li>Facebook: <a class="text-info" href="{{route('clientarea.auth.login.social-callback', "facebook")}}">{{route('clientarea.auth.login.social-callback', "facebook")}}</a></li>
                        <li>Google: <a class="text-info" href="{{route('clientarea.auth.login.social-callback', "google")}}">{{route('clientarea.auth.login.social-callback', "google")}}</a></li>
                        <li>Kakao: <a class="text-info" href="{{route('clientarea.auth.login.social-callback', "kakao")}}">{{route('clientarea.auth.login.social-callback', "kakao")}}</a></li>
                        <li>Naver: <a class="text-info" href="{{route('clientarea.auth.login.social-callback', "naver")}}">{{route('clientarea.auth.login.social-callback', "naver")}}</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fab fa-facebook"></i>
                        Facebook
                    </h5>
                    <x-forms::group
                        label="{{__('Client ID')}}"
                        name="services__facebook__client_id"
                        :value="config('services.facebook.client_id')"
                    />
                    <x-forms::group
                        label="{{__('Client Secret')}}"
                        name="services__facebook__client_secret"
                        :value="config('services.facebook.client_secret')"
                    />
                    <x-forms::group
                        mode="switch"
                        label="{{__('Enabled')}}"
                        name="services__facebook__enabled"
                        :checked="intval(config('services.facebook.enabled', 0)) === 1"
                    />
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fab fa-google"></i>
                        Google
                    </h5>
                    <x-forms::group
                        label="{{__('Client ID')}}"
                        name="services__google__client_id"
                        :value="config('services.google.client_id')"
                    />
                    <x-forms::group
                        label="{{__('Client Secret')}}"
                        name="services__google__client_secret"
                        :value="config('services.google.client_secret')"
                    />
                    <x-forms::group
                        mode="switch"
                        label="{{__('Enabled')}}"
                        name="services__google__enabled"
                        :checked="intval(config('services.google.enabled', 0)) === 1"
                    />
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <img height="22" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAA7AAAAOwBeShxvQAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAALpSURBVFiFtddbiFZVFAfw33xNjppUdFVpUiaFbkxQRGXhQ4Ev1oOlSYRPQkFPFfTQiyXdIyIqjcKXHuupHioIjEJLuhAaFUQQXewiIUVjfjU6Tg9rn+ZwOPuc8/GNf1iczd5rr//al7XWPiO64zTcjOtxLZbhbIziT3yP/XgPb2FqANuNmMAr+AuzHeVv7MLqYYgX4gn8MwBxVabxdLI1EMbx8RDEVdknjqwTLsPv80heyE+YbCNfhV9OAnnZiaU58lPxyUkkL+QzLCpITyk58Dhub9uihGl8h29xEH2cXrGXw3LM4P1y54Xab/tREVo3YnGN4cW4KekcbbE1pXIUL7VMeA3nd1hdgaV4vcXmzkJ5UfKoTukE7h2AuIr7ko0620ewBDY0ePnQEOQFtjfY30x++/eLPD8sRvFFhuPlHi7PTHwRx0Vk7BXh8xFuwVPYkvTuwJ2pfR0ewAI8Ke7CcezIcEzCoYx3FyWlFbg79W3BWlFsfk2r2yl2Ee5Jzr6JD8zF+6oMx6FR9SFFxDf8gHdS+w1sw6uiLK8XsX8ijfdS/xFRT/oVW1Wc0RMZsHaw1O6l75i5XTgLW0tjhd7XIuVuK/XnQnimh8OZwRtK7SJJXZm+2/EI1omseBu+xHlitZtwF65K89dkOA7Dh+rPZ3dF+RqRbi+p9J2biK5ODkyksdVix0awJ8Oxj+YsuDXj+SAoLnCdPEdzIjqGW4cg34B/G+xvJLY1l4oLJ56Rj5Y6LBTV9ViD3T/KNl9oUCzk2Q7EK/AgfuxgbwdxQYik85W4NDmsx9upvQb3i3jv4Rxcmhzogj4uTo7+j6aiMSW2dQEeE+m1bYVN8midV2M4kJmwW4TZ50MSz4pnX3anx/FzzaSZeSCexW/i9dWIyYwTw8pBce6dMC7eA/NF/qm5DNkZY3jYcL9mfXHhcgWvEybwvMF+TqdEnF/QZnykTaGEJeLZvQ5XYCXOFNVwGt+Iivhukn6tlQr+A0019+Gq/p8DAAAAAElFTkSuQmCC">
                        Kakao
                    </h5>
                    <x-forms::group
                        label="{{__('Client ID')}}"
                        name="services__kakao__client_id"
                        :value="config('services.kakao.client_id')"
                    />
                    <x-forms::group
                        label="{{__('Client Secret')}}"
                        name="services__kakao__client_secret"
                        :value="config('services.kakao.client_secret')"
                    />
                    <x-forms::group
                        mode="switch"
                        label="{{__('Enabled')}}"
                        name="services__kakao__enabled"
                        :checked="intval(config('services.kakao.enabled', 0)) === 1"
                    />
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <img height="22" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAQAAADZc7J/AAAAIGNIUk0AAHomAACAhAAA+gAAAIDoAAB1MAAA6mAAADqYAAAXcJy6UTwAAAACYktHRAAAqo0jMgAAAAd0SU1FB+gBEAodCkmUTbQAAAEqSURBVEjH7ZWxSsRAEIa/xEDAsxKxOCJBBLUX9BUstdDzGe4RYm0e5Ao5LLXyCSw8ezmwFkEsjpwY8BKbMexEbjXZxuKyzczszj+7/2T/9bhnj/bfg0fpkA74bukLAIBAeSUv5HjirbIi1juv1YqQdV22NEbGMRExMTEb9PmQ+DWbEo04IlM5yplwYECHDCR+aUT3mZg5mgNPbS4nZfwjXmPNTuIjKblbF4YM3QByUp5Yag8AYy6Yzp8OLKnfN/WKZ3yKpjsIOKQDwJRbPpsfoeCUntgWzbBx0CFh5zeKbAAztkgI2wMA9DhzAwhJ2P07QGk0qxDqtjlXx6i1M6jBdYnkv/MrNTjhjhspNaOri2pZ14KyxrJYGW/VipqgLN6F/wEwUrrcdIy+APeleD/ed+ZlAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDI0LTAxLTE2VDEwOjI5OjAxKzAwOjAwzOmvxAAAACV0RVh0ZGF0ZTptb2RpZnkAMjAyNC0wMS0xNlQxMDoyOTowMSswMDowML20F3gAAAAodEVYdGRhdGU6dGltZXN0YW1wADIwMjQtMDEtMTZUMTA6Mjk6MDkrMDA6MDDZTnjAAAAAAElFTkSuQmCC">
                        Naver
                    </h5>
                    <x-forms::group
                        label="{{__('Client ID')}}"
                        name="services__naver__client_id"
                        :value="config('services.naver.client_id')"
                    />
                    <x-forms::group
                        label="{{__('Client Secret')}}"
                        name="services__naver__client_secret"
                        :value="config('services.naver.client_secret')"
                    />
                    <x-forms::group
                        mode="switch"
                        label="{{__('Enabled')}}"
                        name="services__naver__enabled"
                        :checked="intval(config('services.naver.enabled', 0)) === 1"
                    />
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success text-white">
                        <i class="fas fa-save"></i>
                        @lang('messages.save_changes')
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-forms::base-form>
