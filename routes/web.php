<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SummaryController;
use App\Http\Controllers\Auditor\DashboardController as AuditorDashboard;
use App\Http\Controllers\Auditor\GenbaController;
use App\Exports\PicaExport;
use Maatwebsite\Excel\Facades\Excel;



require __DIR__.'/auth.php';

// Captcha - taruh di sini, di luar semua group
Route::get('/captcha', [\App\Http\Controllers\CaptchaController::class, 'generate'])->name('captcha');

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('auditor.dashboard');
    }
    return redirect()->route('login');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/summary', [SummaryController::class, 'index'])->name('summary');
    Route::resource('dealers', DealerController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('questions', QuestionController::class);
    Route::resource('users', UserController::class);
    Route::get('/rekap', [\App\Http\Controllers\Admin\RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/{session}', [\App\Http\Controllers\Admin\RekapController::class, 'show'])->name('rekap.show');
    // Tambahkan ini
    Route::get('/genba', [\App\Http\Controllers\Admin\GenbaController::class, 'index'])->name('genba.index');
    Route::get('/genba/{session}', [\App\Http\Controllers\Admin\GenbaController::class, 'show'])->name('genba.show');
    Route::get('pica/export', [\App\Http\Controllers\Admin\PicaController::class, 'export'])->name('pica.export');
    Route::get('pica', [\App\Http\Controllers\Admin\PicaController::class, 'index'])->name('pica.index');
    Route::get('pica/{session}', [\App\Http\Controllers\Admin\PicaController::class, 'show'])->name('pica.show');
    
    // Di dalam group admin
    Route::get('schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('schedules/{schedule}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');
    
    //evidence
    Route::get('evidence', [\App\Http\Controllers\Admin\EvidenceController::class, 'index'])->name('evidence.index');
    Route::post('evidence/{dealer}/{tanggal}/upload-free', [\App\Http\Controllers\Admin\EvidenceController::class, 'uploadFree'])->name('evidence.upload-free');
    Route::get('evidence/{dealer}/{tanggal}', [\App\Http\Controllers\Admin\EvidenceController::class, 'show'])->name('evidence.show');
    Route::post('evidence/{dealer}/{tanggal}/upload', [\App\Http\Controllers\Admin\EvidenceController::class, 'upload'])->name('evidence.upload');
    Route::delete('evidence/{evidence}', [\App\Http\Controllers\Admin\EvidenceController::class, 'destroy'])->name('evidence.destroy');
    Route::get('summary/export-pdf/{dealerId}', [SummaryController::class, 'exportPdf'])
    ->name('summary.export-pdf');
});

// Auditor Routes
Route::prefix('auditor')->name('auditor.')->middleware(['auth', 'auditor'])->group(function () {
    Route::get('/dashboard', [AuditorDashboard::class, 'index'])->name('dashboard');
    Route::get('/genba/create', [GenbaController::class, 'create'])->name('genba.create');
    Route::post('/genba', [GenbaController::class, 'store'])->name('genba.store');
    Route::get('/genba/{session}/fill', [GenbaController::class, 'fill'])->name('genba.fill');
    Route::post('/genba/{session}/submit', [GenbaController::class, 'submit'])->name('genba.submit');
    Route::get('/genba/{session}/result', [GenbaController::class, 'result'])->name('genba.result');
    Route::get('/genba', [GenbaController::class, 'index'])->name('genba.index');
    Route::get('pica', [\App\Http\Controllers\Auditor\PicaController::class, 'index'])->name('pica.index');
    Route::get('pica/{session}', [\App\Http\Controllers\Auditor\PicaController::class, 'show'])->name('pica.show');
    Route::get('pica/{session}/edit/{pica}', [\App\Http\Controllers\Auditor\PicaController::class, 'edit'])->name('pica.edit');
    Route::put('pica/{pica}', [\App\Http\Controllers\Auditor\PicaController::class, 'update'])->name('pica.update');
    Route::post('pica', [\App\Http\Controllers\Auditor\PicaController::class, 'store'])->name('pica.store');
    Route::get('pica/create', [\App\Http\Controllers\Auditor\PicaController::class, 'create'])->name('pica.create');
    Route::delete('pica/{pica}', [\App\Http\Controllers\Auditor\PicaController::class, 'destroy'])->name('pica.destroy');

});

// Kacab Routes
Route::prefix('kacab')->name('kacab.')->middleware(['auth', 'kacab'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Kacab\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/rekap', [\App\Http\Controllers\Kacab\RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/{session}', [\App\Http\Controllers\Kacab\RekapController::class, 'show'])->name('rekap.show');
    Route::get('/summary', [\App\Http\Controllers\Kacab\SummaryController::class, 'index'])->name('summary');
    Route::get('/pica', [\App\Http\Controllers\Kacab\PicaController::class, 'index'])->name('pica.index');
    Route::get('/pica/{session}', [\App\Http\Controllers\Kacab\PicaController::class, 'show'])->name('pica.show');
});