<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\WarehouseSeeder;

class InitWarehouseCommand extends Command
{
    protected $signature = 'warehouse:init';

    protected $description = 'Golește baza de date și generează un depozit gata populat geometric corect';

    public function handle()
    {
        $this->info('Se curăță depozitul și se aplică regulile de spațiere...');

        $this->call(WarehouseSeeder::class);

        $this->info('Succes! Depozitul a fost inițializat. Verifică browserul!');
        return Command::SUCCESS;
    }
}