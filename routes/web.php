<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HomemissionController;
use App\Http\Controllers\Admin\JourneysController;
use App\Http\Controllers\Admin\MessagesController;
use App\Http\Controllers\Admin\MissionController;
use App\Http\Controllers\Admin\NewsandnoticeController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\OrganizationStaffController;
use App\Http\Controllers\Admin\OtherorganizationController;
use App\Http\Controllers\Admin\StaffRoleController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\GalleryItemsController;
use App\Http\Controllers\Admin\HomeCarouselController;
use App\Http\Controllers\Admin\OrganizationHeroController;
use App\Http\Controllers\Admin\MediaHeroController;
use App\Http\Controllers\Admin\FaqHeroController;
use App\Http\Controllers\Admin\TeamHeroController;
use App\Http\Controllers\Admin\MessageHeroController;
use App\Http\Controllers\Admin\SisterHeroController;
use App\Http\Controllers\Admin\AdmissionFeeSettingController;

use App\Http\Controllers\WelcomeController;
use App\Models\Organization;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function () {
    Route::get('/uploads/{type}/{filename}', function ($type, $filename) {
        $allowed_types = ['medias', 'table', 'restaurant_menu_item', 'images', 'files', 'videos', 'audio'];
        if (in_array($type, $allowed_types)) {
            $path = storage_path('app/uploads/' . $type . '/' . $filename);
            if (!File::exists($path)) {
                abort(404);
            }

            $file = File::get($path);
            $type = File::mimeType($path);
            $size = File::size($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            $response->header("Content-Length", $size);
            return $response;
        }
        abort(404);
    })->name('assets.uploads');
});

Route::get('lang/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');

// Route::get('/', function () {
//     return view('guest.welcome');
// })->name('home');


