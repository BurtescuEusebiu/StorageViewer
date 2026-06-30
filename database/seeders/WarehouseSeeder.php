<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StorageSystem;
use App\Models\Storage;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('PRAGMA foreign_keys = OFF;');
        Storage::truncate();
        StorageSystem::truncate();
        \Illuminate\Support\Facades\DB::statement('PRAGMA foreign_keys = ON;');

        $slotWidth = 0.8;
        $slotDepth = 1.2;
        $levelHeight = 0.8;

        $columns1 = 6;
        $rows1 = 1;
        $levels1 = 4;

        $rack1X = -12.0;
        $rack1Z = 0.0;
        
        $sizeX1 = $columns1 * $slotWidth;
        $sizeZ1 = $rows1 * $slotDepth;
        $sizeY1 = $levels1 * $levelHeight;

        if (!StorageSystem::isSpaceOccupied($rack1X, $rack1Z, $sizeX1, $sizeZ1)) {
            $rack1 = StorageSystem::create([
                'name' => 'Raft Principal Stânga',
                'type' => 'rack',
                'pos_x' => $rack1X,
                'pos_y' => 0.0,
                'pos_z' => $rack1Z,
                'columns' => $columns1,
                'rows' => $rows1,
                'levels' => $levels1,
                'spacing' => 0.1,
                'size_x' => $sizeX1,
                'size_y' => $sizeY1,
                'size_z' => $sizeZ1,
                'orientation' => 0,
            ]);

            Storage::create([
                'storage_system_id' => $rack1->id,
                'name' => 'Cutie Procesoare i9',
                'code' => 'CPU-I9-001',
                'row' => 0, 'column' => 0, 'level' => 1,
            ]);

            Storage::create([
                'storage_system_id' => $rack1->id,
                'name' => 'Plăci Video RTX 5090',
                'code' => 'GPU-RTX-5090',
                'row' => 0, 'column' => 3, 'level' => 3,
            ]);
        }

        $columns2 = 4;
        $rows2 = 10;
        $levels2 = 3;

        $rack2X = 8.0; 
        $rack2Z = 0.0;
        
        $sizeX2 = $columns2 * $slotWidth;
        $sizeZ2 = $rows2 * $slotDepth;
        $sizeY2 = $levels2 * $levelHeight;

        if (!StorageSystem::isSpaceOccupied($rack2X, $rack2Z, $sizeX2, $sizeZ2)) {
            $rack2 = StorageSystem::create([
                'name' => 'Raft Compact Dreapta',
                'type' => 'rack',
                'pos_x' => $rack2X,
                'pos_y' => 0.0,
                'pos_z' => $rack2Z,
                'columns' => $columns2,
                'rows' => $rows2,
                'levels' => $levels2,
                'spacing' => 0.1,
                'size_x' => $sizeX2,
                'size_y' => $sizeY2,
                'size_z' => $sizeZ2,
                'orientation' => 0,
            ]);

            Storage::create([
                'storage_system_id' => $rack2->id,
                'name' => 'Monitoare OLED 4K',
                'code' => 'MON-OLED-4K',
                'row' => 0, 'column' => 1, 'level' => 2,
            ]);

            Storage::create([
                'storage_system_id' => $rack2->id,
                'name' => 'Tastaturi Mecanice RGB',
                'code' => 'KEY-RGB-02',
                'row' => 1, 'column' => 0, 'level' => 1,
            ]);

            Storage::create([
                'storage_system_id' => $rack2->id,
                'name' => 'Componente Server S5',
                'code' => 'SRV-COMP-05',
                'row' => 4, 'column' => 2, 'level' => 2,
            ]);

            Storage::create([
                'storage_system_id' => $rack2->id,
                'name' => 'Sistem Backup UPS (Spate)',
                'code' => 'UPS-BACK-09',
                'row' => 9, 'column' => 3, 'level' => 3,
            ]);
        }

        $columns3 = 8;
        $rows3 = 1;
        $levels3 = 5;

        $rack3X = 0.0;
        $rack3Z = -14.0; 
        
        $sizeX3 = $columns3 * $slotWidth;
        $sizeZ3 = $rows3 * $slotDepth;
        $sizeY3 = $levels3 * $levelHeight;

        if (!StorageSystem::isSpaceOccupied($rack3X, $rack3Z, $sizeX3, $sizeZ3)) {
            $rack3 = StorageSystem::create([
                'name' => 'Raft Înalt Spate',
                'type' => 'rack',
                'pos_x' => $rack3X,
                'pos_y' => 0.0,
                'pos_z' => $rack3Z,
                'columns' => $columns3,
                'rows' => $rows3,
                'levels' => $levels3,
                'spacing' => 0.1,
                'size_x' => $sizeX3,
                'size_y' => $sizeY3,
                'size_z' => $sizeZ3,
                'orientation' => 0,
            ]);

            Storage::create([
                'storage_system_id' => $rack3->id,
                'name' => 'SSD NVMe 2TB',
                'code' => 'SSD-2TB-NVME',
                'row' => 0, 'column' => 2, 'level' => 4,
            ]);
        }
    }
}