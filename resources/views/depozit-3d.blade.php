<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vizualizare 3D Depozit</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <style>
        body { margin: 0; overflow: hidden; background-color: #0f172a; font-family: sans-serif; }
        #canvas-container { width: 100vw; height: 100vh; position: absolute; top: 0; left: 0; z-index: 1; }
        #ui-layer { 
            position: absolute; 
            top: 20px; 
            left: 20px; 
            z-index: 10; 
        }
    </style>

    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
                "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
            }
        }
    </script>
</head>
<body>

    <div id="ui-layer">
        <div class="bg-slate-900/90 text-white p-4 rounded-xl border border-slate-800 shadow-xl backdrop-blur interactive space-y-2">
            <h1 class="text-base font-black uppercase tracking-tight text-indigo-400">Scenă Test 3D</h1>
            <div class="space-y-1 text-xs text-slate-400">
            <p><strong>Control:</strong> Click stânga + Drag pe un obiect pentru a-l muta (stânga/dreapta, față/spate).</p>
            <p>În timp ce muți un produs, apasă tastele <strong>W</strong> și <strong>S</strong> pentru a-l ridica sau coborî.</p>
            <p>Click dreapta + Drag în spațiul gol pentru a muta camera.</p>
        </div>
            <a href="{{ route('systems.index') }}" class="inline-block bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 transition-all mt-2">
                Înapoi la Management
            </a>
        </div>
    </div>

    <div id="canvas-container" 
        data-url-load-systems="{{ route('api.systems.index') }}" 
        data-url-update-systems="{{ url('/api/storage-systems') }}"
        data-url-load-products="{{ route('api.products.index') }}"
        data-url-update-products="{{ url('/api/products') }}">
    </div>

    <div id="details-sidebar" class="hidden fixed top-4 right-4 w-80 bg-slate-900/95 border border-slate-700 rounded-lg p-4 text-slate-200 shadow-2xl backdrop-blur max-h-[85vh] overflow-y-auto z-50">
    <div class="flex justify-between items-center border-b border-slate-700 pb-2 mb-3">
        <h3 id="sidebar-title" class="font-bold text-sm text-indigo-400">Detalii Raft</h3>
        <button id="close-sidebar" class="text-xs text-slate-400 hover:text-white bg-slate-800 px-2 py-1 rounded">Închide</button>
    </div>
    <div id="sidebar-content" class="text-xs space-y-3">
        </div>
    </div>

    <script type="module" src="{{ asset('js/depozit-3d.js') }}"></script>
</body>
</html>