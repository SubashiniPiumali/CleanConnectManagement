<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\RequestController;
use App\Http\Controllers\User\ProfileController;
use App\Models\Category;

// Public Routes
Route::get('/', function () {
    $categories = Category::all();
    return view('home', compact('categories'));
})->name('home');

Route::view('/about', 'user.about')->name('about');
Route::view('/contact', 'user.contact')->name('contact');

// Authentication Routes
Auth::routes();
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard');
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});

// Admin Dashboard
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Category Routes
    Route::prefix('category')->group(function () {
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/manage', [CategoryController::class, 'index'])->name('category.manage');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });

    // Team Member Routes
    Route::get('/member/create', [TeamMemberController::class, 'create'])->name('member.create');
    Route::post('/member/store', [TeamMemberController::class, 'store'])->name('member.store');
    Route::get('/team-members', [TeamMemberController::class, 'index'])->name('team-members.index');
    Route::get('/team-members/{id}', [TeamMemberController::class, 'show'])->name('team-members.show');
    Route::get('/team-members/search', [TeamMemberController::class, 'search'])->name('team-members.search');
    Route::delete('/admin/team-members/{id}', [TeamMemberController::class, 'destroy'])->name('team-members.destroy');
    Route::get('/admin/team-members/{id}/edit', [TeamMemberController::class, 'edit'])->name('team-members.edit');
    Route::put('/admin/team-members/{id}', [TeamMemberController::class, 'update'])->name('team-members.update');

    // Request Management
    Route::prefix('requests')->group(function () {
        Route::get('/new', [AdminRequestController::class, 'new'])->name('admin.requests.new');
        Route::get('/assigned', [AdminRequestController::class, 'assigned'])->name('admin.requests.assigned');
        Route::get('/rejected', [AdminRequestController::class, 'rejected'])->name('admin.requests.rejected');
        Route::put('/{id}/assign-email', [AdminRequestController::class, 'assignByEmail'])->name('requests.assignByEmail');
        Route::put('/{id}/unassign', [AdminRequestController::class, 'unassign'])->name('requests.unassign');
        Route::put('/{id}/cancel', [AdminRequestController::class, 'cancel'])->name('requests.cancel');
    });
});

// User Requests
Route::middleware('auth')->group(function () {
    Route::post('/requests/store', [RequestController::class, 'store'])->name('requests.store');
    Route::get('/user/requests', [RequestController::class, 'index'])->name('user.requests');
    Route::put('/requests/update-multiple', [RequestController::class, 'updateMultiple'])->name('requests.updateMultiple');
    Route::put('/requests/update/{id}', [RequestController::class, 'update'])->name('requests.update');
    Route::delete('/requests/delete/{id}', [RequestController::class, 'destroy'])->name('requests.destroy');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('user.profile.create');
    Route::post('/profile/store', [ProfileController::class, 'store'])->name('user.profile.store');
});


