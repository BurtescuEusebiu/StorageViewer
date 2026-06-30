<div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider">Denumire</th>
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider">Tip</th>
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider">Dimensiuni (Grid)</th>
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider">Poziție 3D (X, Y, Z)</th>
                <th class="p-4 text-xs font-bold text-indigo-600 uppercase tracking-wider text-right">Acțiuni</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($items as $system)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 font-bold text-gray-900">{{ $system->name }}</td>
                    
                    <td class="p-4 text-sm text-gray-700">
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md font-medium text-xs uppercase">
                            {{ $system->type }}
                        </span>
                    </td>
                    
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        {{ $system->rows }}x{{ $system->columns }}×{{ $system->levels }}
                        <div class="text-xs text-gray-700">spacing: {{ $system->spacing }}</div>
                    </td>
                    
                    <td class="p-4 text-sm text-gray-700 font-medium text-xs">
                        X: {{ $system->pos_x }} | Y: {{ $system->pos_y }} | Z: {{ $system->pos_z }}
                        <div class="text-gray-700 font-sans mt-0.5">Rotire: {{ $system->orientation }}°</div>
                    </td>
                    
                    <td class="p-4 text-right">
                        <button wire:click="deleteSystem({{ $system->id }})" 
                                class="text-red-500 hover:text-red-700 font-bold text-sm transition-colors cursor-pointer p-2 rounded-lg hover:bg-red-50">
                            Șterge
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>