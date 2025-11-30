<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\TechniqueController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// Public Routes
Route::get('/portfolios', [PublicController::class, 'index'])->name('public.index');

Route::prefix('portfolio/{slug}')->group(function () {
    Route::get('/', [PublicController::class, 'home'])->name('public.home');
    Route::get('/projets', [PublicController::class, 'projects'])->name('public.projects');
    Route::get('/projets/{projectSlug}', [PublicController::class, 'projectShow'])->name('public.projects.show');
    Route::get('/a-propos', [PublicController::class, 'about'])->name('public.about');
    Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
    Route::post('/contact', [PublicController::class, 'contactSubmit'])->name('public.contact.submit');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('users', UserController::class);
    Route::resource('formations', FormationController::class);
    Route::resource('experiences', ExperienceController::class);
    Route::resource('technologies', TechnologyController::class);
    Route::resource('techniques', TechniqueController::class);
    Route::resource('galleries', GalleryController::class);

    // Display order management
    Route::post('projects/{project:id}/reorder', [ProjectController::class, 'reorder'])->name('projects.reorder');
    Route::post('formations/{formation}/reorder', [FormationController::class, 'reorder'])->name('formations.reorder');
    Route::post('experiences/{experience}/reorder', [ExperienceController::class, 'reorder'])->name('experiences.reorder');
    Route::post('technologies/{technology}/reorder', [TechnologyController::class, 'reorder'])->name('technologies.reorder');
    Route::post('techniques/{technique}/reorder', [TechniqueController::class, 'reorder'])->name('techniques.reorder');

    // Image management within galleries
    Route::post('galleries/{gallery}/images', [GalleryController::class, 'addImages'])->name('galleries.images.add');
    Route::delete('galleries/{gallery}/images/{image}', [GalleryController::class, 'deleteImage'])->name('galleries.images.delete');
    Route::post('galleries/{gallery}/images/{image}/reorder', [GalleryController::class, 'reorderImage'])->name('galleries.images.reorder');
});

// Admin Routes (protected by Gate in controller)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::post('users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('users.password');
    Route::post('users/{user}/toggle-role', [AdminUserController::class, 'toggleRole'])->name('users.toggle-role');
});

require __DIR__.'/settings.php';
