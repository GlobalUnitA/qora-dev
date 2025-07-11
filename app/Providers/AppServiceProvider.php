<?php

namespace App\Providers;

use App\Models\GradePolicy;
use App\Models\SubscriptionPolicy;
use App\Models\ReferralPolicy;
use App\Models\AssetPolicy;
use App\Models\TradingPolicy;
use App\Models\StakingPolicy;
use App\Models\BonusPolicy;
use App\Models\LanguagePolicy;
use App\Models\DepositToast;
use App\Observers\PolicyObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        GradePolicy::observe(PolicyObserver::class);
        SubscriptionPolicy::observe(PolicyObserver::class);
        ReferralPolicy::observe(PolicyObserver::class);
        AssetPolicy::observe(PolicyObserver::class);
        TradingPolicy::observe(PolicyObserver::class);
        //StakingPolicy::observe(PolicyObserver::class);

        View::composer('*', function ($view) {
            $languages = LanguagePolicy::where('type', 'locale')->first()->content ?? [];

            $view->with('locales', $languages);
        });

        View::composer('admin.layouts.master', function ($view) {
            $admin = auth()->guard('admin')->user();

            if ($admin && $admin->admin_level > 1) {
                $toasts = DepositToast::where('is_read', false)->latest()->get();
                $view->with('toasts', $toasts);
            } else {
                $view->with('toasts', collect());
            }
        });
    }
}
