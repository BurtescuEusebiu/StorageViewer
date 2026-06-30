<div class="p-8 max-w-6xl mx-auto space-y-4">
    
    <div class="flex justify-start">
        <a href="{{ route('storages.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-md shadow-indigo-50">
            Înapoi la Listă
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Aparține de Sistemul</label>
                    <select wire:model="storage_system_id" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Selectează un Raft --</option>
                        @foreach($systems as $sys)
                            <option value="{{ $sys->id }}">{{ $sys->name }} ({{ $sys->rows }}x{{ $sys->columns }}x{{ $sys->levels }})</option>
                        @endforeach
                    </select>
                    @error('storage_system_id') 
                        <span class="text-red-500 text-xs mt-1 font-bold block">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Denumire Produs</label>
                    <input type="text" wire:model="name" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Cod Identificare</label>
                    <input type="text" wire:model="code" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Rând</label>
                    <input type="number" wire:model="row" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Coloană</label>
                    <input type="number" wire:model="column" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Nivel</label>
                    <input type="number" wire:model="level" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        <button wire:click="addStorage" class="mt-8 w-full bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-base hover:bg-indigo-700 shadow-md shadow-indigo-50 transition-all active:scale-[0.99]">
            Salvează produsul în Baza de Date
        </button>

    </div> </div> 