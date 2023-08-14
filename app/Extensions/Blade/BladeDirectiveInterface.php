<?php

namespace App\Extensions\Blade;

use Illuminate\Contracts\Foundation\Application;

/**
 * Interface BladeDirectiveInterface
 * @package App\Extensions\Blade
 */
interface BladeDirectiveInterface
{
    /**
     * @param  Application  $application
     */
    public static function boot(Application $application);
}
