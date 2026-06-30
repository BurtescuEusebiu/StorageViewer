<div class="p-8 max-w-6xl mx-auto space-y-6">
    
    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800"> Listă Sisteme de Stocare </h2>
        
        <a href="{{ route('systems.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-md shadow-indigo-50">
            Adaugă Sistem Nou
        </a>
    </div>

    <x-system-table :items="$allSystems" />

</div>