<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Log Viewer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom scrollbar for the stack trace */
        pre::-webkit-scrollbar { height: 8px; }
        pre::-webkit-scrollbar-thumb { background: #4a5568; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100 p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">System Logs</h1>
                {{-- <p class="text-sm text-gray-500">Viewing: {{ storage_path('logs/laravel.log') }}</p> --}}
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <form action="" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search logs..." 
                           class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('log-viewer.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition">
                            Reset
                        </a>
                    @endif
                </form>

                <form action="{{ route('log-viewer.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all logs? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition font-medium">
                        Clear Logs
                    </button>
                </form>

                <div class="flex flex-wrap items-center gap-3">
                    <form action="{{ route('log-viewer.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Level</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Env</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Message</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap font-mono">
                                    {{ $log['date'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-tighter
                                        {{ $log['class'] == 'danger' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
                                        {{ $log['class'] == 'warning' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : '' }}
                                        {{ $log['class'] == 'info' ? 'bg-blue-100 text-blue-700 border border-blue-200' : '' }}
                                        {{ $log['class'] == 'dark' ? 'bg-gray-800 text-gray-100' : '' }}
                                        {{ $log['class'] == 'primary' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
                                        {{ $log['class'] == 'secondary' ? 'bg-gray-100 text-gray-500 border border-gray-200' : '' }}">
                                        {{ $log['level'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-medium text-gray-400 uppercase tracking-widest">{{ $log['env'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="text-gray-800 font-medium leading-relaxed">{{ $log['message'] }}</div>
                                    
                                    @if(isset($log['full']) && strlen($log['full']) > strlen($log['message']) + 20)
                                        <details class="mt-3 group">
                                            <summary class="text-xs text-blue-600 hover:text-blue-800 cursor-pointer font-semibold list-none flex items-center gap-1">
                                                <span class="group-open:rotate-90 transition-transform">▶</span> 
                                                Stack Trace
                                            </summary>
                                            <div class="mt-2 relative">
                                                <pre class="p-4 bg-gray-900 text-gray-300 rounded-lg text-xs leading-5 overflow-x-auto border border-gray-700 shadow-inner max-h-96">{{ $log['full'] }}</pre>
                                            </div>
                                        </details>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-4xl mb-2 text-gray-300">∅</span>
                                        <p class="text-gray-500 font-medium">No logs found matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $logs->links() }}
        </div>
    </div>
</body>
</html>