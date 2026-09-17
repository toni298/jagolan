<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProductController as PublicProductController;
use App\Http\Controllers\Public\PostController as PublicPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [PublicProductController::class, 'index'])
    ->middleware('throttle:60,1') // 60 requests per minute
    ->name('products.index');
Route::get('/produk/{slug}', [PublicProductController::class, 'show'])
    ->middleware('throttle:60,1')
    ->name('products.show');
Route::get('/blog', [PublicPostController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('posts.index');
Route::get('/blog/{slug}', [PublicPostController::class, 'show'])
    ->middleware('throttle:60,1')
    ->name('posts.show');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1'); // 5 attempts per 1 minute
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Product Categories
    Route::resource('product-categories', ProductCategoryController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{id}/images', [ProductController::class, 'uploadImages'])->name('products.images');
    Route::delete('product-images/{id}', [ProductController::class, 'destroyImage'])->name('product-images.destroy');
    Route::post('product-images/{id}/set-primary', [ProductController::class, 'setPrimaryImage'])->name('product-images.set-primary');

    // Posts
    Route::resource('posts', PostController::class);
    Route::get('search-products', [PostController::class, 'searchProducts'])->name('search-products');

    // Landing Page Management
    Route::get('landing-page', [LandingPageController::class, 'index'])->name('landing-page.index');
    Route::put('landing-page', [LandingPageController::class, 'update'])->name('landing-page.update');

    // Users
    Route::resource('users', UserController::class);

    // Image upload for TinyMCE
    Route::post('upload-image', function (Request $request) {
        $validator = Illuminate\Support\Facades\Validator::make($request->all(), [
            'file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first('file')], 422);
        }
        $file = $request->file('file');
        // Nama acak + ekstensi tervalidasi -> cegah file berbahaya / overwrite
        $filename = Illuminate\Support\Str::uuid()->toString() . '.' . strtolower($file->extension());
        $path = $file->storeAs('uploads', $filename, 'public');
        return response()->json(['location' => asset('storage/' . $path)]);
    })->name('upload-image');
});