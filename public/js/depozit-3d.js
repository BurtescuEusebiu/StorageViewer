import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

class Engine3D {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color('#0f172a');
        this.initCamera();
        this.initRenderer();
        this.initLights();
        this.initControls();
        window.addEventListener('resize', () => this.onWindowResize());
        this.animate();
    }
    initCamera() {
        this.camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
        this.camera.position.set(12, 18, 22);
    }
    initRenderer() {
        this.renderer = new THREE.WebGLRenderer({ antialias: true });
        this.renderer.setSize(window.innerWidth, window.innerHeight);
        this.container.appendChild(this.renderer.domElement);
    }
    initLights() {
        this.scene.add(new THREE.AmbientLight(0xffffff, 1));
        this.scene.add(new THREE.GridHelper(40, 40, '#4f46e5', '#334155'));
    }
    initControls() {
        this.controls = new OrbitControls(this.camera, this.renderer.domElement);
        this.controls.enableDamping = true;
        this.controls.dampingFactor = 0.05;
    }
    onWindowResize() {
        this.camera.aspect = window.innerWidth / window.innerHeight;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(window.innerWidth, window.innerHeight);
    }
    animate() {
        requestAnimationFrame(() => this.animate());
        this.controls.update();
        this.renderer.render(this.scene, this.camera);
    }
}

class BaseObject3D {
    constructor(manager, dbId, name, x, y, z, orientation, updateUrlKey) {
        this.manager = manager;
        this.scene = manager.scene;
        this.dbId = dbId;
        this.name = name;
        this.updateUrlKey = updateUrlKey;
        this.mesh = null;
        this.initialPos = { x, y, z };
        this.initialOrientation = orientation;
    }

    initMesh(mesh, type) {
        this.mesh = mesh;
        this.mesh.position.set(this.initialPos.x, this.initialPos.y, this.initialPos.z);
        this.mesh.rotation.y = this.initialOrientation;
        
        this.mesh.userData = { 
            type: type, 
            dbId: this.dbId,
            name: this.name,
            parentInstance: this 
        };

        this.scene.add(this.mesh);
        this.manager.racks.push(this.mesh);
    }

    async savePosition() {
        const baseUrl = this.manager[this.updateUrlKey];
        const targetUrl = `${baseUrl}/${this.dbId}/position`;
        const payload = this.getPayload();

        try {
            const response = await fetch(targetUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            if (response.ok) {
                console.log(`Salvare reușită în DB pentru "${this.name}"!`, payload);
            }
        } catch (error) {
            console.error("Eroare la salvare:", error);
        }
    }
}


class StorageSystem extends BaseObject3D {
    constructor(manager, dbId, name, x, y, z, orientation, size_x, size_y, size_z, rows, columns, levels, spacing) {
        super(manager, dbId, name, x, y, z, orientation, 'updateSystemsUrl');
        
        this.rowsCount = rows || 1;
        this.columnsCount = columns || 5;
        this.levelsCount = levels || 3;
        this.spacing = spacing !== null && spacing !== undefined ? spacing : 0.1;

        this.width = size_x > 0 ? size_x : 3.0;   
        this.height = size_y > 0 ? size_y : 4.0;  
        this.length = size_z > 0 ? size_z : 1.5;  

        const geometry = new THREE.BoxGeometry(this.width, this.height, this.length);
        const material = new THREE.MeshStandardMaterial({ 
            color: '#7b7b83', 
            roughness: 0.4,
            transparent: true,
            opacity: 0.15 
        });
        const mesh = new THREE.Mesh(geometry, material);
        
        this.initialPos.y += this.height / 2;
        this.initMesh(mesh, 'rack');

        const edges = new THREE.EdgesGeometry(this.mesh.geometry);

        const lineMat = new THREE.LineBasicMaterial({ 
            color: '#ffffff', 
            transparent: true,
            opacity: 0.5
        });

        const outline = new THREE.LineSegments(edges, lineMat);
        outline.raycast = function() {};
        this.mesh.add(outline);

        this.createShelvesVisuals();
    }

