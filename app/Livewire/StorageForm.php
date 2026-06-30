<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Storage;
use App\Models\StorageSystem;

class StorageForm extends Component
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
        $result = \DB::select("SELECT seq FROM sqlite_sequence WHERE name = 'storages'");
        $nextId = !empty($result) ? $result[0]->seq + 1 : 1;
        
        $this->name = "Produs " . $nextId;
        $this->code = "P-" . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        
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

        $this->dispatch('storage-updated');

        $this->reset(['name', 'code', 'row', 'column', 'level']);
        $this->generateSuggestValues();
    }

    public function render()
    {
        return view('livewire.storage-form', [
            'systems' => StorageSystem::all()
        ]);
    }
}