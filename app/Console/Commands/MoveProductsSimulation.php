<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Storage;
use App\Models\StorageSystem;

class MoveProductsSimulation extends Command
{
    protected $signature = 'warehouse:simulate-movement {--delay=4 : Secunde între mutări}';
    
    protected $description = 'Simulează mișcarea automată a produselor în depozit în timp real (inclusiv între rafturi)';

    public function handle()
    {
        $this->info("=== Pornit simularea mișcărilor INTER-RAFTURI în depozit ===");
        $this->info("Apasă CTRL+C pentru a opri serviciul.");
        
        $delay = (int) $this->option('delay');

        while (true) {
            $product = Storage::inRandomOrder()->first();

            if ($product) {
                $currentRack = StorageSystem::find($product->storage_system_id);
                
                $targetRack = StorageSystem::inRandomOrder()->first();

                if ($targetRack && $currentRack) {
                    $newRow = rand(0, $targetRack->rows - 1);
                    $newColumn = rand(0, $targetRack->columns - 1);
                    $newLevel = rand(1, $targetRack->levels);

                    $isOccupied = Storage::where('storage_system_id', $targetRack->id)
                        ->where('row', $newRow)
                        ->where('column', $newColumn)
                        ->where('level', $newLevel)
                        ->where('id', '!=', $product->id)
                        ->exists();

                    if (!$isOccupied) {

                        $oldInfo = "'{$currentRack->name}' (R:{$product->row} C:{$product->column} L:{$product->level})";
                        

                        $product->update([
                            'storage_system_id' => $targetRack->id,
                            'row' => $newRow,
                            'column' => $newColumn,
                            'level' => $newLevel
                        ]);


                        $this->line("[" . now()->format('H:i:s') . "] Mutat '{$product->name}' de la {$oldInfo} la raftul '{$targetRack->name}' (R:{$newRow} C:{$newColumn} L:{$newLevel})");
                    }
                }
            }

            sleep($delay);
        }
    }
}