<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'My App' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>

<body class="bg-gray-100 min-h-screen flex">
    <aside class="w-75 bg-white shadow-lg rounded-r-xl p-4 hidden md:flex flex-col space-y-6">
        @livewire('sidebar')
    </aside>
    <main class="flex-1 p-4">
        <div class="max-w-6xl mx-auto">
            <div>
                {{ $slot }}
                @livewireScripts
            </div>
        </div>
    </main>
</body>

</html>