<?php
// scripts/simulate_submit_ujian.php
// Usage: php scripts/simulate_submit_ujian.php [user_id]
$uid = $argv[1] ?? 5;
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mahasiswa\UjianTAController;
use App\Models\User;
use Illuminate\Support\Facades\Log;

try {
    $user = User::find($uid);
    if (!$user) {
        echo "User $uid not found\n";
        exit(1);
    }

    Auth::loginUsingId($uid);
    echo "Logged in as user $uid\n";

    // create temp files for upload
    $tmpDir = sys_get_temp_dir();
    $suratPath = tempnam($tmpDir, 'surat_') . '.pdf';
    $transPath = tempnam($tmpDir, 'trans_') . '.pdf';
    file_put_contents($suratPath, "%PDF-1.4 Dummy PDF content\n");
    file_put_contents($transPath, "%PDF-1.4 Dummy PDF content\n");

    // create UploadedFile instances
    // Symfony UploadedFile constructor: (path, originalName, mimeType = null, size = null, error = null, test = false)
    // Provide explicit error code 0 and mark as test to allow isValid() in CLI context.
    $uploadedSurat = new \Symfony\Component\HttpFoundation\File\UploadedFile(
        $suratPath,
        basename($suratPath),
        'application/pdf',
        null,
        0,
        true
    );
    $uploadedTrans = new \Symfony\Component\HttpFoundation\File\UploadedFile(
        $transPath,
        basename($transPath),
        'application/pdf',
        null,
        0,
        true
    );

    // Build request
    $request = Request::create('/mahasiswa/ujian-ta', 'POST', [], [], [
        'file_surat_pengantar' => $uploadedSurat,
        'file_transkrip_nilai' => $uploadedTrans,
    ], [], null);

    // Attach Laravel session store and start it so controller's redirect/session logic works
    $laravelSession = null;
    try {
        $laravelSession = $app->make('session.store');
    } catch (Exception $e) {
        // fallback to array session store
        $laravelSession = new \Illuminate\Session\Store('cli', new \Illuminate\Session\ArraySessionHandler());
    }
    if (method_exists($laravelSession, 'start')) {
        $laravelSession->start();
    }
    $request->setLaravelSession($laravelSession);

    $controller = new UjianTAController();
    $response = $controller->store($request);

    echo "Controller response type: " . get_class($response) . "\n";
    if (method_exists($response, 'getStatusCode')) {
        echo "Status: " . $response->getStatusCode() . "\n";
    }

    // print session flash messages if any
    $session = $request->session();
    if ($session) {
        echo "Flash data:\n";
        print_r($session->all());
    }

    // cleanup temp files
    @unlink($suratPath);
    @unlink($transPath);

    echo "Done.\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
