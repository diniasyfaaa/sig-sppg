<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "1. Testing DB connection...\n";
$start = microtime(true);
$pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
echo "   DB connected in " . round(microtime(true) - $start, 3) . "s\n";

echo "2. Testing Desa::count()...\n";
$start = microtime(true);
$c = \App\Models\Desa::count();
echo "   Desa count: $c in " . round(microtime(true) - $start, 3) . "s\n";

echo "3. Testing DashboardController::userDashboard()...\n";
$start = microtime(true);
$controller = new \App\Http\Controllers\DashboardController();
$view = $controller->userDashboard();
echo "   Controller executed in " . round(microtime(true) - $start, 3) . "s\n";

echo "4. Testing View render...\n";
$start = microtime(true);
$rendered = $view->render();
echo "   View rendered in " . round(microtime(true) - $start, 3) . "s, length: " . strlen($rendered) . "\n";
