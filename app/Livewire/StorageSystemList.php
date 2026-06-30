<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\StorageSystem;

class StorageSystemList extends Component
{
    #[On('system-updated')]
    public function refreshList() {}

    public function deleteSystem($id)
    {
        StorageSystem::destroy($id);
    }

    public function render()
    {
        return view('livewire.storage-system-list', [
            'allSystems' => StorageSystem::all(),
        ]);
    }
}