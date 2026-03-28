<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "--- Users ---\n";
foreach (User::all() as $u) {
    echo $u->email . " | Role: " . $u->role . "\n";
}
