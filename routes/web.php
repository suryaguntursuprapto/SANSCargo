<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DetailPengirimanController;
use App\Http\Controllers\PengirimanImportController;
use App\Http\Controllers\KalkulatorPengirimanController;
use App\Http\Controllers\OpsiPengirimanController;
use App\Http\Controllers\PengirimPenerimaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\DikirimController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/
Route::get('/test-translation', function () {
    return view('test');
})->name('test.translation');

// Make sure your language route is also properly defined:
Route::get('/set-language/{locale}', [App\Http\Controllers\LanguageController::class, 'setLanguage'])
    ->name('set.language');
// Dashboard route
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])
    ->name('landing.index');

// Utility route
Route::get('/kembali', function() {
    return redirect()->back();
})->name('kembali');

// Authentication Routes
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->intended('/pengiriman');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/pengiriman');
    }
    
    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
})->name('login.process');

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Registration Routes
Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->intended('login');
    }
    return view('auth.register');
})->name('register');

// Customer Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


Route::post('/register', function (Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'nama_lengkap' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'nomor_telepon' => ['required', 'string', 'max:20'],
        'alamat' => ['required', 'string'],
    ]);
    
    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'nama_lengkap' => $validated['nama_lengkap'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'nomor_telepon' => $validated['nomor_telepon'],
        'alamat' => $validated['alamat'],
        'branch' => 'CCM Cargo Yogyakarta', // Default branch
        'status' => 'Customer', // Default status
    ]);
    
    Auth::login($user);
    
    return redirect()->route('login');
})->name('register.process');

