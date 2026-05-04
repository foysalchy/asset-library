<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\Frontend\BookmarkController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriveUploadController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Frontend\FrontendAuthController;
use App\Http\Controllers\Frontend\NotificationController;
use App\Http\Controllers\Frontend\TicketController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\UserController;
use Google\Service\Storage;
use Illuminate\Support\Facades\Storage as FacadesStorage;

// dashboard pages

Route::get('/test-drive', function () {
    FacadesStorage::disk('google_drive')->write('test.txt', 'Hello from Laravel!');
    return 'Upload success!';
});

Route::middleware('guest')->group(function () {
    Route::get('/admin-login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin-login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin ────────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('admin')->group(function () {




    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware([
        'index'   => 'permission:dashboard.view',
    ]);

    Route::resource('projects', ProjectController::class)
        ->middleware([
            'index'   => 'permission:projects.view',
            'create'  => 'permission:projects.create',
            'store'   => 'permission:projects.create',
            'edit'    => 'permission:projects.edit',
            'update'  => 'permission:projects.edit',
            'destroy' => 'permission:projects.delete',
        ]);
    Route::resource('asset-types', AssetTypeController::class)
        ->middleware([
            'index'   => 'permission:asset-types.view',
            'create'  => 'permission:asset-types.create',
            'store'   => 'permission:asset-types.create',
            'edit'    => 'permission:asset-types.edit',
            'update'  => 'permission:asset-types.edit',
            'destroy' => 'permission:asset-types.delete',
        ]);
    Route::resource('campaigns', CampaignController::class)
        ->middleware([
            'index'   => 'permission:campaigns.view',
            'create'  => 'permission:campaigns.create',
            'store'   => 'permission:campaigns.create',
            'edit'    => 'permission:campaigns.edit',
            'update'  => 'permission:campaigns.edit',
            'destroy' => 'permission:campaigns.delete',
        ]);

    Route::resource('assets', AssetController::class)
        ->middleware([
            'index'   => 'permission:assets.view',
            'create'  => 'permission:assets.create',
            'store'   => 'permission:assets.create',
            'edit'    => 'permission:assets.edit',
            'update'  => 'permission:assets.edit',
            'destroy' => 'permission:assets.delete',
        ]);

    Route::delete('asset-media/{media}', [AssetController::class, 'destroyMedia'])
        ->name('asset-media.destroy')
        ->middleware('permission:assets.edit');

    Route::resource('roles', RoleController::class)->except(['show'])
        ->middleware([
            'index'   => 'permission:roles.view',
            'create'  => 'permission:roles.create',
            'store'   => 'permission:roles.create',
            'edit'    => 'permission:roles.edit',
            'update'  => 'permission:roles.edit',
            'destroy' => 'permission:roles.delete',
        ]);

    Route::resource('users', UserController::class)
        ->middleware([
            'index'   => 'permission:users.view',
            'show'    => 'permission:users.view',
            'create'  => 'permission:users.create',
            'store'   => 'permission:users.create',
            'edit'    => 'permission:users.edit',
            'update'  => 'permission:users.edit',
            'destroy' => 'permission:users.delete',
        ]);

    Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index')
        ->middleware('permission:activity_logs.view');

    Route::get('tickets', [TicketController::class, 'list'])->name('ticket.admin');
    Route::get('tickets/{ticket}', [TicketController::class, 'showAdmin'])->name('admin.tickets.show');
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'adminReply'])->name('admin.tickets.reply');
    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');
});
Route::prefix('')->group(function () {

    // Guest only
    Route::get('/', [FrontendAuthController::class, 'showSignin'])->name('signin');
    Route::post('/', [FrontendAuthController::class, 'signin']);
    Route::middleware('guest')->group(function () {
        Route::get('/signup', [FrontendAuthController::class, 'showSignup'])->name('signup');
        Route::post('/signup', [FrontendAuthController::class, 'signup'])->name('signup');
    });
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [FrontendAuthController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [FrontendAuthController::class, 'update'])->name('profile.update');

        Route::get('/home', [HomeController::class, 'index'])->name('home.index');
        Route::get('/campaign/{slug}', [HomeController::class, 'campaignDetails'])->name('campaign.details');
        Route::get('/asset/{slug}', [HomeController::class, 'assetdetails'])->name('asset.details');
        Route::get('/filter', [HomeController::class, 'filter'])->name('home.filter');
        Route::post('frontend/logout', [FrontendAuthController::class, 'logout'])->name('frontend.logout');
        Route::get('/drive/file/{type}/{id}', [FileController::class, 'stream'])
            ->name('drive.file.stream');
        Route::post('/drive/bulk-download', [FileController::class, 'bulkDownload'])->name('drive.bulkDownload');
        Route::get('/brand', [HomeController::class, 'brand'])->name('brand.index');
        Route::post('/bookmark', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');
        Route::get('/bookmark-list', [BookmarkController::class, 'list'])->name('bookmark.list');
        Route::post('/notification/{notification}/read', [NotificationController::class, 'markRead'])->name('notification.read');
        Route::post('/notification/read-all', [NotificationController::class, 'markAllRead'])->name('notification.readAll');

        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
        Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    });

    Route::get('settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');


    Route::post('/drive/upload/session', [DriveUploadController::class, 'createUploadSession'])
        ->name('drive.upload.session');

    Route::post('/drive/upload/complete', [DriveUploadController::class, 'completeUpload'])
        ->name('drive.upload.complete');
    Route::post('/drive/upload/resolve', [DriveUploadController::class, 'resolveFileId'])
        ->name('drive.upload.resolve');
});
