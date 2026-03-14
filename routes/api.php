<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/organization', [App\Http\Controllers\Api\OrganizationController::class, 'index']);


Route::prefix('/faqs')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\FaqController::class, 'index']);
});
Route::prefix('/messages')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\MessageController::class, 'index']);
});
Route::prefix('/missions')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\MissionController::class, 'index']);
});
Route::prefix('/teams')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\TeamController::class, 'index']);
});
Route::prefix('/heros')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\HeroController::class, 'index']);
});
Route::prefix('/journeys')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\JourneyController::class, 'index']);
});
Route::prefix('/news')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\NewController::class, 'index']);
});
Route::prefix('/notices')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\NoticeController::class, 'index']);
});
Route::prefix('/other-organizations')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\OtherOrganizationController::class, 'index']);
});
Route::prefix('/homemissions')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\HomemissionController::class, 'index']);
});
Route::prefix('/about-hero')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\AboutController::class, 'abouthero']);
});

Route::prefix('/about-story')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\AboutController::class, 'aboutstory']);
});
Route::prefix('/organization-hero')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\AboutController::class, 'aboutstory']);
});
Route::prefix('/media-hero')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\MediaHeroController::class, 'show']);
});
Route::prefix('/faq-hero')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\FaqHeroController::class, 'show']);
});
Route::prefix('/team-hero')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\TeamHeroController::class, 'show']);
});
Route::prefix('/message-hero')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\MessageHeroController::class, 'show']);
});
Route::prefix('/blogs')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\BlogController::class, 'index']);
});
Route::prefix('/events')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\EventsController::class, 'index']);
});
Route::prefix('/collections')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\CollectionController::class, 'index']);
});
Route::prefix('/gallery-items')->group(function () {
    Route::get('/{collection_id}', [App\Http\Controllers\Api\GalleryItemController::class, 'index']);
});
Route::get('/stats', [App\Http\Controllers\Api\StatController::class, 'stats']);

Route::prefix('/contact')->group(function () {
    Route::post('/', [App\Http\Controllers\Api\ContactController::class, 'store']);
});
Route::get('/organization/all', [App\Http\Controllers\Api\OrganizationController::class, 'all']);

Route::prefix('/admission-fee-settings')->group(function () {
    Route::get('/{organization_id?}', [App\Http\Controllers\Api\AdmissionFeeSettingController::class, 'index']);
});