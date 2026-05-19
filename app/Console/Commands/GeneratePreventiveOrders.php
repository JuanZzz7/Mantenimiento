<?php

namespace App\Console\Commands;

use App\Services\MaintenancePlanService;
use Illuminate\Console\Command;

class GeneratePreventiveOrders extends Command
{
    protected $signature   = 'maintenance:generate';
    protected $description = 'Genera órdenes de trabajo preventivas para planes vencidos';

    public function handle(MaintenancePlanService $service): int
    {
        $this->info('Verificando planes de mantenimiento pendientes...');
        $count = $service->generateDueOrders();

        if ($count === 0) {
            $this->line('No hay planes vencidos pendientes.');
        } else {
            $this->info("✔ Se generaron {$count} orden(es) de trabajo preventiva(s).");
        }

        return Command::SUCCESS;
    }
}