    getSlotLocalPosition(row, column, level) {
        const slotWidth = (this.width - (this.spacing * (this.columnsCount + 1))) / this.columnsCount;
        const slotHeight = (this.height - (this.spacing * (this.levelsCount + 1))) / this.levelsCount;
        const slotDepth = (this.length - (this.spacing * (this.rowsCount + 1))) / this.rowsCount;

        const cIndex = Math.max(0, Math.min(column - 1, this.columnsCount - 1));
        const lIndex = Math.max(0, Math.min(level - 1, this.levelsCount - 1));
        const rIndex = Math.max(0, Math.min(row - 1, this.rowsCount - 1));

        const localX = -this.width / 2 + this.spacing + (cIndex * (slotWidth + this.spacing)) + (slotWidth / 2);
        const localY = -this.height / 2 + this.spacing + (lIndex * (slotHeight + this.spacing)) + (slotHeight / 2);
        const localZ = -this.length / 2 + this.spacing + (rIndex * (slotDepth + this.spacing)) + (slotDepth / 2);

        return new THREE.Vector3(localX, localY, localZ);
    }

    getClosestSlotIndices(localPoint) {
        const slotWidth = (this.width - (this.spacing * (this.columnsCount + 1))) / this.columnsCount;
        const slotHeight = (this.height - (this.spacing * (this.levelsCount + 1))) / this.levelsCount;
        const slotDepth = (this.length - (this.spacing * (this.rowsCount + 1))) / this.rowsCount;

        const colIdx = Math.round((localPoint.x + this.width / 2 - this.spacing - slotWidth / 2) / (slotWidth + this.spacing));
        const lvlIdx = Math.round((localPoint.y + this.height / 2 - this.spacing - slotHeight / 2) / (slotHeight + this.spacing));
        const rowIdx = Math.round((localPoint.z + this.length / 2 - this.spacing - slotDepth / 2) / (slotDepth + this.spacing));

        return {
            row: Math.max(1, Math.min(rowIdx + 1, this.rowsCount)),
            column: Math.max(1, Math.min(colIdx + 1, this.columnsCount)),
            level: Math.max(1, Math.min(lvlIdx + 1, this.levelsCount))
        };
    }

    createShelvesVisuals() {
        const slotHeight = this.height / this.levelsCount;
        const slotDepth = this.length / this.rowsCount;

        const canvas = document.createElement('canvas');
        canvas.width = 64;
        canvas.height = 64;
        const ctx = canvas.getContext('2d');
        
        ctx.fillStyle = 'rgba(10, 10, 11, 0.55)'; 
        ctx.fillRect(0, 0, 64, 64);
            
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.07)';
        ctx.lineWidth = 4;
        ctx.beginPath();
        for (let k = -64; k < 124; k += 16) {
            ctx.moveTo(k, 0);
            ctx.lineTo(k + 64, 64);
        }
        ctx.stroke();

        const hatchedTexture = new THREE.CanvasTexture(canvas);
        hatchedTexture.wrapS = THREE.RepeatWrapping;
        hatchedTexture.wrapT = THREE.RepeatWrapping;
        hatchedTexture.repeat.set(this.width * 2, this.length * 2); 

        const shelfMat = new THREE.MeshStandardMaterial({ 
            map: hatchedTexture,
            transparent: true, 
            roughness: 1,
            metalness: 0,
            depthTest: false
        });

        for (let i = 0; i < this.levelsCount; i++) {
            const shelfGeom = new THREE.BoxGeometry(this.width, 0.04, this.length);
            const shelfMesh = new THREE.Mesh(shelfGeom, shelfMat);
            shelfMesh.position.y = -this.height / 2 + (i * slotHeight);
            shelfMesh.frustumCulled = false;
            shelfMesh.userData = { 
            type: 'shelf', 
            levelIndex: i + 1
            };
            this.mesh.add(shelfMesh);

            const edges = new THREE.EdgesGeometry(shelfGeom);
            const lineMat = new THREE.LineBasicMaterial({ color: '#ffffff', transparent: true, opacity: 0.4, depthTest: false });
            const outline = new THREE.LineSegments(edges, lineMat);
            outline.raycast = function() {};
            shelfMesh.add(outline);
        }
    }

    getPayload() {
        return {
            pos_x: this.mesh.position.x,
            pos_y: this.mesh.position.y - (this.height / 2),
            pos_z: this.mesh.position.z,
            orientation: this.mesh.rotation.y,
            size_x: this.width,
            size_y: this.height,
            size_z: this.length,
            type: 'rack',
            rows: this.rowsCount,
            columns: this.columnsCount,
            levels: this.levelsCount,
            spacing: this.spacing
        };
    }
}

