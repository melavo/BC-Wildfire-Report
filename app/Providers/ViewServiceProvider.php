<?php

namespace App\Providers;

use App\Extensions\Blade\Gmap;

use Illuminate\View\ViewServiceProvider as LaravelViewServiceProvider;

/**
 * Class ViewServiceProvider
 * @package App\Providers
 */
class ViewServiceProvider extends LaravelViewServiceProvider
{
    /**
     * @var string[] $bladeDirectives
     */
    protected static $bladeDirectives = [
        Gmap::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (php_sapi_name() !== 'cli') {
            /** @var BladeDirectiveInterface $directive */
            foreach (static::$bladeDirectives as $directive) {
                $directive::boot($this->app);
            }
        }
    }
}
