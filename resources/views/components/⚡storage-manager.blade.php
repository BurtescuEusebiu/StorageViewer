<?php

use Livewire\Component;
use App\Models\Storage;
use App\Models\StorageSystem;

new class extends Component
{
    public string $name = '';
    public string $code = '';
    public $storage_system_id = '';
    public int $row = 1;
    public int $column = 1;
    public int $level = 1;

    public function mount()
    {
        $this->generateSuggestValues();
    }

    public function generateSuggestValues()
    {
        $nextId = Storage::max('id') + 1;
        $this->name = "Locatie " . $nextId;
        $this->code = "LOC-" . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        
        if (empty($this->storage_system_id)) {
            $firstSystem = StorageSystem::first();
            $this->storage_system_id = $firstSystem ? $firstSystem->id : '';
        }
    }

public function addStorage()
{
    $this->validate([
        'name' => 'required|min:2',
        'code' => 'required|unique:storages,code',
        // 'required' va da eroare dacă valoarea este null sau șir vid
        'storage_system_id' => 'required|exists:storage_systems,id',
        'row' => 'required|integer|min:1',
        'column' => 'required|integer|min:1',
        'level' => 'required|integer|min:1',
    ], [
        'storage_system_id.required' => 'Trebuie să selectezi un raft din listă.',
    ]);

    Storage::create([
        'name' => $this->name,
        'code' => $this->code,
        'storage_system_id' => $this->storage_system_id,
        'row' => $this->row,
        'column' => $this->column,
        'level' => $this->level,
    ]);

    $this->reset(['name', 'code', 'row', 'column', 'level']);
    $this->generateSuggestValues();
}

    public function deleteStorage($id)
    {
        Storage::destroy($id);
        $this->generateSuggestValues();
    }

    public function with(): array
    {
        return [
            'allStorages' => Storage::with('system')->get(),
            'systems' => StorageSystem::all(),
        ];
    }
};
?>

<div class="p-8 max-w-6xl mx-auto text-black">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
        <h2 class="text-xl font-bold mb-6 text-green-700 flex items-center gap-2">
            Definire Locatie Depozitare
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Aparține de Sistemul:</label>
                <select wire:model="storage_system_id" class="w-full border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500 text-black">
                    <option value="">-- Selectează un Raft --</option>
                    @foreach($systems as $sys)
                        <option value="{{ $sys->id }}">{{ $sys->name }} ({{ $sys->columns }}x{{ $sys->levels }})</option>
                    @endforeach
                </select>
                @error('storage_system_id') 
                <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Denumire Locație</label>
                <input type="text" wire:model="name" class="w-full border-gray-200 rounded-lg text-black">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cod Identificare</label>
                <input type="text" wire:model="code" class="w-full border-gray-200 rounded-lg text-black">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 bg-green-50 p-4 rounded-xl border border-green-100">
            <div>
                <label class="block text-xs font-bold text-green-700 uppercase mb-1">Rând</label>
                <input type="number" wire:model="row" class="w-full border-green-200 rounded-lg text-black">
            </div>
            <div>
                <label class="block text-xs font-bold text-green-700 uppercase mb-1">Coloană</label>
                <input type="number" wire:model="column" class="w-full border-green-200 rounded-lg text-black">
            </div>
            <div>
                <label class="block text-xs font-bold text-green-700 uppercase mb-1">Nivel</label>
                <input type="number" wire:model="level" class="w-full border-green-200 rounded-lg text-black">
            </div>
        </div>

        <button wire:click="addStorage" class="mt-6 w-full bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 shadow-lg shadow-green-100 transition-all active:scale-[0.98]">
            Salvează Locația în Baza de Date
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Cod / Nume</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Sistem Asociat</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase text-center">Poziție Logică</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase text-right">Acțiuni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($allStorages as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4">
                        <div class="font-bold text-gray-800">{{ $item->code }}</div>
                        <div class="text-sm text-gray-500">{{ $item->name }}</div>
                    </td>
                    <td class="p-4 text-gray-600">
                        <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded text-xs font-medium">
                            {{ $item->system->name ?? 'Fără sistem' }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-1 font-mono text-sm">
                            <span class="bg-gray-100 px-2 rounded">R:{{ $item->row }}</span>
                            <span class="bg-gray-100 px-2 rounded">C:{{ $item->column }}</span>
                            <span class="bg-gray-100 px-2 rounded">L:{{ $item->level }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-right">
                        <button wire:click="deleteStorage({{ $item->id }})" class="text-red-400 hover:text-red-600 transition">
                            Șterge
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>