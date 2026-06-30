<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Storage;

class StorageList extends Component
{
    public $editingProductId;
    public $editProductName;
    public $editProductRow;
    public $editProductColumn;
    public $editProductLevel;
    public $showProductEditModal = false;

    public $maxRows = 0;
    public $maxColumns = 0;
    public $maxLevels = 0;


    #[On('storage-updated')]
    public function refreshList()
    {
    }

    public function editProduct($id)
    {
        $product = Storage::findOrFail($id); 

        $system = \App\Models\StorageSystem::findOrFail($product->storage_system_id);
        
        $this->editingProductId = $product->id;
        $this->editProductName = $product->name;
        $this->editProductRow = $product->row;
        $this->editProductColumn = $product->column;
        $this->editProductLevel = $product->level;

        $this->maxRows = $system->rows;
        $this->maxColumns = $system->columns;
        $this->maxLevels = $system->levels;

        $this->showProductEditModal = true; 
    }

    public function updateProduct()
    {
        $this->validate([
            'editProductName' => 'required|string|max:255',
            'editProductRow' => 'required|integer|min:1',
            'editProductColumn' => 'required|integer|min:1',
            'editProductLevel' => 'required|integer|min:1',
        ]);

        $product = Storage::findOrFail($this->editingProductId);
        
        $product->update([
            'name' => $this->editProductName,
            'row' => $this->editProductRow,
            'column' => $this->editProductColumn,
            'level' => $this->editProductLevel,
        ]);

        $this->showProductEditModal = false;
        
        $this->dispatch('product-updated'); 
        
    }
    

    public function deleteStorage($id)
    {
        Storage::destroy($id);
    }

    public function render()
    {
        return view('livewire.storage-list', [
            'allStorages' => Storage::with('system')->get(),
        ]);
    }
}