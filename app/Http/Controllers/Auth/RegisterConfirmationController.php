<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        $user = User::where('confirmation_token', request('token'))->first();
        if(!$user){
            return redirect(route('threads'))
                ->with('flash', 'Invalid email confirming token');
        }
        $user->confirm();
        return redirect('/threads')->with('flash', 'Your email address is confirmed, You can now post to the forum');
    }
}
