<?php

namespace SultanulArefin\LogViewer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogViewerAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Allow the login route itself to be accessed
        if ($request->is('log-viewer/login*')) {
            return $next($request);
        }

        $allowedEmails = explode(',', env('LOG_VIEWER_EMAILS', ''));
        $sessionEmail = session('log_viewer_authenticated_email');

        if (empty(filter_var_array($allowedEmails)) && config('app.env') === 'production') {
            abort(403, 'Log Viewer is disabled in production unless LOG_VIEWER_EMAILS is set.');
        }

        if (!$sessionEmail || !in_array($sessionEmail, $allowedEmails)) {
            return redirect()->route('log-viewer.login');
        }

        return $next($request);
    }
}