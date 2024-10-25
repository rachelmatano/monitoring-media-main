<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Auth;
use App\Models\NewsMedia;
use App\Models\AdminNotification;
use App\Models\MediaNotification;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        view()->composer('*',function($view){
            $user = Auth::guard('reporters')->user();
            if($user){
                $media_id = NewsMedia::where('ref_code',$user->code)->first()->id;
                $notifSide = MediaNotification::where('media_id',$media_id)->where('status','unread')->count();
                $view->with('notifSide',$notifSide);
            }else{
                $user = Auth::user();
                if($user){
                    $notifSide = AdminNotification::where('status','unread')->count();
                    $view->with('notifSide',$notifSide);
                }
            }
        });
    }
}
