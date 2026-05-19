<?php

namespace App\Console\Commands;

use App\Services\MaintenancePlanService;
use Illuminate\Console\Command;

class GeneratePreventiveWorkOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmms:generate-preventive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera órdenes de trabajo preventivas para los planes que ya vencieron sus fechas programadas.';

    /**
     * Execute the console command.
     */
    public function handle(MaintenancePlanService $service): int
    {
        $this->info('Iniciando generación de órdenes preventivas...');

        try {
            $count = $service->generateDueOrders();
            $this->info("Hecho! Se han generado {$count} nuevas órdenes de trabajo.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error al generar órdenes: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
