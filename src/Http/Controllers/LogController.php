<?php

namespace SultanulArefin\LogViewer\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        if (!File::exists($logPath)) {
            return view('log-viewer::index', ['logs' => new LengthAwarePaginator([], 0, 15)]);
        }

        $fileContent = File::get($logPath);
        
        // Regex that captures Date, Level, and Message
        $pattern = '/\[(?P<date>.*)\] (?P<env>\w+)\.(?P<level>\w+): (?P<message>.*)/';
        
        // Split file by the pattern but keep the matches to preserve stack traces
        $logEntries = preg_split('/(?=\[\d{4}-\d{2}-\d{2})/', $fileContent, -1, PREG_SPLIT_NO_EMPTY);

        $parsedLogs = collect($logEntries)->map(function ($entry) use ($pattern) {
            if (preg_match($pattern, $entry, $match)) {
                return [
                    'date'    => $match['date'],
                    'level'   => strtoupper($match['level']),
                    'env'     => $match['env'],
                    'class'   => $this->getLevelClass($match['level']),
                    'message' => trim($match['message']),
                    'full'    => trim($entry) // This contains the stack trace
                ];
            }
            return null;
        })->filter()->reverse();

        // Search
        if ($query = $request->get('search')) {
            $parsedLogs = $parsedLogs->filter(fn($item) => 
                str_contains(strtolower($item['full']), strtolower($query))
            );
        }

        // Pagination
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $pagedData = $parsedLogs->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $logs = new LengthAwarePaginator(
            $pagedData, 
            $parsedLogs->count(), 
            $perPage, 
            $currentPage, 
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('log-viewer::index', compact('logs'));
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, ''); // Empties the file instead of deleting it to avoid permission issues
        }

        return redirect()->back()->with('success', 'Logs cleared successfully!');
    }

    private function getLevelClass($level)
    {
        return match (strtolower($level)) {
            'error', 'critical', 'alert', 'emergency' => 'danger',
            'warning' => 'warning',
            'info'    => 'info',
            'notice'  => 'primary',
            'debug'   => 'dark',
            default   => 'secondary',
        };
    }
}