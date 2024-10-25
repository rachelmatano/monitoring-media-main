<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminNotificationController,
    ApprovedMediaController,
    MediaNotificationController,
    MediaProveController,
    NewsMediaController,
    ProjectController,
    ProjectParticipantController,
    ProveGalleryController,
    ReporterController,
    UserController,
    LoginController,
    ReporterMediaController,
    DashboardAdminController,
    ForgetPasswordController
};


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

Route::middleware(['auth'])->group(function(){
    Route::get('/', [DashboardAdminController::class,'index']);
    
    Route::resource('/reporter',ReporterController::class);
    Route::post('/filter-reporter',[ReporterController::class,'filter']);

    Route::resource('/news_media',NewsMediaController::class);
    Route::post('/filter-news-media',[NewsMediaController::class,'filter']);

    Route::resource('/media_notification',MediaNotificationController::class);
    Route::post('/filter-media-notification',[MediaNotificationController::class,'filter']);

    Route::resource('/project',ProjectController::class);
    Route::post('/filter-project',[ProjectController::class,'filter']);
    Route::resource('/project_participant',ProjectParticipantController::class);

    Route::resource('/media_prove',MediaProveController::class);
    Route::post('/filter-media-prove',[MediaProveController::class,'filter']);

    Route::get('/admin_notification',[AdminNotificationController::class,'index']);
    Route::get('/admin_notification/{id}',[AdminNotificationController::class,'show']);
    Route::get('/admin_notification/reporter/detail/{id}',[ReporterController::class,'show']);
    Route::post('/filter-admin-notification',[AdminNotificationController::class,'filter']);

    Route::get('/admin_clear_filter/{tipe}',[UserController::class,'clearFilter']);

    Route::get('/admin/reporter/detail/{rId}',[ReporterController::class,'show']);
    Route::get('/media_status',[MediaProveController::class,'currentStatus']);
    Route::get('/get_media_data_per_month/{period}/{media}/{project?}',[MediaProveController::class,'getDataPerMonth']);
    Route::get('/history_media_performance',[MediaProveController::class,'historyMedia']);
    Route::get('/get_media_data_per_year/{period}/{media}/{project?}',[MediaProveController::class,'getDataPerYear']);

    Route::resource('/prove_gallery',ProveGalleryController::class);
    Route::get('/prove_gallery_add/{id}',[ProveGalleryController::class,'create']);
    Route::resource('/user',UserController::class);
    Route::post('/user_update_password',[UserController::class,'update_password'])->name('user.update_password');
    Route::post('/update_user_profile',[UserController::class,'update_profile'])->name('update.profile.admin');
    Route::post('/admin_update_password',[UserController::class,'admin_update_password']);
    Route::get('/admin_profile',[UserController::class,'profile']);
    Route::get('/logout_admin',[LoginController::class,'logout_admin']);

    Route::get('/approved_media/list',[ApprovedMediaController::class,'list']);
    Route::post('/filter-approved-list',[ApprovedMediaController::class,'filterList']);
    Route::get('/filter-clear-approved-list',[ApprovedMediaController::class,'clearFilter']);
    Route::post('/approved_media/store',[ApprovedMediaController::class,'approved']);
    Route::delete('/approved_media/{id}',[ApprovedMediaController::class,'destroy']);
    Route::get('/approved_media',[ApprovedMediaController::class,'index']);
    Route::post('/filter-approved-media',[ApprovedMediaController::class,'filter']);
    Route::get('/filter-clear-approved-media',[ApprovedMediaController::class,'clearFilterApproved']);

    Route::get('/get_data_per_year/{year}/{project?}',[DashboardAdminController::class,'getDataPerYear']);

    
});

//Admin
Route::get('/login-admin',[LoginController::class,'login_admin'])->name('login.admin');
Route::post('/validate-login-admin',[LoginController::class,'validate_login_admin'])->name('login.validate.admin');
Route::get('/forget_password_admin',[ForgetPasswordController::class,'emailViewAdmin']);
Route::post('/request_forget_password_admin',[ForgetPasswordController::class,'checkEmailAdmin']);
Route::post('/token_forget_password_admin',[ForgetPasswordController::class,'tokenViewAdmin']);
Route::post('/save_forget_password_admin',[ForgetPasswordController::class,'updatePasswordAdmin']);

