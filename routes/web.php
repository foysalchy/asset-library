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
use App\Http\Controllers\DownloadLogController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\Frontend\EmailVerificationController;
use Google\Service\Storage;
use Illuminate\Support\Facades\Storage as FacadesStorage;

// dashboard pages

Route::get('/test-drive', function () {
    FacadesStorage::disk('google_drive')->write('test.txt', 'Hello from Laravel!');
    return 'Upload success!';
});

Route::get('/create-storage-link', function () {

    $target = storage_path('app/public');
    $link = public_path('storage');

    if (file_exists($link)) {
        return "Storage link already exists.";
    }

    File::link($target, $link);

    return "Storage link created successfully.";
});
Route::middleware('guest')->group(function () {
    Route::get('/admin-login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin-login', [AuthController::class, 'login']);
    Route::get('/guest/tickets/create', [TicketController::class, 'guestCreate'])->name('guest.tickets.create');
    Route::post('/guest/tickets', [TicketController::class, 'guestStore'])->name('guest.tickets.store');
});
Route::get('/fix-media-paths', function () {
    $medias = \App\Models\AssetMedia::where('file_path', 'not like', 'drive:%')
        ->where('file_path', 'not like', 'http%')
        ->get();

    foreach ($medias as $media) {
        $mime    = $media->mime_type ?? '';
        $isLocal = str_starts_with($mime, 'image');

        // Video হলে drive: prefix যোগ করো
        if (!$isLocal) {
            $media->update(['file_path' => 'drive:' . $media->file_path]);
            echo "Fixed: {$media->id} → drive:{$media->file_path} <br>";
        }
    }

    return 'Done!';
})->middleware('auth');

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
    Route::post('/assets/sort', [AssetController::class, 'sort'])->name('assets.sort');

    Route::delete('asset-media/{media}', [AssetController::class, 'destroyMedia'])
        ->name('asset-media.destroy')
        ->middleware('permission:assets.edit');
    Route::delete('asset/delete-media/{media}', [AssetController::class, 'destroyAsssetMedia'])
        ->name('asset.delete.media.destroy')
        ->middleware('permission:assets.edit');
    Route::post('/assets/media/upload-image', [AssetController::class, 'uploadImageImmediate'])
        ->name('assets.media.upload-image');
    Route::post('/assets/media/delete-temp-image', [AssetController::class, 'deleteTempImage'])
        ->name('assets.media.delete-temp-image')
        ->middleware('auth');

    Route::resource('roles', RoleController::class)->except(['show'])
        ->middleware([
            'index'   => 'permission:roles.view',
            'create'  => 'permission:roles.create',
            'store'   => 'permission:roles.create',
            'edit'    => 'permission:roles.edit',
            'update'  => 'permission:roles.edit',
            'destroy' => 'permission:roles.delete',
        ]);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
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
    Route::get('users/{user}/download-logs/more', [UserController::class, 'loadMoreDownloadLogs'])
        ->name('users.download-logs.more');

    Route::get('users/{user}/activity-logs/more', [UserController::class, 'loadMoreActivityLogs'])
        ->name('users.activity-logs.more');
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
        Route::get('/forgot-password', [FrontendAuthController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [FrontendAuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [FrontendAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [FrontendAuthController::class, 'reset'])->name('password.reset.update');
    });

    Route::middleware('auth')->group(function () {

        Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
            ->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
    });

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/profile', [FrontendAuthController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [FrontendAuthController::class, 'update'])->name('profile.update');
        Route::put('/password', [FrontendAuthController::class, 'updatePassword'])->name('password.update');
        Route::get('/assets/{asset:slug}/edit-content', [AssetController::class, 'editContent'])
            ->name('assets.edit-content');
        Route::get('/drive/media/{media}/base64', [FileController::class, 'base64Image'])
            ->name('drive.media.base64');
        Route::get('/home', [HomeController::class, 'index'])->name('home.index');
        Route::get('/campaign/{slug}', [HomeController::class, 'campaignDetails'])->name('campaign.details');
        Route::get('/asset/{slug}', [HomeController::class, 'assetdetails'])->name('asset.details');
        Route::get('/filter', [HomeController::class, 'filter'])->name('home.filter');
        Route::post('frontend/logout', [FrontendAuthController::class, 'logout'])->name('frontend.logout');
        Route::get('/drive/file/{type}/{id}', [FileController::class, 'stream'])
            ->name('drive.file.stream');
        Route::get('/drive/media/{media}', [FileController::class, 'streamMedia'])
            ->name('drive.media.stream');
        Route::post('/assets/media/{media}/process-video', [FileController::class, 'processVideo'])
            ->name('assets.media.process-video')
            ->middleware('auth');



        Route::get('/download-logs', [DownloadLogController::class, 'index'])->name('download-logs.index');

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

    Route::get('/assets/{asset}/video/{media}/download', [FileController::class, 'downloadVideo'])
        ->name('assets.video.download')
        ->middleware('auth');
    Route::post('/download-logs/track', [DownloadLogController::class, 'track'])
        ->name('download-logs.track')
        ->middleware('auth');

    Route::middleware('auth')->group(function () {
        Route::post('/fcm/token', [FcmController::class, 'saveToken'])->name('fcm.token');
    });
});
