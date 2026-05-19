<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $user = \App\Models\User::find(1);
    echo "User: " . $user->name . " | Role: " . $user->role . PHP_EOL;
    
    $notifs = $user->unreadNotifications->take(5);
    echo "Notifications OK: " . $notifs->count() . PHP_EOL;
    
    $spares = \App\Models\Spare::query()->paginate(16);
    echo "Spares paginate OK: " . $spares->count() . PHP_EOL;
    
    $lowStock = \App\Models\Spare::whereColumn('stock','<=','stock_min')->count();
    echo "LowStock OK: " . $lowStock . PHP_EOL;
    
    $categories = \App\Models\Spare::select('category')->distinct()->whereNotNull('category')->orderBy('category')->pluck('category');
    echo "Categories OK: " . $categories->count() . PHP_EOL;
    
    $stats = [
        'total'     => \App\Models\Spare::count(),
        'value'     => \App\Models\Spare::selectRaw('COALESCE(SUM(stock * price), 0) as v')->value('v'),
        'low_stock' => $lowStock,
        'out_stock' => \App\Models\Spare::where('stock', 0)->count(),
    ];
    echo "Stats OK: total=" . $stats['total'] . " value=" . $stats['value'] . PHP_EOL;
    
    echo PHP_EOL . "=== TODAS LAS QUERIES PASARON SIN ERROR ===" . PHP_EOL;
    
} catch (\Throwable $e) {
    echo PHP_EOL . "=== ERROR ENCONTRADO ===" . PHP_EOL;
    echo "Tipo: " . get_class($e) . PHP_EOL;
    echo "Mensaje: " . $e->getMessage() . PHP_EOL;
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
}