class Product extends BaseObject3D {
    constructor(manager, dbId, name, row, column, level, storageSystemId) {
        super(manager, dbId, name, 0, 0, 0, 0, 'updateProductsUrl');
        
        this.storageSystemId = storageSystemId;
        this.row = row;
        this.column = column;
        this.level = level;

        const geometry = new THREE.BoxGeometry(0.5, 0.5, 0.5);
        const material = new THREE.MeshStandardMaterial({ color: '#f59e0b', roughness: 1, depthTest: true });
        const mesh = new THREE.Mesh(geometry, material);

        this.initMesh(mesh, 'product');
        this.attachToRack(storageSystemId, row, column, level);
    }

    attachToRack(rackId, row, column, level) {
        const parentRack = this.manager.systemsMap.get(rackId);

        if (parentRack && parentRack.mesh) {
            this.storageSystemId = rackId;
            this.row = row;
            this.column = column;
            this.level = level;

            if (this.mesh.parent) this.mesh.parent.remove(this.mesh);
            parentRack.mesh.add(this.mesh);

            const localPos = parentRack.getSlotLocalPosition(row, column, level);
            this.mesh.position.copy(localPos);
            this.mesh.updateMatrixWorld();
        } else {
            console.warn(`Raftul ${rackId} nu există.`);
        }
    }

    getPayload() {
        return {
            storage_system_id: this.storageSystemId,
            row: this.row - 1,
            column: this.column - 1,
            level: this.level
        };
    }
}

class WarehouseManager {
    constructor(engine) {
        this.engine = engine;
        this.scene = engine.scene;
        this.racks = []; 
        this.systemsMap = new Map(); 

        this.clickStartTime = 0;
        this.clickStartMouse = new THREE.Vector2();
        this.setupSidebar();

        this.offset = new THREE.Vector3();

        this.setupInteractions();

        this.loadSystemsUrl = this.engine.container.getAttribute('data-url-load-systems');
        this.updateSystemsUrl = this.engine.container.getAttribute('data-url-update-systems');
        this.loadProductsUrl = this.engine.container.getAttribute('data-url-load-products');
        this.updateProductsUrl = this.engine.container.getAttribute('data-url-update-products');

        this.raycaster = new THREE.Raycaster();
        this.mouse = new THREE.Vector2();
        this.selectedObject = null;
        this.selectedType = null;
        
        this.plane = new THREE.Plane(new THREE.Vector3(0, 1, 0), 0);
        this.intersectionPoint = new THREE.Vector3();
        
        this.setupInteractions();
        this.loadWarehouseData().then(() => {
            this.startPolling(1000);
        });
    }

    checkRackCollision(movingRackInstance, targetX, targetZ) {
        const r1HalfWidth = movingRackInstance.width / 2;
        const r1HalfLength = movingRackInstance.length / 2;

        const r1MinX = targetX - r1HalfWidth;
        const r1MaxX = targetX + r1HalfWidth;
        const r1MinZ = targetZ - r1HalfLength;
        const r1MaxZ = targetZ + r1HalfLength;

        for (let [rackId, rackInstance] of this.systemsMap) {
            if (rackId === movingRackInstance.dbId) continue;
            const r2X = rackInstance.mesh.position.x;
            const r2Z = rackInstance.mesh.position.z;
            const r2HalfWidth = rackInstance.width / 2;
            const r2HalfLength = rackInstance.length / 2;

            const r2MinX = r2X - r2HalfWidth;
            const r2MaxX = r2X + r2HalfWidth;
            const r2MinZ = r2Z - r2HalfLength;
            const r2MaxZ = r2Z + r2HalfLength;

            const overlapX = r1MinX < r2MaxX && r1MaxX > r2MinX;
            const overlapZ = r1MinZ < r2MaxZ && r1MaxZ > r2MinZ;

            if (overlapX && overlapZ) {
                return true;
            }
        }
        return false;
    }

    startPolling(intervalMs) {
            this.pollingInterval = setInterval(async () => {
                if (this.selectedObject) {
                    console.log("Polling amânat: utilizatorul mută un obiect.");
                    return;
                }
                await this.loadWarehouseData();
            }, intervalMs);
        }

