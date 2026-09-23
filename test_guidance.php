<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
try { 
    $classes = \App\Models\SchoolClass::getSortedClasses(); 
    $query = \App\Models\Guidance::with(['student.schoolClass', 'schoolClass']); 
    $guidances = $query->orderBy('date', 'desc')->paginate(10)->withQueryString(); 
    $students = \App\Models\Student::orderBy('name')->get(); 
    $stats = [
        'total' => \App\Models\Guidance::count(), 
        'apresiasi' => \App\Models\Guidance::where('type', 'Apresiasi')->count(), 
        'konseling' => \App\Models\Guidance::where('type', 'Konseling')->count(), 
        'pelanggaran' => \App\Models\Guidance::where('type', 'Pelanggaran')->count()
    ]; 
    echo 'OK'; 
} catch (\Exception $e) { 
    echo $e->getMessage(); 
}