//Reporters
Route::get('/login-reporter',[LoginController::class,'login_reporters'])->name('login.reporter');
Route::post('/validate-login-reporter',[LoginController::class,'validate_login_reporter'])->name('login.validate.reporter');
Route::get('/register',[LoginController::class,'register_view']);
Route::post('/register-validate',[LoginController::class,'validate_register'])->name('register.store');

Route::get('/forget_password',[ForgetPasswordController::class,'emailViewReporter']);
Route::post('/request_forget_password',[ForgetPasswordController::class,'checkEmailReporter']);
Route::post('/token_forget_password',[ForgetPasswordController::class,'tokenViewReporter']);
Route::post('/save_forget_password',[ForgetPasswordController::class,'updatePasswordReporter']);

Route::middleware(['auth:reporters'])->group(function(){
    Route::get('/dashboard',[ReporterMediaController::class,'index']);
    Route::get('/logout-reporter',[LoginController::class,'logout_reporter']);
    Route::get('/media_reporter',[ReporterMediaController::class,'media']);
    Route::get('/project_media',[ReporterMediaController::class,'project']);
    Route::get('/project_media/{id}',[ReporterMediaController::class,'project_detail']);
    Route::post('/project_filter',[ReporterMediaController::class,'projectFilter']);

    Route::get('/notification',[ReporterMediaController::class,'notifications']);
    Route::get('/notification/{id}',[ReporterMediaController::class,'notification_detail']);
    Route::post('/filter_notification',[ReporterMediaController::class,'filterNotification']);

    Route::get('/media_prove_reporter',[ReporterMediaController::class,'media_prove'])->name('media_prove.index');
    Route::get('/media_prove_reporter_create',[ReporterMediaController::class,'media_prove_create'])->name('media_prove.create');
    Route::post('/media_prove_reporter',[ReporterMediaController::class,'media_prove_store'])->name('media_prove.store');
    Route::get('/media_prove_reporter/{id}',[ReporterMediaController::class,'media_prove_edit'])->name('media_prove.edit');
    Route::get('/media_prove_reporter_detail/{id}',[ReporterMediaController::class,'media_prove_detail'])->name('media_prove.detail');
    Route::put('/media_prove_reporter/{id}',[ReporterMediaController::class,'media_prove_update'])->name('media_prove.update');
    Route::delete('/media_prove_reporter/{id}',[ReporterMediaController::class,'media_prove_destroy'])->name('media_prove.destroy');
    Route::post('/filter_media_prove',[ReporterMediaController::class,'filterMediaProve']);

    Route::get('/reporter_prove_gallery/{proveId}',[ReporterMediaController::class,'prove_gallery_form']);
    Route::post('/reporter_prove_gallery',[ReporterMediaController::class,'prove_gallery_store']);
    Route::delete('/reporter_prove_gallery_delete/{id}',[ReporterMediaController::class,'prove_gallery_destroy']);

    Route::get('/user_profile',[ReporterMediaController::class,'profile']);
    Route::get('/edit_user_profile',[ReporterMediaController::class,'edit_profile']);
    Route::put('/update_user_profile',[ReporterMediaController::class,'update_profile']);
    Route::get('/password_form',[ReporterMediaController::class,'password_form']);
    Route::post('/update_password_reporter',[ReporterMediaController::class,'user_update_password']);

    Route::get('/status',[ReporterMediaController::class,'getStatusProve']);
    Route::get('/history',[ReporterMediaController::class,'getHistoryMedia']);
    Route::get('/data_per_year/{year}/{project}',[ReporterMediaController::class,'getDataPerYear']);
    Route::get('/data_per_month/{period}/{project}',[ReporterMediaController::class,'getDataPerMonthApi']);
    Route::get('/project_participant_confirm/{ppId}',[ProjectParticipantController::class,'confirmToFollowProject']);

    Route::get('/reporter_clear_filter/{tipe}',[ReporterMediaController::class,'clearFilter']);
});
Route::get('/temp/{year}',[ReporterMediaController::class,'getDataPerYear']);
Route::get('/month/{period}',[ReporterMediaController::class,'getDataPerMonth']);

Route::resource('/approved_media',ApprovedMediaController::class);