    stopPolling() {
        if (this.pollingInterval) clearInterval(this.pollingInterval);
    }

    clearProductsMesh() {
    this.racks = this.racks.filter(mesh => {
        if (mesh.userData && mesh.userData.type === 'product') {
            if (mesh.parent) mesh.parent.remove(mesh);
            return false;
        }
        return true;
    });

    this.systemsMap.forEach((rackInstance) => {
        if (rackInstance.mesh) {
            const toRemove = [];
            rackInstance.mesh.traverse((child) => {
                if (child.userData && child.userData.type === 'product') {
                    toRemove.push(child);
                }
            });
            toRemove.forEach(child => rackInstance.mesh.remove(child));
        }
    });
    }

    async loadWarehouseData() {
            try {
                const [systemsRes, productsRes] = await Promise.all([
                    fetch(this.loadSystemsUrl),
                    fetch(this.loadProductsUrl)
                ]);

                const systems = await systemsRes.json();
                const products = await productsRes.json();

                const serverSystemIds = systems.map(s => s.id);
                for (let [rackId, rackInstance] of this.systemsMap) {
                    if (!serverSystemIds.includes(rackId)) {
                        this.scene.remove(rackInstance.mesh);
                        
                        this.racks = this.racks.filter(mesh => mesh !== rackInstance.mesh);
                        
                        this.systemsMap.delete(rackId);
                    }
                }

                systems.forEach(s => {
                    if (this.systemsMap.has(s.id)) {
                        const systemInstance = this.systemsMap.get(s.id);
                        systemInstance.mesh.position.set(s.pos_x, s.pos_y + systemInstance.height / 2, s.pos_z);
                        systemInstance.mesh.rotation.y = s.orientation;
                    } else {
                        const systemInstance = new StorageSystem(
                            this, s.id, s.name, 
                            s.pos_x, s.pos_y, s.pos_z, s.orientation, 
                            s.size_x, s.size_y, s.size_z, 
                            s.rows, s.columns, s.levels, s.spacing
                        );
                        this.systemsMap.set(s.id, systemInstance);
                    }
                });

                this.clearProductsMesh();

                products.forEach(p => {
                    new Product(this, p.id, p.name, p.row + 1, p.column + 1, p.level, p.storage_system_id);
                });

            } catch (error) {
                console.error("Eroare la sincronizarea datelor (polling):", error);
            }
        }

