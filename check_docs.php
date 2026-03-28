<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Doctor;
use App\Models\DoctorSchedule;

echo 'Total Doctors: ' . Doctor::count() . "\n";
foreach (Doctor::all() as $d) {
    echo $d->name . ': Start=' . ($d->availability_start ?: 'NONE') . ', End=' . ($d->availability_end ?: 'NONE') . ', Days=' . (is_array($d->available_days) ? implode(',', $d->available_days) : 'NONE') . ', Schedules=' . $d->schedules->count() . "\n";
}
