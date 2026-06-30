@props(['items'])

<div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider">Cod / Nume</th>
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider">Sistem Asociat</th>
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider text-center">Poziție Logică</th>
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider text-right">Acțiuni</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4">
                        <div class="font-bold text-gray-900">{{ $item->code }}</div>
                        <div class="text-xs text-gray-700 font-medium mt-0.5">{{ $item->name }}</div>
                    </td>
                    
                    <td class="p-4 text-sm text-gray-700">
                        <span class="px-2 py-1 bg-indigo-50 text-grey-700 rounded-md font-medium text-xs uppercase border border-indigo-100">
                            {{ $item->system->name ?? 'Fără sistem' }}
                        </span>
                    </td>
                    
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-1.5 font-mono text-xs text-gray-700 font-medium">
                            <span class="bg-gray-100 px-2 py-1 rounded-md">R:{{ $item->row }}</span>
                            <span class="bg-gray-100 px-2 py-1 rounded-md">C:{{ $item->column }}</span>
                            <span class="bg-gray-100 px-2 py-1 rounded-md">L:{{ $item->level }}</span>
                        </div>
                    </td>
                    
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            
                            <button wire:click="editProduct({{ $item->id }})" 
                                    class="text-amber-500 hover:text-amber-700 font-bold text-sm cursor-pointer p-2 rounded-lg hover:bg-amber-50">
                                Modifică
                            </button>

                            <button wire:click="deleteProduct({{ $item->id }})" 
                                    wire:confirm="Sigur vrei să ștergi acest produs?"
                                    class="text-red-500 hover:text-red-700 font-bold text-sm cursor-pointer p-2 rounded-lg hover:bg-red-50">
                                Șterge
                            </button>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-700 text-sm font-medium">Nu există nicio locație definită.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>