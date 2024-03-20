<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        return view('clientarea.home');
    }

    public function logout()
    {
        Auth::guard('clients')->logout();
        return redirect()->route('clientarea.auth.login');
    }
}
