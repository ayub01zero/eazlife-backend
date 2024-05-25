<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');
            Filament::registerNavigationItems([
                NavigationItem::make('Register new company')
                    ->url('/')
                    ->icon('heroicon-o-bars-4')
                    ->sort(3)
                    ->label(__('register new company')),
            ]);
        });


        
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en','ar','ku'])
                ->labels([
                    'ku' => 'kurdish',
                    'en' => 'english',
                    'ar' => 'arabic',
                    
                    // Other custom labels as needed
                ]);
        });
    }
}
