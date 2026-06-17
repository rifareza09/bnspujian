<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$u = \App\Models\User::where('email', 'admin@pmb.test')->first();
if (!$u) {
    echo "User not found!\n";
    exit(1);
}
echo "email: " . $u->email . "\n";
echo "role: " . $u->role . "\n";
echo "pass_ok: " . (password_verify('admin123', $u->password) ? 'YES' : 'NO') . "\n";
echo "total provinsi: " . \App\Models\Provinsi::count() . "\n";
echo "total kabupaten: " . \App\Models\Kabupaten::count() . "\n";
