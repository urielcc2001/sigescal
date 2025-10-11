<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Calidad\Solicitudes\RevisarSolicitudes;
use App\Livewire\Calidad\Solicitudes\VerSolicitud;
use App\Livewire\Calidad\Solicitudes\EstadoSolicitud;
use App\Livewire\Calidad\Solicitudes\SolicitudesAprovadas; 
use App\Livewire\Calidad\Solicitudes\SolicitudesRechazadas;
use App\Http\Controllers\SolicitudPdfController;
use App\Http\Controllers\ListaMaestraPdfController;

Route::get('/', \App\Livewire\Home::class)->name('home');

Route::get('/dashboard', \App\Livewire\Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function (): void {

    // Impersonations
    Route::post('/impersonate/{user}', [\App\Http\Controllers\ImpersonationController::class, 'store'])->name('impersonate.store')->middleware('can:impersonate');
    Route::delete('/impersonate/stop', [\App\Http\Controllers\ImpersonationController::class, 'destroy'])->name('impersonate.destroy');

    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', \App\Livewire\Settings\Profile::class)->name('settings.profile');
    Route::get('settings/password', \App\Livewire\Settings\Password::class)->name('settings.password');
    Route::get('settings/appearance', \App\Livewire\Settings\Appearance::class)->name('settings.appearance');
    Route::get('settings/locale', \App\Livewire\Settings\Locale::class)->name('settings.locale');

    // Admin
    Route::prefix('admin')->as('admin.')->group(function (): void {
        Route::get('/', \App\Livewire\Admin\Index::class)->middleware(['auth', 'verified'])->name('index')->middleware('can:access dashboard');
        Route::get('/users', \App\Livewire\Admin\Users::class)->name('users.index')->middleware('can:view users');
        Route::get('/users/create', \App\Livewire\Admin\Users\CreateUser::class)->name('users.create')->middleware('can:create users');
        Route::get('/users/{user}', \App\Livewire\Admin\Users\ViewUser::class)->name('users.show')->middleware('can:view users');
        Route::get('/users/{user}/edit', \App\Livewire\Admin\Users\EditUser::class)->name('users.edit')->middleware('can:update users');
        Route::get('/roles', \App\Livewire\Admin\Roles::class)->name('roles.index')->middleware('can:view roles');
        Route::get('/roles/create', \App\Livewire\Admin\Roles\CreateRole::class)->name('roles.create')->middleware('can:create roles');
        Route::get('/roles/{role}/edit', \App\Livewire\Admin\Roles\EditRole::class)->name('roles.edit')->middleware('can:update roles');
        Route::get('/permissions', \App\Livewire\Admin\Permissions::class)->name('permissions.index')->middleware('can:view permissions');
        Route::get('/permissions/create', \App\Livewire\Admin\Permissions\CreatePermission::class)->name('permissions.create')->middleware('can:create permissions');
        Route::get('/permissions/{permission}/edit', \App\Livewire\Admin\Permissions\EditPermission::class)->name('permissions.edit')->middleware('can:update permissions');
    });

    //pdf´s
    Route::get('calidad/solicitudes/estado/{solicitud}/formato.pdf', [SolicitudPdfController::class, 'download'])
        ->whereNumber('solicitud')
        ->name('calidad.solicitudes.estado.formato.pdf');

    Route::get('calidad/lista-maestra/pdf', [ListaMaestraPdfController::class, 'download'])
        ->name('calidad.lista-maestra.pdf');

    // Solicitudes 
    Route::get('calidad/solicitudes/crear', \App\Livewire\Calidad\Solicitudes\CrearSolicitud::class)->name('calidad.solicitudes.crear');
    
    Route::get('calidad/solicitudes/estado', EstadoSolicitud::class)->name('calidad.solicitudes.estado');

    Route::get('calidad/solicitudes/estado/{solicitud}', SolicitudesAprovadas::class)
        ->whereNumber('solicitud')
        ->name('calidad.solicitudes.estado.show');    
    
    // Solicitudes revision 
    Route::get('calidad/solicitudes/revisar', RevisarSolicitudes::class)->name('calidad.solicitudes.revisar');    
    
    Route::get('calidad/solicitudes/revisar/{solicitud}', VerSolicitud::class)
        ->whereNumber('solicitud')
        ->name('calidad.solicitudes.revisar.show');
    //Route::get('/calidad/solicitudes/{solicitud}', VerSolicitud::class)->name('calidad.solicitudes.show');
    
    Route::get('calidad/solicitudes/estado/{solicitud}/editar', SolicitudesRechazadas::class)
        ->whereNumber('solicitud')
        ->name('calidad.solicitudes.estado.edit');


    //Lista Maestra 
    Route::get('calidad/lista-maestra', \App\Livewire\Calidad\ListaMaestra\ListaMaestra::class)->name('calidad.lista-maestra.index');

});

require __DIR__.'/auth.php';
