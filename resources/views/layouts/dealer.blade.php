<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genba Honda - Dealer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">

<nav class="bg-red-700 text-white px-6 py-4 flex justify-between items-center shadow">
    <div>
        <h1 class="text-lg font-bold">🏍️ Genba Honda</h1>
        <p class="text-xs text-red-200">{{ auth()->user()->dealer->name ?? '' }} — {{ auth()->user()->role->name ?? '' }}</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('dealer.dashboard') }}" class="text-sm hover:text-red-200">Dashboard</a>
        <a href="{{ route('dealer.genba.index') }}" class="text-sm hover:text-red-200">Genba Saya</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm bg-red-800 px-3 py-1 rounded hover:bg-red-900">Logout</button>
        </form>
    </div>
</nav>

<main class="max-w-4xl mx-auto p-6">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-200">
            ✅ {{ session('success') }}
        </div>
    @endif
    @yield('content')
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>