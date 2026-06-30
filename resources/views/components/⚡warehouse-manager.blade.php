<?php

use Livewire\Component;
use App\Models\StorageSystem;

new class extends Component
{
    public string $name = '';
    public string $type = 'pallet_rack';
    public float $pos_x = 0; public float $pos_y = 0; public float $pos_z = 0;
    public int $columns = 1; public int $rows = 1; public int $levels = 1;
    public float $spacing = 0.1;
    public float $size_x = 1.0; public float $size_y = 1.0; public float $size_z = 1.0;
    public int $orientation = 0;

    public function mount() {
        $this->generateSuggestName();
    }

    public function generateSuggestName() {
        $nextNumber = StorageSystem::count() + 1;
        $this->name = "Storage " . $nextNumber;
    }

    public function addStorage() {
        $this->validate([
            'name' => 'required',
            'pos_x' => 'required|numeric',
            'pos_y' => 'required|numeric',
            'pos_z' => 'required|numeric',
            'columns' => 'required|integer|min:1',
            'levels' => 'required|integer|min:1',
        ]);

        StorageSystem::create([
            'name' => $this->name,
            'type' => $this->type,
            'pos_x' => $this->pos_x,
            'pos_y' => $this->pos_y,
            'pos_z' => $this->pos_z,
            'columns' => $this->columns,
            'rows' => $this->rows,
            'levels' => $this->levels,
            'spacing' => $this->spacing,
            'size_x' => $this->size_x,
            'size_y' => $this->size_y,
            'size_z' => $this->size_z,
            'orientation' => $this->orientation,
        ]);

        $this->reset(['pos_x', 'pos_y', 'pos_z', 'columns', 'rows', 'levels']);
        $this->generateSuggestName();
    }

    public function with(): array {
        return ['Storages' => StorageSystem::all()];
    }

    public function deleteStorage($id) {
        StorageSystem::destroy($id);
        $this->generateSuggestName();
    }
};
?>

<div class="p-8 bg-gray-100 min-h-screen text-black">
    <div class="max-w-6xl mx-auto">
        
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8 border border-indigo-100">
            <h2 class="text-xl font-bold mb-6 text-indigo-800 border-b pb-2">Configurare Sistem Stocare</h2>
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Nume Identificare</label>
                        <input type="text" wire:model="name" class="w-full border-gray-300 rounded-lg text-black font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Tip Sistem</label>
                        <select wire:model="type" class="w-full border-gray-300 rounded-lg">
                            <option value="pallet_rack">Pallet Rack</option>
                            <option value="shelf">Shelf</option>
                            <option value="cold_storage">Cold Storage</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Orientare (Grade)</label>
                        <input type="number" wire:model="orientation" class="w-full border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-blue-50 p-4 rounded-lg">
                    <div>
                        <label class="block text-xs font-bold text-blue-700 uppercase">Poziție X (Lățime)</label>
                        <input type="number" step="0.1" wire:model="pos_x" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-blue-700 uppercase">Poziție Y (Înălțime)</label>
                        <input type="number" step="0.1" wire:model="pos_y" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-blue-700 uppercase">Poziție Z (Adâncime)</label>
                        <input type="number" step="0.1" wire:model="pos_z" class="w-full border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Nr. Coloane</label>
                        <input type="number" wire:model="columns" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Nr. Rânduri</label>
                        <input type="number" wire:model="rows" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Nr. Niveluri</label>
                        <input type="number" wire:model="levels" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Spacing (m)</label>
                        <input type="number" step="0.01" wire:model="spacing" class="w-full border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase">Lățime Celulă (Size X)</label>
                        <input type="number" step="0.1" wire:model="size_x" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase">Înălțime Celulă (Size Y)</label>
                        <input type="number" step="0.1" wire:model="size_y" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase">Adâncime Celulă (Size Z)</label>
                        <input type="number" step="0.1" wire:model="size_z" class="w-full border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <button wire:click="addStorage" class="mt-8 w-full bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-lg hover:bg-indigo-700 shadow-md transition-all active:scale-95">
                Salvează Configurația în Baza de Date
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($Storages as $Storage)
                <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-indigo-500 flex justify-between items-start">
                    <div class="space-y-1">
                        <h3 class="font-bold text-lg text-gray-900">{{ $Storage->name }} <span class="text-xs font-normal text-gray-400">({{ $Storage->type }})</span></h3>
                        <p class="text-sm text-gray-600">Grid: <b>{{ $Storage->rows }}x{{ $Storage->columns }}x{{ $Storage->levels }}</b> | Spacing: {{ $Storage->spacing }}m</p>
                        <p class="text-xs font-mono text-indigo-600">Pos: [{{ $Storage->pos_x }}, {{ $Storage->pos_y }}, {{ $Storage->pos_z }}]</p>
                    </div>
                    <button wire:click="deleteStorage({{ $Storage->id }})" class="bg-red-50 text-red-500 p-2 rounded-lg hover:bg-red-500 hover:text-white transition">
                        Șterge
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>