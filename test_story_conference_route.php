<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use App\Models\StoryConference;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "Testing Story Conference Route Generation\n";
echo "=========================================\n\n";

// Test 1: Check if StoryConference model has correct primary key
echo "Test 1: StoryConference Model Primary Key\n";
$storyConference = new StoryConference();
echo "Primary Key: " . $storyConference->getKeyName() . "\n";
echo "Table: " . $storyConference->getTable() . "\n\n";

// Test 2: Check if route exists
echo "Test 2: Route Existence\n";
try {
    $route = Route::getRoutes()->getByName('mahasiswa.story-conference.download');
    if ($route) {
        echo "Route found: " . $route->uri() . "\n";
        echo "Methods: " . implode(', ', $route->methods()) . "\n";
        echo "Parameters: " . implode(', ', $route->parameterNames()) . "\n";
    } else {
        echo "Route not found!\n";
    }
} catch (Exception $e) {
    echo "Error finding route: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 3: Try to generate URL with a mock StoryConference
echo "Test 3: URL Generation\n";
try {
    // Create a mock StoryConference with id_conference = 1
    $mockStoryConference = new StoryConference();
    $mockStoryConference->id_conference = 1;
    $mockStoryConference->exists = true; // Make it appear as if it exists

    $url = route('mahasiswa.story-conference.download', $mockStoryConference->id);
    echo "Generated URL: " . $url . "\n";
    echo "Success: URL generation works!\n";
} catch (Exception $e) {
    echo "Error generating URL: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 4: Check database for existing records
echo "Test 4: Database Records\n";
try {
    $count = StoryConference::count();
    echo "Total StoryConference records: " . $count . "\n";

    if ($count > 0) {
        $firstRecord = StoryConference::first();
        echo "First record ID: " . $firstRecord->id_conference . "\n";
        echo "First record status: " . $firstRecord->status . "\n";

        // Try URL generation with real data
        $url = route('mahasiswa.story-conference.download', $firstRecord->id);
        echo "URL with real data: " . $url . "\n";
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
echo "\n";

echo "Testing completed.\n";
