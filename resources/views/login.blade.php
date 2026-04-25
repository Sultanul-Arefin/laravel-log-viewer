<!DOCTYPE html>
<html>
<head>
    <title>Log Viewer Auth</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Verify Identity</h2>
        <form action="" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Authorized Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
            </div>
            @if($errors->any())
                <p class="text-red-500 text-xs mb-4">{{ $errors->first() }}</p>
            @endif
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                Enter Log Viewer
            </button>
        </form>
    </div>
</body>
</html>