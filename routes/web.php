<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Blog;
use Illuminate\Support\Facades\Route;
use Livewire\Form;

Route::get('/', function () {
    $blogs = Blog::latest()->take(3)->get();
    return view('welcome',compact('blogs'));
});

Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog');
Route::get('/blog/{blog}', [\App\Http\Controllers\BlogController::class, 'single'])->name('blog.single');

Route::middleware('auth')->group(function () {
    Route::post('/webpush/subscribe', [\App\Http\Controllers\WebPushController::class, 'subscribe'])->name('webpush.subscribe');
    Route::get('/webpush/status', [\App\Http\Controllers\WebPushController::class, 'status'])->name('webpush.status');

    Route::livewire('/dashboard', 'dashboard')->name('dashboard');
    Route::livewire('/monthly', 'monthly-calendar')->name('monthly.calendar');
    Route::livewire('/reports', 'charts.reports')->name('reports');
    Route::livewire('/timeline', 'timeline')->name('timeline');
    Route::livewire('/profile', 'profile')->name('profile');
    Route::livewire('/support', 'support.support')->name('support');
    Route::livewire('/help', 'help')->name('help');
    Route::livewire('/task', 'task.task')->name('task');
    Route::livewire('/task/form', 'task.form')->name('task.form');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/',[DashboardController::class, 'index']);
        Route::resource('user',UserController::class);
        Route::resource('blog', BlogController::class);
        Route::resource('ticket', TicketController::class)->only(['index', 'edit', 'update','destroy']);
        Route::resource('comments',CommentsController::class)->only(['index','destroy','update']);
        Route::get('settings/index', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings/update', [SettingsController::class, 'update'])->name('settings.update');
    });
});

Route::get('/login',[AuthController::class,'loginForm'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.post');
Route::get('/register',[AuthController::class,'registerForm'])->name('register');
Route::post('/register',[AuthController::class,'register'])->name('register.post');
Route::get('/verify-phone',[AuthController::class,'verifyPhoneForm'])->name('verify.phone.form');
Route::post('/verify-phone',[AuthController::class,'verifyPhone'])->name('verify.phone.post');
Route::get('/forgot-password',[AuthController::class,'forgotPasswordForm'])->name('forgot.password.form');
Route::post('/forgot-password',[AuthController::class,'forgotPassword'])->name('forgot.password.post');
Route::get('/reset-password',[AuthController::class,'resetPasswordForm'])->name('reset.password.form');
Route::post('/reset-password',[AuthController::class,'resetPassword'])->name('reset.password.post');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');
