<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Mahasiswa;
use App\Models\Dosen;

echo "Testing Registration Logic\n";
echo "========================\n\n";

// Test role determination
$testEmails = [
    '12345678@student.isi.ac.id' => 'mahasiswa',
    'dosen1@lecturer.isi.ac.id' => 'dospem',
    'kaprodi1@kaprodi.isi.ac.id' => 'kaprodi',
    'koor1@koordinator.isi.ac.id' => 'koordinator_ta',
    'penguji1@penguji.isi.ac.id' => 'dosen_penguji',
    'admin@admin.com' => 'admin'
];

// Helper to call private method
class TestController extends App\Http\Controllers\Auth\RegisterController {
    public function callPrivateMethod($method, ...$args) {
        $ref = new \ReflectionObject($this);
        if (! $ref->hasMethod($method)) {
            throw new \BadMethodCallException("Method $method does not exist on controller");
        }
        $m = $ref->getMethod($method);
        $m->setAccessible(true);
        return $m->invokeArgs($this, $args);
    }
}

$controller = new TestController();

foreach ($testEmails as $email => $expectedRole) {
    $role = $controller->callPrivateMethod('determineRoleFromEmail', $email);
    echo "Email: $email -> Role: $role (Expected: $expectedRole) - " . ($role === $expectedRole ? 'PASS' : 'FAIL') . "\n";
}

echo "\nChecking if roles exist in database:\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "- {$role->name}: {$role->display_name}\n";
}

echo "\nTest completed.\n";

