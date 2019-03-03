<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        User::where('confirmation_token', request('token'))->firstOrFail()->confirm();
        return redirect('/threads')->with('flash', 'Your email address is confirmed, You can now post to the forum');
    }
}
