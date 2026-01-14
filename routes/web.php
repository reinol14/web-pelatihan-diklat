<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PegawaiAuthController;

use App\Http\Controllers\DashboardController;


use App\Http\Controllers\Umum\Pelatihan\UmumPelatihanController;
use App\Http\Controllers\Umum\Pelatihan\PelatihanPendaftaranController;
use App\Http\Controllers\Umum\Pegawai\PegawaiSelfRegisterController;
use App\Http\Controllers\Umum\Pegawai\LaporanPelatihanController;


use App\Http\Controllers\Admin\Laporan\LaporanPelatihanApprovalController;
use App\Http\Controllers\Admin\Pegawai\PegawaiController;
use App\Http\Controllers\Admin\PelatihanSessionController;
use App\Http\Controllers\Admin\Pegawai\PegawaiProfileApprovalController;
use App\Http\Controllers\Admin\Pegawai\PegawaiRegistrationApprovalController;
use App\Http\Controllers\Admin\Pegawai\PelatihanVerifikasiController;
use App\Http\Controllers\Pegawai\ProfileChangeController;

use App\Http\Controllers\SuperAdmin\AdminUserController;
use App\Http\Controllers\Pegawai\PegawaiDashboardController;


// -----------------------------
// Public (Umum) Routes
// -----------------------------
Route::get('/', function () {
    return view('FrontPage.index');
})->name('frontpage.index');

// Pelatihan public menu
Route::get('/pelatihan', [UmumPelatihanController::class, 'index'])->name('Pelatihan.index');
Route::get('/pelatihan/{id}', [UmumPelatihanController::class, 'show'])->name('Pelatihan.show');
Route::post('/pelatihan/{id}/join', [UmumPelatihanController::class, 'join'])->name('pelatihan.join');


// -----------------------------
// ASN / Pegawai (User) Routes
// -----------------------------
// Login/Register pegawai
Route::get('/pegawai/login', [PegawaiAuthController::class, 'showLoginForm'])->name('Pegawai.login');
Route::post('/pegawai/login', [PegawaiAuthController::class, 'login'])->name('Pegawai.login.submit');
Route::post('/pegawai/logout', [PegawaiAuthController::class, 'logout'])->name('Pegawai.logout');
Route::get('/pegawai/register', [PegawaiSelfRegisterController::class, 'showForm'])->name('Pegawai.register');
Route::post('/pegawai/register', [PegawaiSelfRegisterController::class, 'submit'])->name('Pegawai.register.submit');

// Protected pegawai routes (requires auth:pegawais)
Route::middleware(['auth:pegawais'])->group(function () {
    Route::get('/pegawai/dashboard', [PegawaiDashboardController::class, 'index'])->name('Pegawai.dashboard');
    Route::get('/pegawai/profil', [PegawaiDashboardController::class, 'profil'])->name('Pegawai.profil');
    Route::get('/pegawai/profil/status', [PegawaiDashboardController::class, 'status'])->name('Pegawai.profil.status');
    // Profil pegawai
    Route::prefix('pegawai')->name('Pegawai.')->group(function () {
    Route::get('/profil/edit', [ProfileChangeController::class, 'edit'])->name('profil.edit');
    Route::post('/profil/store', [ProfileChangeController::class, 'store'])->name('profil.store');
    });

    //Pendaftaran Sesi Pelatihan
    Route::post('/pelatihan/{id}/join',  [PelatihanPendaftaranController::class, 'join'])->name('pelatihan.join');
    Route::post('/pelatihan/{id}/leave', [PelatihanPendaftaranController::class, 'leave'])->name('pelatihan.leave');

    Route::prefix('Pegawai')->name('Pegawai.Laporan.')->group(function () {
    // Laporan Pelatihan (menu pegawai, berdiri sendiri)
    Route::get('/laporan',            [LaporanPelatihanController::class, 'index'])->name('index');
    Route::get('/laporan/{id}/buat',  [LaporanPelatihanController::class, 'create'])->name('create');
    Route::post('/laporan/{id}',      [LaporanPelatihanController::class, 'store'])->name('store');
    Route::get('pegawai/laporan/{laporan}/edit', [LaporanPelatihanController::class, 'edit'])->name('edit');
Route::put('pegawai/laporan/{laporan}',      [LaporanPelatihanController::class, 'update'])->name('update');

});

});


