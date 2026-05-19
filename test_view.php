<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
try {
    echo view('spares.index', [
        'spares' => App\Models\Spare::paginate(15), 
        'lowStockCount' => 0
    ])->render();
    echo "RENDER_SUCCESS";
} catch (\Throwable $e) {
    echo "VIEW_ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
