<div class="p-8 max-w-6xl mx-auto space-y-6">
    
    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800">Listă Produse</h2>
        
        <a href="{{ route('storages.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-md shadow-indigo-50">
            Adaugă Produs Nou
        </a>
    </div>

    <x-storage-table :items="$allStorages" />

    @if($showProductEditModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white border border-gray-200 p-6 rounded-2xl w-96 text-gray-800 shadow-2xl">
            <h3 class="font-bold text-lg text-indigo-600 mb-4">Modifică Detalii Produs</h3>
            
            <form wire:submit.prevent="updateProduct" class="space-y-4 text-sm">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Nume Produs</label>
                    <input type="text" wire:model="editProductName" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:outline-none focus:border-indigo-500 transition-all">
                </div>
                
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Rând (1 - {{ $maxRows }})</label>
                        <input type="number" 
                            min="1" 
                            max="{{ $maxRows }}" 
                            wire:model="editProductRow" 
                            class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:outline-none focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Coloană (1 - {{ $maxColumns }})</label>
                        <input type="number" 
                            min="1" 
                            max="{{ $maxColumns }}" 
                            wire:model="editProductColumn" 
                            class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:outline-none focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nivel (1 - {{ $maxLevels }})</label>
                        <input type="number" 
                            min="1" 
                            max="{{ $maxLevels }}" 
                            wire:model="editProductLevel" 
                            class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" wire:click="$set('showProductEditModal', false)" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-xs font-bold transition-all">Anulează</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded-xl text-xs text-white font-bold transition-all shadow-md">Salvează</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>