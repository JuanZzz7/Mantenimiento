<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Asset;
use App\Models\MaintenancePlan;
use App\Models\WorkOrder;
use App\Models\Spare;
use App\Models\WorkOrderSpare;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuarios ──────────────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Administrador CMMS',
            'email'    => 'admin@cmms.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '+1-555-0100',
            'active'   => true,
        ]);

        $tecnicos = collect([
            ['name' => 'Carlos Mendoza',   'email' => 'carlos@cmms.com'],
            ['name' => 'María García',     'email' => 'maria@cmms.com'],
            ['name' => 'Roberto Sánchez',  'email' => 'roberto@cmms.com'],
        ])->map(fn($t) => User::create([
            ...$t,
            'password' => Hash::make('password'),
            'role'     => 'tecnico',
            'phone'    => '+1-555-01' . rand(10, 99),
            'active'   => true,
        ]));

        // ── Activos ───────────────────────────────────────────────────────────
        $assetsData = [
            ['code' => 'ACT-001', 'name' => 'Bomba Centrífuga #1',      'location' => 'Planta A — Nivel 1', 'status' => 'activo',          'brand' => 'Grundfos',   'model' => 'CM5-5',  'category' => 'Bombas'],
            ['code' => 'ACT-002', 'name' => 'Compresor de Aire #1',     'location' => 'Sala de Máquinas',   'status' => 'activo',          'brand' => 'Atlas Copco','model' => 'GA15',   'category' => 'Compresores'],
            ['code' => 'ACT-003', 'name' => 'Generador Eléctrico',      'location' => 'Subestación',         'status' => 'activo',          'brand' => 'Caterpillar','model' => 'DG150',  'category' => 'Eléctrico'],
            ['code' => 'ACT-004', 'name' => 'Cinta Transportadora #1',  'location' => 'Línea de Producción', 'status' => 'en_mantenimiento','brand' => 'Fenner',     'model' => 'CT-50',  'category' => 'Transporte'],
            ['code' => 'ACT-005', 'name' => 'Torre de Enfriamiento',    'location' => 'Patio Norte',         'status' => 'activo',          'brand' => 'SPX Cooling','model' => 'TC-200', 'category' => 'HVAC'],
            ['code' => 'ACT-006', 'name' => 'Caldero Industrial #1',    'location' => 'Sala de Calderos',    'status' => 'activo',          'brand' => 'Cleaver',    'model' => 'CBLE-50','category' => 'Calderos'],
            ['code' => 'ACT-007', 'name' => 'Bomba Centrífuga #2',      'location' => 'Planta B — Nivel 1', 'status' => 'activo',          'brand' => 'Grundfos',   'model' => 'CM10-5', 'category' => 'Bombas'],
            ['code' => 'ACT-008', 'name' => 'Puente Grúa 5 ton',        'location' => 'Almacén Principal',  'status' => 'activo',          'brand' => 'Konecranes', 'model' => 'CLX5',   'category' => 'Izaje'],
            ['code' => 'ACT-009', 'name' => 'Compresor de Aire #2',     'location' => 'Sala de Máquinas',   'status' => 'inactivo',        'brand' => 'Ingersoll',  'model' => 'UP6-15', 'category' => 'Compresores'],
            ['code' => 'ACT-010', 'name' => 'Sistema HVAC Oficinas',    'location' => 'Edificio Admin.',     'status' => 'activo',          'brand' => 'Carrier',    'model' => 'AHU-40', 'category' => 'HVAC'],
        ];

        $assets = collect($assetsData)->map(fn($a) => Asset::create([
            ...$a,
            'acquisition_date' => now()->subMonths(rand(6, 60))->format('Y-m-d'),
            'serial_number'    => 'SN-' . strtoupper(substr(md5(rand()), 0, 8)),
            'description'      => "Equipo industrial {$a['category']} en ubicación {$a['location']}.",
        ]));

        // ── Planes de Mantenimiento ────────────────────────────────────────────
        $plans = [
            ['asset_idx'=>0,'name'=>'Cambio de aceite y filtros',         'freq'=>'mensual'],
            ['asset_idx'=>1,'name'=>'Revisión de filtros de aire',         'freq'=>'semanal'],
            ['asset_idx'=>2,'name'=>'Prueba de carga del generador',       'freq'=>'mensual'],
            ['asset_idx'=>4,'name'=>'Limpieza y revisión torre enfriamiento','freq'=>'trimestral'],
            ['asset_idx'=>5,'name'=>'Revisión de quemadores y válvulas',   'freq'=>'mensual'],
            ['asset_idx'=>7,'name'=>'Inspección de cables y poleas',        'freq'=>'trimestral'],
            ['asset_idx'=>0,'name'=>'Revisión de sellos y rodamientos',     'freq'=>'trimestral'],
            ['asset_idx'=>9,'name'=>'Cambio de filtros HVAC',               'freq'=>'mensual'],
        ];

        $createdPlans = collect($plans)->map(fn($p) => MaintenancePlan::create([
            'asset_id'    => $assets[$p['asset_idx']]->id,
            'name'        => $p['name'],
            'description' => "Mantenimiento preventivo programado: {$p['name']}",
            'frequency'   => $p['freq'],
            'next_run_at' => now()->addDays(rand(-5, 30)),
            'active'      => true,
        ]));

        // ── Repuestos ─────────────────────────────────────────────────────────
        $sparesData = [
            ['code'=>'REP-001','name'=>'Filtro de aceite industrial',  'unit'=>'unidad','stock'=>25,'stock_min'=>5, 'price'=>12.50],
            ['code'=>'REP-002','name'=>'Filtro de aire comprimido',    'unit'=>'unidad','stock'=>18,'stock_min'=>6, 'price'=>8.75],
            ['code'=>'REP-003','name'=>'Rodamiento SKF 6205',          'unit'=>'unidad','stock'=>3, 'stock_min'=>5, 'price'=>15.00],
            ['code'=>'REP-004','name'=>'Correa trapezoidal B-68',      'unit'=>'unidad','stock'=>8, 'stock_min'=>4, 'price'=>6.20],
            ['code'=>'REP-005','name'=>'Aceite hidráulico ISO 46 (lt)','unit'=>'litro', 'stock'=>50,'stock_min'=>10,'price'=>3.80],
            ['code'=>'REP-006','name'=>'Sello mecánico bomba 50mm',    'unit'=>'unidad','stock'=>2, 'stock_min'=>3, 'price'=>45.00],
            ['code'=>'REP-007','name'=>'Fusible 32A industrial',       'unit'=>'unidad','stock'=>30,'stock_min'=>10,'price'=>1.50],
            ['code'=>'REP-008','name'=>'Grasa Lithium EP2 (kg)',       'unit'=>'kg',    'stock'=>12,'stock_min'=>3, 'price'=>4.20],
            ['code'=>'REP-009','name'=>'Válvula solenoide 1/2"',       'unit'=>'unidad','stock'=>4, 'stock_min'=>2, 'price'=>28.00],
            ['code'=>'REP-010','name'=>'Manguera hidráulica 1/4" (m)', 'unit'=>'metro', 'stock'=>20,'stock_min'=>5, 'price'=>5.50],
        ];

        $spares = collect($sparesData)->map(fn($s) => Spare::create([
            ...$s,
            'supplier' => collect(['TechParts SA', 'Industrial Supply', 'MRO Solutions', 'GlobalParts'])->random(),
            'location' => 'Estante ' . chr(rand(65,68)) . '-' . rand(1,5),
        ]));

        // ── Órdenes de Trabajo ────────────────────────────────────────────────
        $statuses   = ['pendiente', 'pendiente', 'en_proceso', 'completada', 'completada', 'completada', 'cancelada'];
        $priorities = ['baja', 'media', 'media', 'alta', 'critica'];
        $types      = ['correctiva', 'correctiva', 'preventiva'];

        $descriptions = [
            'Falla en rodamiento con ruido excesivo. Requiere inspección urgente.',
            'Revisión preventiva programada según plan de mantenimiento.',
            'Fuga de aceite detectada en sello mecánico. Requiere reemplazo.',
            'Termostato fuera de rango. Temperatura elevada en operación.',
            'Vibración anormal detectada durante arranque del equipo.',
            'Cable de alimentación dañado. Riesgo eléctrico.',
            'Mantenimiento preventivo mensual incluyendo lubricación.',
            'Limpieza de filtros y revisión general del sistema.',
        ];

        $allTecnicos = $tecnicos->all();

        for ($i = 0; $i < 40; $i++) {
            $status    = $statuses[array_rand($statuses)];
            $asset     = $assets->random();
            $tecnico   = $allTecnicos[array_rand($allTecnicos)];
            $createdAt = now()->subDays(rand(0, 120));

            $order = WorkOrder::create([
                'asset_id'       => $asset->id,
                'type'           => $types[array_rand($types)],
                'priority'       => $priorities[array_rand($priorities)],
                'status'         => $status,
                'description'    => $descriptions[array_rand($descriptions)],
                'assigned_to'    => $tecnico->id,
                'created_by'     => $admin->id,
                'scheduled_date' => $createdAt->copy()->addDays(rand(1, 7)),
                'started_at'     => in_array($status, ['en_proceso','completada']) ? $createdAt->copy()->addHours(rand(1,24)) : null,
                'completed_at'   => $status === 'completada' ? $createdAt->copy()->addDays(rand(1,5)) : null,
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);

            // Agregar 0-2 repuestos a las órdenes completadas
            if ($status === 'completada' && rand(0,1)) {
                $spare = $spares->random();
                $qty   = rand(1, 3);
                WorkOrderSpare::create([
                    'work_order_id' => $order->id,
                    'spare_id'      => $spare->id,
                    'quantity'      => $qty,
                    'unit_price'    => $spare->price,
                ]);
            }
        }

        echo "✅ Seeder completado:\n";
        echo "   - 4 usuarios (1 admin + 3 técnicos)\n";
        echo "   - 10 activos industriales\n";
        echo "   - 8 planes de mantenimiento preventivo\n";
        echo "   - 10 repuestos en inventario\n";
        echo "   - 40 órdenes de trabajo con histórico\n";
        echo "\n   🔐 Admin: admin@cmms.com / password\n";
        echo "   🔧 Técnico: carlos@cmms.com / password\n";
    }
}
