<!DOCTYPE html>
<html>
<head>
    <title>Laravel Log Viewer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">System Logs</h2>
        
        <div class="table-responsive bg-white shadow-sm">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Level</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-nowrap">{{ $log['date'] }}</td>
                            <td>
                                <span class="badge bg-{{ $log['class'] }}">
                                    {{ $log['level'] }}
                                </span>
                            </td>
                            <td class="text-break">{{ $log['message'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>