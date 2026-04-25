<?php

namespace SultanulArefin\LogViewer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function showLogin() {
        return view('log-viewer::login');
    }

    public function login(Request $request) {
        $allowedEmails = explode(',', env('LOG_VIEWER_EMAILS', ''));
        
        if (in_array($request->email, $allowedEmails)) {
            session(['log_viewer_authenticated_email' => $request->email]);
            return redirect()->route('log-viewer.index');
        }

        return back()->withErrors(['email' => 'Access Denied. Email not authorized.']);
    }

    public function logout(Request $request) 
    {
        // Clear only our specific session key
        session()->forget('log_viewer_authenticated_email');

        return redirect()->route('log-viewer.login')->with('success', 'Logged out successfully.');
    }
}