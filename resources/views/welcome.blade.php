<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warehouse Manager 3D</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 antialiased font-sans">
    
    <nav class="bg-white shadow-md mb-6 border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex space-x-8 items-center">
                    <div class="text-xl font-black text-indigo-600 tracking-tighter uppercase">WMS-3D</div>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('systems') }}" 
                           class="px-4 py-2 rounded-md text-sm font-bold transition-colors {{ request()->routeIs('systems') ? 'bg-grey-100 text-indigo-700' : 'text-gray-500 hover:text-indigo-600' }}">
                           Sisteme Stocare
                        </a>

                        <a href="{{ route('storages') }}" 
                           class="px-4 py-2 rounded-md text-sm font-bold transition-colors {{ request()->routeIs('storages') ? 'bg-grey-100 text-green-700' : 'text-gray-500 hover:text-green-600' }}">
                           Celule Stocare
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 pb-12">
        @if(request()->routeIs('storages'))
            <livewire:storage-item-manager /> 
        @else
            <livewire:storage-system-manager /> 
        @endif
    </main>

    @livewireScripts
</body>
</html>