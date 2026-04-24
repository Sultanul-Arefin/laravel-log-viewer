<?php

namespace SultanulArefin\LogViewer\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return view('log-viewer::index', ['logs' => []]);
        }

        $fileContent = File::get($logPath);
        
        // Regex to capture Date, Level, and Message
        $pattern = '/\[(?P<date>.*)\] (?P<env>\w+)\.(?P<level>\w+): (?P<message>.*)/';
        
        preg_match_all($pattern, $fileContent, $matches, PREG_SET_ORDER);

        $logs = [];
        foreach ($matches as $match) {
            $logs[] = [
                'date'    => $match['date'],
                'level'   => strtoupper($match['level']),
                'class'   => $this->getLevelClass($match['level']),
                'message' => $match['message']
            ];
        }

        return view('log-viewer::index', ['logs' => array_reverse($logs)]);
    }

    private function getLevelClass($level)
    {
        return match (strtolower($level)) {
            'error', 'critical', 'alert', 'emergency' => 'danger',
            'warning' => 'warning',
            'info'    => 'info',
            'notice'  => 'primary',
            'success' => 'success', // Custom if you use it
            default   => 'secondary',
        };
    }
}