// Route::get('/', [WelcomeController::class, 'index'])->name('home');
//medias





Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles/get-role', [RoleController::class, 'getRole'])->name('roles.get.role');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/roles/{role}/permissions', [RoleController::class, 'showPermissions'])->name('roles.permissions');
    Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{user}/editStatus', [UserController::class, 'updateStatus'])->name('users.update.status');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/organizations/create', [OrganizationController::class, 'create'])->name('organizations.create');
    Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
    Route::get('/organizations/{slug}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
    Route::match(['put', 'patch'], '/organizations/{slug}', [OrganizationController::class, 'update'])
        ->name('organizations.update');
    Route::post('/organizations/{slug}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');


    Route::get('/{slug}/otherorganizations', [OtherorganizationController::class, 'index'])->name('otherorganizations.index');
    Route::get('/{slug}/otherorganizations/create', [OtherorganizationController::class, 'create'])->name('otherorganizations.create');
    Route::post('/{slug}/otherorganizations', [OtherorganizationController::class, 'store'])->name('otherorganizations.store');
    Route::get('/{slug}/otherorganizations/{otherOrgSlug}/edit', [OtherorganizationController::class, 'edit'])->name('otherorganizations.edit');
    Route::put('/{slug}/otherorganizations/{otherOrgSlug}', [OtherorganizationController::class, 'update'])->name('otherorganizations.update');
    Route::delete('/{slug}/otherorganizations/{otherOrgSlug}', [OtherorganizationController::class, 'destroy'])->name('otherorganizations.destroy');


    Route::get('/media', [MediaController::class, 'index'])->name('dashboard.media.index'); // For potential media library view
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('dashboard.media.upload');
    Route::post('/dashboard/media/download', [MediaController::class, 'download'])->name('dashboard.media.download');
    Route::delete('/dashboard/media/{id}', [MediaController::class, 'destroy'])->name('dashboard.media.destroy');
    Route::post('/dashboard/media/restore/{id}', [MediaController::class, 'restore'])->name('dashboard.media.restore');
    Route::patch('/dashboard/media/{media}', [MediaController::class, 'update'])->name('dashboard.media.update');

    Route::get('/{slug}/dashboard', [DashboardController::class, 'index'])->name('organization.dashboard');

    Route::get('/staff-role', [StaffRoleController::class, 'index'])->name('staff-role.index');
    Route::post('/staff-role/store', [StaffRoleController::class, 'store'])->name('staff-role.store');
    Route::delete('/staff/{id}', [StaffRoleController::class, 'destroy'])->name('staff.destroy');

    Route::get('/organization-staff/{slug}', [OrganizationStaffController::class, 'index'])->name('organization-staff.index');
    Route::get('/organization-staff/{slug}/create', [OrganizationStaffController::class, 'create'])->name('organization-staff.create');
    Route::post('/organization-staff/{slug}/store', [OrganizationStaffController::class, 'store'])->name('organization-staff.store');
    Route::delete('/organization-staff/{id}/delete', [OrganizationStaffController::class, 'delete'])->name('organization-staff.delete');
    Route::get('/organization-staff/{slug}/edit/{id}', [OrganizationStaffController::class, 'edit'])->name('organization-staff.edit');
    Route::put('/organization-staff/{slug}/edit/{id}', [OrganizationStaffController::class, 'update'])->name('organization-staff.update');

    Route::get('/getstaffRoles', [OrganizationStaffController::class, 'getstaffRoles'])->name('organization-staff.getstaffRoles');

    Route::get('/faq/{slug}', [FaqController::class, 'index'])->name('faq.index');
    Route::post('/faq/{slug}/store', [FaqController::class, 'store'])->name('faq.store');
    Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');

    Route::get('/services/{slug}', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/services/{slug}/store', [ServiceController::class, 'store'])->name('services.store');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/mission/{slug}', [MissionController::class, 'index'])->name('mission.index');
    Route::post('/mission/{slug}/store', [MissionController::class, 'store'])->name('mission.store');
    Route::delete('/mission/{id}', [MissionController::class, 'destroy'])->name('mission.destroy');



    Route::get('/news/{slug}', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}/create', [NewsController::class, 'create'])->name('news.create');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::post('/news/{slug}/store', [NewsController::class, 'store'])->name('news.store');
    Route::put('/news/{slug}/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{slug}/{id}', [NewsController::class, 'destroy'])->name('news.destroy');

    Route::get('/notices/{slug}', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/{slug}/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::get('/notices/{slug}/{id}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
    Route::post('/notices/{slug}/store', [NoticeController::class, 'store'])->name('notices.store');
    Route::put('/notices/{slug}/{id}', [NoticeController::class, 'update'])->name('notices.update');
    Route::delete('/notices/{slug}/{id}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    
    Route::get('/events/{slug}', [EventsController::class, 'index'])->name('events.index');
    Route::get('/events/{slug}/create', [EventsController::class, 'create'])->name('events.create');
    Route::get('/events/{id}/edit', [EventsController::class, 'edit'])->name('events.edit');
    Route::post('/events/{slug}/store', [EventsController::class, 'store'])->name('events.store');
    Route::put('/events/{slug}/{id}', [EventsController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventsController::class, 'destroy'])->name('events.destroy');

    Route::get('/blog/{slug}', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}/create', [BlogController::class, 'create'])->name('blog.create');
    Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::post('/blog/{slug}/store', [BlogController::class, 'store'])->name('blog.store');
    Route::put('/blog/{slug}/{id}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');

    Route::get('/journeys/{slug}', [JourneysController::class, 'index'])->name('journeys.index');
    Route::get('/journeys/{slug}/create', [JourneysController::class, 'create'])->name('journeys.create');
    Route::get('/journeys/{id}/edit', [JourneysController::class, 'edit'])->name('journeys.edit');
    Route::post('/journeys/{slug}/store', [JourneysController::class, 'store'])->name('journeys.store');
    Route::put('/journeys/{slug}/{id}', [JourneysController::class, 'update'])->name('journeys.update');
    Route::delete('/journeys/{id}', [JourneysController::class, 'destroy'])->name('journeys.destroy');

    Route::get('/messages/{slug}', [MessagesController::class, 'index'])->name('messages.index');
    Route::get('/messages/{slug}/create', [MessagesController::class, 'create'])->name('messages.create');
    Route::get('/messages/{id}/edit', [MessagesController::class, 'edit'])->name('messages.edit');
    Route::post('/messages/{slug}/store', [MessagesController::class, 'store'])->name('messages.store');
    Route::put('/messages/{slug}/{id}', [MessagesController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{id}', [MessagesController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/{slug}/staff', [MessagesController::class, 'getOrganizationStaff'])->name('messages.getMessageableStaff');




    Route::get('/contact/{slug}', [ContactController::class, 'index'])->name('contact.index');
    Route::delete('/contact/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');


    Route::get('/collections/{slug}', [CollectionController::class, 'index'])->name('collections.index');
    Route::get('/collections/{slug}/create', [CollectionController::class, 'create'])->name('collections.create');
    Route::get('/collections/{id}/edit', [CollectionController::class, 'edit'])->name('collections.edit');
    Route::post('/collections/{slug}/store', [CollectionController::class, 'store'])->name('collections.store');
    Route::put('/collections/{id}', [CollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{slug}/{id}', [CollectionController::class, 'destroy'])->name('collections.destroy');
    Route::post('/organizations/{slug}/collections/toggle-status', [CollectionController::class, 'toggleStatus'])
        ->name('collections.toggleStatus');

    Route::get('/collections/{id}/gallery', [GalleryItemsController::class, 'index'])
        ->name('gallery-items.index');
    Route::get('/collections/{id}/gallery/data', [GalleryItemsController::class, 'galleryItems'])
        ->name('gallery-items.data');
    Route::post('/collections/{collectionId}/gallery/{itemId}/cover', [GalleryItemsController::class, 'setCover'])
        ->name('gallery-items.set-cover');
    Route::delete('/collections/{collectionId}/gallery/{itemId}', [GalleryItemsController::class, 'destroy'])
        ->name('gallery-items.destroy');
    Route::post('/collections/{id}/gallery/store', [GalleryItemsController::class, 'store'])
        ->name('gallery-items.store');
    Route::post('/collections/{collectionId}/gallery/{itemId}/toggle', [GalleryItemsController::class, 'toggleStatus'])
        ->name('gallery-items.toggle-status');

    Route::get('/home-carousel/{slug}', [HomeCarouselController::class, 'index'])->name('home-carousel.index');
    Route::post('/home-carousel/{slug}/store', [HomeCarouselController::class, 'store'])->name('home-carousel.store');
    Route::delete('/home-carousel/{id}/delete', [HomeCarouselController::class, 'destroy'])->name('home-carousel.destroy');

    Route::post('message/{staffId}/{organizationId}/exists', [OrganizationStaffController::class, 'messageExists'])->name('message.exists');
    Route::post('staff/{slug}/storeMessage', [OrganizationStaffController::class, 'storeMessage'])->name('organization-staff.storeMessage');

    // Route::get('/homemission/{slug}/create', [HomemissionController::class, 'create'])->name('mission.homemission');
    // Route::get('/homemission/{slug}/create', [HomemissionController::class, 'create'])
    //     ->name('homemission.create');

    // Route::post('/homemission/{slug}/store', [HomemissionController::class, 'store'])->name('homemission.store');

    // Route::get('/mission/homemission/{slug}', [HomemissionController::class, 'homemission'])
    //     ->name('mission.homemission');

    // Route::get('/homemission/{slug}/{id}/edit', [HomemissionController::class, 'edit'])
    //     ->name('homemission.edit');

    Route::get('/mission/homemission/{slug}', [HomemissionController::class, 'homemission'])
        ->name('mission.homemission');
    Route::get('/homemission/{slug}/create', [HomemissionController::class, 'create'])
        ->name('homemission.create');
    Route::post('/homemission/{slug}/store', [HomemissionController::class, 'store'])
        ->name('homemission.store');
    Route::get('/homemission/{slug}/{id}/edit', [HomemissionController::class, 'edit'])
        ->name('homemission.edit');
    Route::put('/homemission/{slug}/{id}', [HomemissionController::class, 'update'])
        ->name('homemission.update');


    Route::get('/about/abouthero/{slug}', [AboutController::class, 'abouthero'])
        ->name('about.abouthero');
    Route::get('/abouthero/{slug}/create', [AboutController::class, 'createAbouthero'])
        ->name('abouthero.create');
    Route::post('/abouthero/{slug}/store', [AboutController::class, 'storeAbouthero'])
        ->name('abouthero.store');
    Route::get('/abouthero/{slug}/{id}/edit', [AboutController::class, 'editAbouthero'])
        ->name('abouthero.edit');
    Route::put('/abouthero/{slug}/{id}', [AboutController::class, 'updateAbouthero'])
        ->name('abouthero.update');


    Route::get('/about/aboutstory/{slug}', [AboutController::class, 'aboutstory'])
        ->name('about.aboutstory');
    Route::get('/aboutstory/{slug}/create', [AboutController::class, 'createAboutstory'])
        ->name('aboutstory.create');
    Route::post('/aboutstory/{slug}/store', [AboutController::class, 'storeAboutstory'])
        ->name('aboutstory.store');
    Route::get('/aboutstory/{slug}/{id}/edit', [AboutController::class, 'editAboutstory'])
        ->name('aboutstory.edit');
    Route::put('/aboutstory/{slug}/{id}', [AboutController::class, 'updateAboutstory'])
        ->name('aboutstory.update');


    Route::get('/newsandnotice/newshero/{slug}', [NewsandnoticeController::class, 'newshero'])
        ->name('newsandnotice.newshero');
    Route::get('/newshero/{slug}/create', [NewsandnoticeController::class, 'createnewshero'])
        ->name('newshero.create');
    Route::post('/newshero/{slug}/store', [NewsandnoticeController::class, 'storenewshero'])
        ->name('newshero.store');
    Route::get('/newshero/{slug}/{id}/edit', [NewsandnoticeController::class, 'editnewshero'])
        ->name('newshero.edit');
    Route::put('/newshero/{slug}/{id}', [NewsandnoticeController::class, 'updatenewshero'])
        ->name('newshero.update');

    Route::prefix('{slug}/organizationhero')->group(function () {
        Route::get('/', [OrganizationHeroController::class, 'hero'])
            ->name('organizationhero');

        Route::get('/create', [OrganizationHeroController::class, 'create'])
            ->name('organizationhero.create');

        Route::post('/store', [OrganizationHeroController::class, 'store'])
            ->name('organizationhero.store');

        Route::get('/edit/{id}', [OrganizationHeroController::class, 'edit'])
            ->name('organizationhero.edit');

        Route::put('/update/{id}', [OrganizationHeroController::class, 'update'])
            ->name('organizationhero.update');
    });

    Route::prefix('{slug}/mediahero')->group(function () {
        Route::get('/', [MediaHeroController::class, 'mediahero'])->name('mediahero');
        Route::get('/create', [MediaHeroController::class, 'createmediahero'])->name('mediahero.create');
        Route::post('/store', [MediaHeroController::class, 'storemediahero'])->name('mediahero.store');
        Route::get('/edit/{id}', [MediaHeroController::class, 'editmediahero'])->name('mediahero.edit');
        Route::put('/update/{id}', [MediaHeroController::class, 'updatemediahero'])->name('mediahero.update');
    });

    Route::prefix('{slug}/faqhero')->group(function () {
        Route::get('/', [FaqHeroController::class, 'faqhero'])->name('faqhero');
        Route::get('/create', [FaqHeroController::class, 'createfaqhero'])->name('faqhero.create');
        Route::post('/store', [FaqHeroController::class, 'storefaqhero'])->name('faqhero.store');
        Route::get('/edit/{id}', [FaqHeroController::class, 'editfaqhero'])->name('faqhero.edit');
        Route::put('/update/{id}', [FaqHeroController::class, 'updatefaqhero'])->name('faqhero.update');
    });

    Route::prefix('{slug}/teamhero')->group(function () {
        Route::get('/', [TeamHeroController::class, 'teamhero'])->name('teamhero');
        Route::get('/create', [TeamHeroController::class, 'createteamhero'])->name('teamhero.create');
        Route::post('/store', [TeamHeroController::class, 'storeteamhero'])->name('teamhero.store');
        Route::get('/edit/{id}', [TeamHeroController::class, 'editteamhero'])->name('teamhero.edit');
        Route::put('/update/{id}', [TeamHeroController::class, 'updateteamhero'])->name('teamhero.update');
    });

    Route::prefix('{slug}/messagehero')->group(function () {
        Route::get('/', [MessageHeroController::class, 'messagehero'])->name('messagehero');
        Route::get('/create', [MessageHeroController::class, 'createmessagehero'])->name('messagehero.create');
        Route::post('/store', [MessageHeroController::class, 'storemessagehero'])->name('messagehero.store');
        Route::get('/edit/{id}', [MessageHeroController::class, 'editmessagehero'])->name('messagehero.edit');
        Route::put('/update/{id}', [MessageHeroController::class, 'updatemessagehero'])->name('messagehero.update');
    });

    Route::prefix('{slug}/sisterhero')->group(function () {
        Route::get('/', [SisterHeroController::class, 'sisterhero'])
            ->name('sisterhero');
        Route::get('/create', [SisterHeroController::class, 'createsisterhero'])
            ->name('sisterhero.create');
        Route::post('/store', [SisterHeroController::class, 'storesisterhero'])
            ->name('sisterhero.store');
        Route::get('/edit/{id}', [SisterHeroController::class, 'editsisterhero'])
            ->name('sisterhero.edit');
        Route::put('/update/{id}', [SisterHeroController::class, 'updatesisterhero'])
            ->name('sisterhero.update');
    });

    // Admission & Fee Settings
    Route::get('/admission-fee-settings/{slug}', [AdmissionFeeSettingController::class, 'index'])->name('admission-fee-settings.index');
    Route::post('/admission-fee-settings/{slug}/store', [AdmissionFeeSettingController::class, 'store'])->name('admission-fee-settings.store');
    Route::delete('/admission-fee-settings/{id}', [AdmissionFeeSettingController::class, 'destroy'])->name('admission-fee-settings.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/website.php';
