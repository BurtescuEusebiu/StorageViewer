<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StorageSystem;
use Illuminate\Support\Facades\DB;

class StorageSystemForm extends Component
{
    public string $name = '';
    public string $type = 'rack';
    public float $pos_x = 0.0;
    public float $pos_y = 0.0;
    public float $pos_z = 0.0;
    public int $columns = 1;
    public int $rows = 1;
    public int $levels = 1;
    public float $spacing = 0.1;
    public float $size_x = 1.0;
    public float $size_y = 1.0;
    public float $size_z = 1.0;
    public int $orientation = 0;

    public function mount()
    {
        $this->generateSuggestValues();
    }

    public function generateSuggestValues()
    {
        $result = DB::select("SELECT seq FROM sqlite_sequence WHERE name = 'storage_systems'");
        $nextId = !empty($result) ? $result[0]->seq + 1 : 1;
        
        $this->name = "System " . $nextId;
    }

    public function addSystem()
    {
        $this->validate([
            'name' => 'required|min:2|unique:storage_systems,name',
            'type' => 'required|string',
            'pos_x' => 'required|numeric',
            'pos_y' => 'required|numeric',
            'pos_z' => 'required|numeric',
            'columns' => 'required|integer|min:1',
            'rows' => 'required|integer|min:1',
            'levels' => 'required|integer|min:1',
            'spacing' => 'required|numeric',
            'size_x' => 'required|numeric|min:0.1',
            'size_y' => 'required|numeric|min:0.1',
            'size_z' => 'required|numeric|min:0.1',
            'orientation' => 'required|integer',
        ]);

        $isOccupied = StorageSystem::isSpaceOccupied(
            $this->pos_x, 
            $this->pos_z, 
            $this->size_x, 
            $this->size_z
        );

        if ($isOccupied) {
            $this->addError('pos_x', 'Nu poți adăuga raftul aici! Se suprapune cu un alt raft existent din depozit.');
            return;
        }

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

        $this->dispatch('system-updated');

        $this->reset();
        $this->generateSuggestValues();
        
    }

    public function render()
    {
        return view('livewire.storage-system-form');
    }
}