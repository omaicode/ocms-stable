<?php

namespace App\Http\Controllers\Client;

use App\Enums\LoginProviderEnum;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Interfaces\ClientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OCMS\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected ClientRepository $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login()
    {
        return view('clientarea.auth.login');
    }

    public function loginPost(Request $req)
    {
        $req->validate([
           'email' => 'required|email|max:250',
           'password' => 'required'
        ]);

        if(Auth::guard('clients')->attempt($req->only(['email', 'password']), $req->filled('remember_me'))) {
            return redirect()->route('clientarea.home')->with('success', __('auth.logged_in'));
        }

        return redirect()->back()->withErrors(['email' => __('auth.failed')]);
    }

    public function signup()
    {
        return view('clientarea.auth.signup');
    }

    public function signupPost(Request $req)
    {
        $input = $req->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|max:250|unique:clients',
            'phone' => 'required|digits_between:8,16',
            'password' => 'required|string|min:6|max:30|confirmed'
        ]);

        $input['password'] = Hash::make($input['password']);
        $client = $this->repository->create($input);
        Auth::guard('clients')->loginUsingId($client->id);

        return redirect()->route('clientarea.home')->with('success', __('auth.registered'));
    }

    public function socialLogin($provider)
    {
        $providers = LoginProviderEnum::getValues();
        if(!in_array($provider, $providers)) return abort(400);
        if(intval(config("services.{$provider}.enabled", 0)) === 0) {
            return redirect()->route('clientarea.auth.login');
        }
        return Socialite::driver($provider)->redirect();
    }

    public function socialLoginCallback($provider): \Illuminate\Http\RedirectResponse
    {
        $providers = LoginProviderEnum::getValues();
        if(!in_array($provider, $providers)) return abort(400);
        if(intval(config("services.{$provider}.enabled", 0)) === 0) {
            return redirect()->route('clientarea.auth.login');
        }

        $social_info = Socialite::driver($provider)->user();

        $id = $social_info->getId();
        $name = $social_info->getName();
        $email = $social_info->getEmail();

        $user = $this->repository->findWhere([
            'provider_id' => $id,
            'provider' => $provider
        ])->first();

        if(!$user) {
            $user = $this->repository->create([
                'name' => $name,
                'email' => $id . '@noemail.com',
                'provider_id' => $id,
                'provider' => $provider,
                'password' => Hash::make(uniqid()),
                'email_verified_at' => now()
            ]);
        }

        Auth::guard('clients')->login($user);
        return redirect()->route('clientarea.home');
    }
}