    setupSidebar() {
    const closeBtn = document.getElementById('close-sidebar');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            document.getElementById('details-sidebar').classList.add('hidden');
        });
    }
    }

    handleObjectClick(object) {
        let rackInstance = null;
        let clickedLevel = null;

        if (object.userData.type === 'product') {
            const productInstance = object.userData.parentInstance;
            rackInstance = this.systemsMap.get(productInstance.storageSystemId);
            clickedLevel = productInstance.level;
        } 
        else if (object.userData.type === 'shelf') {
            rackInstance = object.parent.userData.parentInstance;
            clickedLevel = object.userData.levelIndex;
        } 
        else {
            rackInstance = object.userData.parentInstance || object.parent?.userData.parentInstance;
        }

        if (!rackInstance) return;

            const productsInRack = [];
            rackInstance.mesh.traverse((child) => {
                if (child.userData && child.userData.type === 'product') {
                    productsInRack.push(child.userData.parentInstance);
                }
            });

            const sidebar = document.getElementById('details-sidebar');
            const title = document.getElementById('sidebar-title');
            const content = document.getElementById('sidebar-content');

            if (!sidebar || !title || !content) return;

            if (clickedLevel !== null) {
                title.innerText = `${rackInstance.name} - Nivelul ${clickedLevel}`;
            } else {
                title.innerText = `Detalii: ${rackInstance.name}`;
            }
            
            let html = `
                <div class="bg-slate-800 p-2 rounded border border-slate-700">
                    <p><strong>Total structură:</strong> ${rackInstance.rowsCount} x ${rackInstance.columnsCount} x ${rackInstance.levelsCount}</p>
                    <p class="text-indigo-400 mt-1"><strong>Total produse în raft:</strong> ${productsInRack.length}</p>
                </div>
                <h4 class="font-bold text-slate-400 border-b border-slate-700 pb-1 mt-2">
                    ${clickedLevel !== null ? `Conținut Nivel ${clickedLevel}:` : 'Inventar complet pe Niveluri:'}
                </h4>
            `;

            for (let l = rackInstance.levelsCount; l >= 1; l--) {
            if (clickedLevel !== null && clickedLevel !== l) {
                continue;
            }

            const productsOnLevel = productsInRack.filter(p => p.level === l);
            
            html += `
                <div class="bg-slate-950 p-2 rounded border border-slate-800/80 mt-1">
                    <div class="flex justify-between font-semibold text-slate-300">
                        <span>Nivelul ${l} ${l === 0 ? '<span class="text-xs text-slate-500">(Baza)</span>' : ''}</span>
                        <span class="text-amber-500">${productsOnLevel.length} prod.</span>
                    </div>
            `;

            if (productsOnLevel.length > 0) {
                html += `<ul class="list-disc list-inside mt-1 space-y-1 text-[11px] text-slate-400">`;
                productsOnLevel.forEach(p => {
                    html += `<li><strong class="text-slate-200">${p.name}</strong> (Rând: ${p.row}, Col: ${p.column})</li>`;
                });
                html += `</ul>`;
            } else {
                html += `<p class="text-[11px] text-slate-600 italic mt-0.5">Nivel gol</p>`;
            }

            html += `</div>`;
        }

            content.innerHTML = html;
            sidebar.classList.remove('hidden');
    }
    
    setupInteractions() {
        const canvas = this.engine.renderer.domElement;
        canvas.addEventListener('mousedown', (e) => this.onMouseDown(e));
        canvas.addEventListener('mousemove', (e) => this.onMouseMove(e));
        window.addEventListener('mouseup', () => this.onMouseUp());
        window.addEventListener('keydown', (e) => this.onKeyDown(e));
    }
    
    onMouseDown(event) {
            if (event.button !== 0) return;

            this.updateMouseCoordinates(event);
            this.clickStartTime = performance.now();
            this.clickStartMouse.copy(this.mouse);

            this.raycaster.setFromCamera(this.mouse, this.engine.camera);
            const intersects = this.raycaster.intersectObjects(this.racks, true);

            if (intersects.length > 0) {
                const productIntersect = intersects.find(i => i.object.userData.type === 'product');
                const shelfIntersect = intersects.find(i => i.object.userData.type === 'shelf');
                
                this.actualClickedObject = productIntersect?.object || shelfIntersect?.object || intersects[0].object;

                if (productIntersect) {
                    this.selectedObject = productIntersect.object;
                } else if (shelfIntersect) {
                    this.selectedObject = shelfIntersect.object.parent; 
                } else {
                    this.selectedObject = intersects[0].object;
                }

                this.selectedType = this.selectedObject.userData.type;
                this.engine.controls.enabled = false;

                const worldPos = new THREE.Vector3();
                this.selectedObject.getWorldPosition(worldPos);

                if (this.selectedType === 'rack') {
                    this.rackBackupX = this.selectedObject.position.x;
                    this.rackBackupZ = this.selectedObject.position.z;
                }

                if (this.selectedType === 'product') {
                    this.scene.add(this.selectedObject); 
                    this.selectedObject.position.copy(worldPos);
                    this.selectedObject.updateMatrixWorld();
                    this.plane.setFromNormalAndCoplanarPoint(new THREE.Vector3(0, 1, 0), this.selectedObject.position);
                } else {
                    this.plane.setFromNormalAndCoplanarPoint(new THREE.Vector3(0, 1, 0), new THREE.Vector3(worldPos.x, worldPos.y, worldPos.z));
                }

                if (this.raycaster.ray.intersectPlane(this.plane, this.intersectionPoint)) {
                    this.offset.copy(worldPos).sub(this.intersectionPoint);
                }
            }
        }
    onMouseMove(event) {
        if (!this.selectedObject) return;

        this.updateMouseCoordinates(event);
        this.raycaster.setFromCamera(this.mouse, this.engine.camera);

        if (this.raycaster.ray.intersectPlane(this.plane, this.intersectionPoint)) {
            this.selectedObject.position.x = this.intersectionPoint.x + this.offset.x;
            this.selectedObject.position.z = this.intersectionPoint.z + this.offset.z;
        }
    }

    onMouseUp() {
            if (!this.selectedObject) {
                this.engine.controls.enabled = true;
                return;
            }

            const dragDuration = performance.now() - this.clickStartTime;
            const dragDistance = this.mouse.distanceTo(this.clickStartMouse);

            if (dragDuration < 250 && dragDistance < 0.02) {
                this.handleObjectClick(this.actualClickedObject); 
                
                if (this.selectedType === 'product') {
                    const currentInstance = this.selectedObject.userData.parentInstance;
                    currentInstance.attachToRack(currentInstance.storageSystemId, currentInstance.row, currentInstance.column, currentInstance.level);
                }
            } else {
                const currentInstance = this.selectedObject.userData.parentInstance;

                if (this.selectedType === 'product') {
                    let closestRack = null;
                    let minDistance = Infinity;
                    const productWorldPos = this.selectedObject.position;

                    for (let [rackId, rackInstance] of this.systemsMap) {
                        const rackWorldPos = new THREE.Vector3();
                        rackInstance.mesh.getWorldPosition(rackWorldPos);

                        const dist = Math.sqrt(
                            Math.pow(productWorldPos.x - rackWorldPos.x, 2) + 
                            Math.pow(productWorldPos.z - rackWorldPos.z, 2)
                        );

                        if (dist < (rackInstance.width * 1.5) && dist < minDistance) {
                            minDistance = dist;
                            closestRack = rackInstance;
                        }
                    }

                    if (closestRack) {
                        const localPoint = productWorldPos.clone();
                        closestRack.mesh.updateMatrixWorld();
                        localPoint.applyMatrix4(closestRack.mesh.matrixWorld.clone().invert());

                        const slots = closestRack.getClosestSlotIndices(localPoint);
                        currentInstance.attachToRack(closestRack.dbId, slots.row, slots.column, slots.level);
                    } else {
                        currentInstance.attachToRack(currentInstance.storageSystemId, currentInstance.row, currentInstance.column, currentInstance.level);
                    }

                    currentInstance.savePosition();
                } 
                else if (this.selectedType === 'rack') {
                    const isColliding = this.checkRackCollision(
                        currentInstance, 
                        this.selectedObject.position.x, 
                        this.selectedObject.position.z
                    );

                    if (isColliding) {
                        console.warn("Suprapunere detectată! Îi dăm snap înapoi.");
                        this.selectedObject.position.x = this.rackBackupX;
                        this.selectedObject.position.z = this.rackBackupZ;
                        this.selectedObject.updateMatrixWorld();
                    } else {
                        currentInstance.savePosition();
                    }
                }
            }

            this.selectedObject = null;
            this.selectedType = null;
            this.engine.controls.enabled = true;
    }

    recalculateOffset() {
        this.plane.setFromNormalAndCoplanarPoint(new THREE.Vector3(0, 1, 0), this.selectedObject.position);
        
        this.raycaster.setFromCamera(this.mouse, this.engine.camera);
        if (this.raycaster.ray.intersectPlane(this.plane, this.intersectionPoint)) {
            this.offset.copy(this.selectedObject.position).sub(this.intersectionPoint);
        }
    }

    onKeyDown(event) {
        if (!this.selectedObject || this.selectedType !== 'product') return;

        const key = event.key.toLowerCase();
        const step = 0.5;

        if (key === 'w') {
            this.selectedObject.position.y += step;
            this.recalculateOffset(); 
            console.log(`Înălțime nouă: ${this.selectedObject.position.y}m`);
        } else if (key === 's') {
            if (this.selectedObject.position.y - step >= 0.25) {
                this.selectedObject.position.y -= step;
                this.recalculateOffset(); 
                console.log(`Înălțime nouă: ${this.selectedObject.position.y}m`);
            }
        }
    }

    updateMouseCoordinates(event) {
        const rect = this.engine.renderer.domElement.getBoundingClientRect();
        const localX = event.clientX - rect.left;
        const localY = event.clientY - rect.top;
        this.mouse.x = (localX / rect.width) * 2 - 1;
        this.mouse.y = -(localY / rect.height) * 2 + 1;
    }
}

const worldPosPoint = new THREE.Vector3();

window.addEventListener('DOMContentLoaded', () => {
    const engine = new Engine3D('canvas-container');
    const warehouse = new WarehouseManager(engine);
});