// -----------------------------
// Admin Routes
// -----------------------------
// Admin login
Route::get('admin0', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('admin0', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Superadmin-only routes
Route::middleware([\App\Http\Middleware\SuperadminMiddleware::class])->group(function () {

    Route::prefix('superadmin/admins')->name('SuperAdmin.Admins.')->group(function () {
        Route::get('/',            [AdminUserController::class,'index'])->name('index');
        Route::get('/create',      [AdminUserController::class,'create'])->name('create');
        Route::post('/',           [AdminUserController::class,'store'])->name('store');
        Route::get('/{id}/edit',   [AdminUserController::class,'edit'])->name('edit');
        Route::put('/{id}',        [AdminUserController::class,'update'])->name('update');
        Route::delete('/{id}',     [AdminUserController::class,'destroy'])->name('destroy');
    });
    
    Route::get('admin/pegawai/check-duplicates', [PegawaiController::class, 'checkDuplicates'])->name('checkDuplicates');
    
    // Admin CRUD for Pelatihan sessions (hanya superadmin)
    Route::prefix('admin/pelatihan')->name('Admin.Pelatihan.')->group(function() {
        Route::get('/', [PelatihanSessionController::class, 'index'])->name('index');
        Route::get('/create', [PelatihanSessionController::class, 'create'])->name('create');
        Route::post('/store', [PelatihanSessionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PelatihanSessionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PelatihanSessionController::class, 'update'])->name('update');
        Route::delete('/{id}', [PelatihanSessionController::class, 'destroy'])->name('destroy');
    });
});

// Admin routes
Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('admin/pegawai/pegawaiapproval')->name('Admin.Pegawai.PegawaiApproval.')->middleware(['auth'])->group(function () {
        Route::get('/',             [PegawaiRegistrationApprovalController::class, 'index'])->name('index');
        Route::get('/{id}',         [PegawaiRegistrationApprovalController::class, 'show'])->name('show');
        Route::post('{id}/approve', [PegawaiRegistrationApprovalController::class, 'approve'])->name('approve');
        Route::post('{id}/reject',  [PegawaiRegistrationApprovalController::class, 'reject'])->name('reject');
    });
    Route::prefix('admin/pegawai')->name('Admin.Pegawai.')->group(function() {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('create');
        Route::post('/', [PegawaiController::class, 'store'])->name('store');
        Route::get('/{id}', [PegawaiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PegawaiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PegawaiController::class, 'update'])->name('update');
        Route::delete('/{id}', [PegawaiController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('admin')->name('Admin.')->middleware(['auth:web'])->group(function () {
        Route::get('/laporan', [LaporanPelatihanApprovalController::class, 'index'])
            ->name('Laporan.approval.index');

        Route::post('/laporan/{id}/approve', [LaporanPelatihanApprovalController::class, 'approve'])
            ->name('Laporan.approval.approve');

        Route::post('/laporan/{id}/reject', [LaporanPelatihanApprovalController::class, 'reject'])
            ->name('Laporan.approval.reject');
    });

    Route::prefix('admin')->middleware(['auth'])->name('Admin.')->group(function () {
        Route::get('/pelatihan/verifikasi', [PelatihanVerifikasiController::class, 'index'])->name('Pelatihan.verifikasi');

        // Single-approve / reject pakai composite key (pelatihan_id + nip)
        Route::post('/pelatihan/verifikasi/{pelatihan}/{nip}/approve', [PelatihanVerifikasiController::class, 'approve'])->whereNumber('pelatihan')
            ->where('nip', '\d+')
            ->name('Pelatihan.verifikasi.approve');

        Route::post('/pelatihan/verifikasi/{pelatihan}/{nip}/reject', [PelatihanVerifikasiController::class, 'reject'])->whereNumber('pelatihan')
            ->where('nip', '\d+')
            ->name('Pelatihan.verifikasi.reject');
        // Bulk action (approve/reject beberapa sekaligus)
        Route::post('/pelatihan/verifikasi/bulk', [PelatihanVerifikasiController::class, 'bulk'])
            ->name('Pelatihan.verifikasi.bulk');
    });


    Route::prefix('admin/pegawai-profile')->name('Admin.pegawai_profile.')->middleware(['auth'])->group(function () {
        Route::get('/',              [PegawaiProfileApprovalController::class, 'index'])->name('index');     // daftar semua perubahan yg menunggu approval
        Route::get('/{id}',          [PegawaiProfileApprovalController::class, 'show'])->name('show');       // detail perubahan profil
        Route::post('/{id}/approve', [PegawaiProfileApprovalController::class, 'approve'])->name('approve'); // setujui perubahan
        Route::post('/{id}/reject',  [PegawaiProfileApprovalController::class, 'reject'])->name('reject');   // tolak perubahan
    });
    

    
});
