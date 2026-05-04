<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingZoomController;
use App\Http\Controllers\DataServerController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ItSupportJatengController;
use App\Http\Controllers\StockPerangkatController;
use App\Http\Controllers\PerangkatAplikasiController;
use App\Http\Controllers\DataJadwalController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\ItSupportMasterController;
use App\Http\Controllers\Admin\ZoomMasterController;
use App\Http\Controllers\Admin\PerangkatAplikasiMasterController;

// ── Auth ────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Protected Routes ────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/schedule', [DashboardController::class, 'storeSchedule'])->name('schedule.store');

    // Data Jadwal
    Route::middleware('permission:data-jadwal')->group(function () {
        Route::get('/data-jadwal', [DataJadwalController::class, 'index'])->name('data-jadwal.index');
        Route::get('/data-jadwal/create', [DataJadwalController::class, 'create'])->name('data-jadwal.create');
        Route::post('/data-jadwal', [DataJadwalController::class, 'store'])->name('data-jadwal.store');
        Route::get('/data-jadwal/export', [DataJadwalController::class, 'export'])->name('data-jadwal.export');
    });

    // Booking Zoom
    Route::middleware('permission:booking-zoom')->group(function () {
        Route::get('/booking-zoom', [BookingZoomController::class, 'index'])->name('booking-zoom.index');
        Route::get('/booking-zoom/create', [BookingZoomController::class, 'create'])->name('booking-zoom.create');
        Route::post('/booking-zoom', [BookingZoomController::class, 'store'])->name('booking-zoom.store');
        Route::delete('/booking-zoom/{id}', [BookingZoomController::class, 'destroy'])->name('booking-zoom.destroy');
        Route::get('/cek-ketersediaan-zoom', [BookingZoomController::class, 'cekKetersediaan'])->name('booking-zoom.ketersediaan');
        Route::get('/api/zoom-bookings', [BookingZoomController::class, 'apiBookings'])->name('api.zoom-bookings');
    });

    // Data Server
    Route::middleware('permission:data-server')->group(function () {
        Route::get('/data-server', [DataServerController::class, 'index'])->name('data-server.index');
        Route::get('/data-server/create', [DataServerController::class, 'create'])->name('data-server.create');
        Route::post('/data-server', [DataServerController::class, 'store'])->name('data-server.store');
        Route::get('/data-server/{id}', [DataServerController::class, 'show'])->name('data-server.show');
        Route::get('/data-server/{id}/edit', [DataServerController::class, 'edit'])->name('data-server.edit');
        Route::put('/data-server/{id}', [DataServerController::class, 'update'])->name('data-server.update');
        Route::delete('/data-server/{id}', [DataServerController::class, 'destroy'])->name('data-server.destroy');
        Route::post('/data-server/{id}/toggle-status', [DataServerController::class, 'toggleStatus'])->name('data-server.toggle-status');

        // Maintenance
        Route::get('/data-server/{serverId}/maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
        Route::post('/data-server/{serverId}/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::get('/maintenance/{id}/edit', [MaintenanceController::class, 'edit'])->name('maintenance.edit');
        Route::put('/maintenance/{id}', [MaintenanceController::class, 'update'])->name('maintenance.update');
    });

    // IT Support Jateng
    Route::middleware('permission:it-support-jateng')->group(function () {
        Route::get('/it-support-jateng', [ItSupportJatengController::class, 'index'])->name('it-support-jateng.index');
        Route::get('/it-support-jateng/create', [ItSupportJatengController::class, 'create'])->name('it-support-jateng.create');
        Route::post('/it-support-jateng', [ItSupportJatengController::class, 'store'])->name('it-support-jateng.store');
        Route::get('/it-support-jateng/{id}/edit', [ItSupportJatengController::class, 'edit'])->name('it-support-jateng.edit');
        Route::put('/it-support-jateng/{id}', [ItSupportJatengController::class, 'update'])->name('it-support-jateng.update');
        Route::delete('/it-support-jateng/{id}', [ItSupportJatengController::class, 'destroy'])->name('it-support-jateng.destroy');
    });

    // Stock Perangkat
    Route::middleware('permission:stock-perangkat')->group(function () {
        Route::get('/stock-perangkat', [StockPerangkatController::class, 'index'])->name('stock-perangkat.index');
        Route::get('/stock-perangkat/create', [StockPerangkatController::class, 'create'])->name('stock-perangkat.create');
        Route::post('/stock-perangkat', [StockPerangkatController::class, 'store'])->name('stock-perangkat.store');
        Route::get('/stock-perangkat/{id}/edit', [StockPerangkatController::class, 'edit'])->name('stock-perangkat.edit');
        Route::put('/stock-perangkat/{id}', [StockPerangkatController::class, 'update'])->name('stock-perangkat.update');
        Route::delete('/stock-perangkat/{id}', [StockPerangkatController::class, 'destroy'])->name('stock-perangkat.destroy');
    });

    // Perangkat Aplikasi
    Route::middleware('permission:perangkat-aplikasi')->group(function () {
        Route::get('/perangkat-aplikasi', [PerangkatAplikasiController::class, 'index'])->name('perangkat-aplikasi.index');
        Route::get('/perangkat-aplikasi/create', [PerangkatAplikasiController::class, 'create'])->name('perangkat-aplikasi.create');
        Route::post('/perangkat-aplikasi', [PerangkatAplikasiController::class, 'store'])->name('perangkat-aplikasi.store');
        Route::get('/perangkat-aplikasi/{id}/edit', [PerangkatAplikasiController::class, 'edit'])->name('perangkat-aplikasi.edit');
        Route::put('/perangkat-aplikasi/{id}', [PerangkatAplikasiController::class, 'update'])->name('perangkat-aplikasi.update');
        Route::delete('/perangkat-aplikasi/{id}', [PerangkatAplikasiController::class, 'destroy'])->name('perangkat-aplikasi.destroy');
        Route::get('/perangkat-aplikasi/export', [ExportController::class, 'perangkatAplikasi'])->name('perangkat-aplikasi.export');
    });

    // ── Admin Routes ────────────────────────────────────────
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{id}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
        Route::post('users/{id}/permissions', [UserController::class, 'syncPermissions'])->name('users.permissions');

        Route::resource('ruangan', RuanganController::class)->except(['show']);
        Route::post('ruangan/{id}/toggle', [RuanganController::class, 'toggleActive'])->name('ruangan.toggle');

        Route::resource('it-support', ItSupportMasterController::class)->except(['show']);
        Route::post('it-support/{id}/toggle', [ItSupportMasterController::class, 'toggleActive'])->name('it-support.toggle');

        Route::get('zoom', [ZoomMasterController::class, 'index'])->name('zoom.index');
        Route::post('zoom/unit', [ZoomMasterController::class, 'storeUnit'])->name('zoom.unit.store');
        Route::put('zoom/unit/{id}', [ZoomMasterController::class, 'updateUnit'])->name('zoom.unit.update');
        Route::delete('zoom/unit/{id}', [ZoomMasterController::class, 'destroyUnit'])->name('zoom.unit.destroy');
        Route::post('zoom/unit/{id}/toggle', [ZoomMasterController::class, 'toggleUnit'])->name('zoom.unit.toggle');
        Route::post('zoom/link', [ZoomMasterController::class, 'storeLink'])->name('zoom.link.store');
        Route::put('zoom/link/{id}', [ZoomMasterController::class, 'updateLink'])->name('zoom.link.update');
        Route::delete('zoom/link/{id}', [ZoomMasterController::class, 'destroyLink'])->name('zoom.link.destroy');
        Route::post('zoom/link/{id}/toggle', [ZoomMasterController::class, 'toggleLink'])->name('zoom.link.toggle');

        Route::resource('master-perangkat-aplikasi', PerangkatAplikasiMasterController::class)->except(['show']);
        Route::post('master-perangkat-aplikasi/{type}/{id}/toggle', [PerangkatAplikasiMasterController::class, 'toggle'])->name('master-perangkat-aplikasi.toggle');
    });
});
