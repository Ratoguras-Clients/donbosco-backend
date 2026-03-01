<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\SisterOrganizationController;
use App\Http\Controllers\WelcomeController;
use App\Models\Organization;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return redirect('/docs/api');
})->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/team', [TeamController::class, 'index'])->name('team');
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/message', [MessageController::class, 'index'])->name('message');
Route::get('/faqs', [MessageController::class, 'faq'])->name('faq.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/notices', [NoticeController::class, 'index'])->name('notices');
Route::get('/news/details/{news}', [NewsController::class, 'show'])
    ->name('news.show');
Route::get('/notices/details/{notices}', [NoticeController::class, 'show'])
    ->name('notices.show');

Route::get('/contact', function () {
    $organizations = Organization::where('status', 'active')->orderby('id', 'DESC')->get();

    return view('guest.contact', compact('organizations'));
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store');
Route::get('/organization/{slug}', [SisterOrganizationController::class, 'index'])->name('sister');
Route::get('/organization/{slug}/news', [SisterOrganizationController::class, 'SisterNews'])->name('sister.news');
Route::get('/organization/{slug}/faqs', [SisterOrganizationController::class, 'faqs'])->name('sister.faqs');
Route::get('/sister-organization', [SisterOrganizationController::class, 'show'])->name('organization');

// Route::get('/team', function () {
//     return view('guest.team');
// })->name('team');

