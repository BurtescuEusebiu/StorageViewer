<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\StorageForm;
use App\Livewire\StorageList;
use App\Livewire\StorageSystemForm;
use App\Livewire\StorageSystemList;
use Illuminate\Http\Request;

Route::get('/', StorageSystemList::class)->name('home');

Route::get('/produse/adauga', StorageForm::class)->name('storages.create');
Route::get('/produse/lista', StorageList::class)->name('storages.index');
Route::get('/sisteme/adauga', StorageSystemForm::class)->name('systems.create');
Route::get('/sisteme/lista', StorageSystemList::class)->name('systems.index');
Route::get('/depozit-3d', function () { return view('depozit-3d');})->name('depozit.3d');

Route::get('/api/storage-systems', function () {
    return response()->json(App\Models\StorageSystem::all());
})->name('api.systems.index');

Route::put('/api/storage-systems/{id}/position', function (Request $request, $id) {
    $system = App\Models\StorageSystem::findOrFail($id);
    
    $system->update([
        'pos_x'        => $request->input('pos_x'),
        'pos_y'        => $request->input('pos_y'),
        'pos_z'        => $request->input('pos_z'),
        'size_x'       => $request->input('size_x'),
        'size_y'       => $request->input('size_y'),
        'size_z'       => $request->input('size_z'),
        'orientation'  => $request->input('orientation'),
        'type'         => $request->input('type'),
        'rows'         => $request->input('rows'),
        'columns'      => $request->input('columns'),
        'levels'       => $request->input('levels'),
        'spacing'      => $request->input('spacing'),
    ]);

    return response()->json(['success' => true]);
});

Route::get('/api/products', function () {
    return response()->json(App\Models\Storage::all());
})->name('api.products.index');

Route::put('/api/products/{id}/position', function (Request $request, $id) {
    $product = App\Models\Storage::findOrFail($id);
    
    $product->update([
        'storage_system_id' => $request->input('storage_system_id'),
        'row'               => $request->input('row'),
        'column'            => $request->input('column'),
        'level'             => $request->input('level'),
    ]);

    return response()->json(['success' => true]);
});