/*
|--------------------------------------------------------------------------
| Pengiriman Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
Route::group(['prefix' => 'pengiriman', 'as' => 'pengiriman.'], function (){

    // Opsi Pengiriman & Layanan
    Route::get('/opsi/{id?}', [OpsiPengirimanController::class, 'create'])->name('opsi.create');
    Route::post('/opsi', [OpsiPengirimanController::class, 'store'])->name('opsi.store');

    // Pengirim & Penerima
    Route::get('/pengirim-penerima/{id}', [PengirimPenerimaController::class, 'create'])->name('pengirim-penerima.create');
    Route::post('/pengirim-penerima', [PengirimPenerimaController::class, 'store'])->name('pengirim-penerima.store');

    // Detail Pengiriman (updated to match our flow)
    Route::get('/detail/{id}', [DetailPengirimanController::class, 'create'])->name('detail.create');
    Route::post('/detail', [DetailPengirimanController::class, 'store'])->name('detail.store');

    // Informasi Pembayaran
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');

    // Catatan & Finalization
    Route::get('/catatan/{id}', [CatatanController::class, 'create'])->name('catatan.create');
    Route::post('/catatan', [CatatanController::class, 'store'])->name('catatan.store');
    Route::post('/request', [CatatanController::class, 'request'])->name('request');
    Route::post('/cancel', [CatatanController::class, 'cancel'])->name('cancel');
    Route::get('/review/{id}', [CatatanController::class, 'review'])->name('review');
    Route::get('/success/{id}', [CatatanController::class, 'success'])->name('success');

    // Resource routes
    Route::get('/', [DetailPengirimanController::class, 'index'])->name('index');
    Route::get('/export', [DetailPengirimanController::class, 'export'])->name('export');
    Route::get('/{pengiriman}/edit', [DetailPengirimanController::class, 'edit'])->name('edit');
    Route::put('/{pengiriman}', [DetailPengirimanController::class, 'update'])->name('update');
    Route::delete('/{pengiriman}', [DetailPengirimanController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [DetailPengirimanController::class, 'show'])->name('show');
    Route::get('/{id}/print-label', [DetailPengirimanController::class, 'printLabel'])->name('print.label');
    Route::get('/track', [DetailPengirimanController::class, 'track'])->name('track');
    Route::get('/track/{resi}', [DetailPengirimanController::class, 'trackDirect'])->name('track.direct');
    
    // Custom pengiriman routes
    Route::get('/detail', [DetailPengirimanController::class, 'createdetailpengiriman'])->name('buat');
    Route::post('/', [DetailPengirimanController::class, 'store'])->name('store');
    Route::post('/simpan-draft', [DetailPengirimanController::class, 'simpanDraft'])->name('simpan-draft');
    Route::post('/hapus-item', [DetailPengirimanController::class, 'hapusItem'])->name('hapus-item'); 
});

 /*
    |--------------------------------------------------------------------------
    | Pengiriman Import Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'import', 'as' => 'import.'], function () {
        Route::get('/', [PengirimanImportController::class, 'index'])->name('index');
        Route::post('/', [PengirimanImportController::class, 'store'])->name('store');
        Route::get('/download-template', [PengirimanImportController::class, 'downloadTemplate'])->name('download-template');
        Route::get('/{id}', [PengirimanImportController::class, 'show'])->name('show');
        Route::get('/{id}/draft', [PengirimanImportController::class, 'draft'])->name('draft');
        Route::post('/{id}/process', [PengirimanImportController::class, 'process'])->name('process');
        Route::delete('/{id}', [PengirimanImportController::class, 'cancel'])->name('cancel');
        Route::get('/{id}/status', [PengirimanImportController::class, 'checkStatus'])->name('check-status');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
    
    // Branch Management
    Route::resource('branches', App\Http\Controllers\Admin\BranchController::class);
    
    // User Management  
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Ongkir Management
    Route::resource('ongkir', App\Http\Controllers\Admin\OngkirController::class);
    // Tambahkan route untuk AJAX calculator
    Route::post('/ongkir/calculate', [App\Http\Controllers\Admin\OngkirController::class, 'calculateOngkir'])
        ->name('ongkir.calculate');
    
    // Pengiriman Management (Admin view)
    Route::get('/pengiriman', [App\Http\Controllers\Admin\PengirimanController::class, 'index'])
        ->name('pengiriman.index');
    Route::get('/pengiriman/{id}', [App\Http\Controllers\Admin\PengirimanController::class, 'show'])
        ->name('pengiriman.show');
    Route::patch('/pengiriman/{id}/status', [App\Http\Controllers\Admin\PengirimanController::class, 'updateStatus'])
        ->name('pengiriman.update-status');
    Route::delete('/pengiriman/{id}', [App\Http\Controllers\Admin\PengirimanController::class, 'destroy'])
        ->name('pengiriman.destroy');
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])
        ->name('reports.index');
    Route::get('/reports/users', [App\Http\Controllers\Admin\ReportController::class, 'users'])
        ->name('reports.users');
    Route::get('/reports/pengiriman', [App\Http\Controllers\Admin\ReportController::class, 'pengiriman'])
        ->name('reports.pengiriman');
    Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])
        ->name('reports.export');
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])
        ->name('settings.update');
});


    /*
    |--------------------------------------------------------------------------
    | Cek Ongkir Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'cek-ongkir', 'as' => 'kalkulator.'], function () {
          // Kalkulator Pengiriman routes
        Route::get('/', [KalkulatorPengirimanController::class, 'index'])
        ->name('index');
        Route::post('/hitung', [KalkulatorPengirimanController::class, 'hitungOngkir'])
        ->name('hitung');
    });

    // Profil routes
    Route::get('/pengaturan/profil', [ProfileController::class, 'show'])
    ->name('pengaturan.profile');

    Route::get('/pengaturan/profil/edit', [ProfileController::class, 'edit'])
    ->name('pengaturan.edit');

    Route::post('/pengaturan/profil/update', [ProfileController::class, 'update'])
    ->name('pengaturan.update');

    Route::post('/pengaturan/profil/update-image', [ProfileController::class, 'updateProfileImage'])
    ->name('pengaturan.update.image');

    //dikirim
    Route::get('/kirim', [DikirimController::class, 'dikirim'])->name('pengiriman.dikirim');
    Route::get('/kirim/export', [DikirimController::class, 'export'])->name('pengiriman.export');


    // Password routes
    Route::get('/pengaturan/password', [PasswordController::class, 'edit'])
    ->name('pengaturan.password.edit');
    Route::post('/pengaturan/password', [PasswordController::class, 'update'])
    ->name('pengaturan.password.update');

    // Language route
    Route::get('/set-language/{locale}', [LanguageController::class, 'setLanguage'])
    ->name('set.language');
});