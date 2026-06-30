<div class="p-8 max-w-6xl mx-auto space-y-4">
    
    <div class="flex justify-start">
        <a href="{{ route('systems.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-md shadow-indigo-50">
            Înapoi la Listă
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
        <h2 class="text-xl font-bold text-indigo-900 border-b border-gray-100 pb-3">
            Definire Completă Sistem de Stocare
        </h2>
        
        <div>
            <h3 class="text-sm font-extrabold text-gray-400 uppercase tracking-wider mb-3">1. Structură & Identificare</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Denumire Sistem</label>
                    <input type="text" wire:model="name" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Tip (Type)</label>
                    <input type="text" wire:model="type" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Columns (X)</label>
                    <input type="number" wire:model="columns" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('columns') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Rows (Y)</label>
                    <input type="number" wire:model="rows" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('rows') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Levels (Z)</label>
                    <input type="number" wire:model="levels" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('levels') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Spacing (Spațiere celule)</label>
                    <input type="number" step="0.01" wire:model="spacing" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('spacing') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <hr class="border-gray-100">

        <div>
            <h3 class="text-sm font-extrabold text-gray-400 uppercase tracking-wider mb-3">2. Poziționare în Spațiu (Coordonate)</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Pos X</label>
                    <input type="number" step="0.01" wire:model="pos_x" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('pos_x') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Pos Y</label>
                    <input type="number" step="0.01" wire:model="pos_y" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('pos_y') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Pos Z</label>
                    <input type="number" step="0.01" wire:model="pos_z" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('pos_z') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">Orientation (Grade: 0-360)</label>
                    <input type="number" wire:model="orientation" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('orientation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <hr class="border-gray-100">

        <div>
            <h3 class="text-sm font-extrabold text-gray-400 uppercase tracking-wider mb-3">3. Dimensiuni</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Size X (Lungime)</label>
                    <input type="number" step="0.01" wire:model="size_x" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('size_x') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Size Y (Lățime)</label>
                    <input type="number" step="0.01" wire:model="size_y" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('size_y') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-600 uppercase mb-1 font-mono">Size Z (Înălțime)</label>
                    <input type="number" step="0.01" wire:model="size_z" class="w-full border-gray-300 rounded-lg text-black focus:ring-indigo-500 focus:border-indigo-500">
                    @error('size_z') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <button wire:click="addSystem" class="mt-4 w-full bg-indigo-600 text-white px-6 py-4 rounded-xl font-bold text-base hover:bg-indigo-700 shadow-md shadow-indigo-50 transition-all active:scale-[0.99]">
            Inserează Sistemul în Baza de Date
        </button>
    </div>
</div>