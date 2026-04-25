<?php

namespace SultanulArefin\LogViewer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            return view('log-viewer::index', [
                'logs' => new LengthAwarePaginator([], 0, 15),
                'perPage' => 15
            ]);
        }

        $fileContent = File::get($logPath);
        $pattern = '/\[(?P<date>.*)\] (?P<env>\w+)\.(?P<level>\w+): (?P<message>.*)/';
        $logEntries = preg_split('/(?=\[\d{4}-\d{2}-\d{2})/', $fileContent, -1, PREG_SPLIT_NO_EMPTY);

        $parsedLogs = collect($logEntries)->map(function ($entry) use ($pattern) {
            if (preg_match($pattern, $entry, $match)) {
                return [
                    'date'    => $match['date'],
                    'level'   => strtoupper($match['level']),
                    'env'     => $match['env'],
                    'class'   => $this->getLevelClass($match['level']),
                    'message' => trim($match['message']),
                    'full'    => trim($entry)
                ];
            }
            return null;
        })->filter()->reverse();

        // Search Logic
        if ($query = $request->get('search')) {
            $parsedLogs = $parsedLogs->filter(fn($item) => 
                str_contains(strtolower($item['full']), strtolower($query))
            );
        }

        // Pagination Logic
        $perPage = (int) $request->get('per_page', 15);
        if (!in_array($perPage, [10, 15, 20, 30, 40, 50])) {
            $perPage = 15;
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedData = $parsedLogs->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $logs = new LengthAwarePaginator(
            $pagedData, 
            $parsedLogs->count(), 
            $perPage, 
            $currentPage, 
            [
                'path' => $request->url(), 
                'query' => $request->query()
            ]
        );

        // Pass both variables to the view
        return view('log-viewer::index', compact('logs', 'perPage'));
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
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