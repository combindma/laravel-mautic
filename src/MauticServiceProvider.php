<?php

namespace Combindma\Mautic;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MauticServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mautic')
            ->hasConfigFile('mautic')
            ->hasViews()
            ->hasRoute('web');
    }

    public function packageRegistered()
    {
        $this->app->singleton('laravel-mautic', function () {
            return new Mautic();
        });
    }
}
