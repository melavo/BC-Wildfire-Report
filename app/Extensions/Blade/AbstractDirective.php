<?php

namespace App\Extensions\Blade;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;

/**
 * Class AbstractDirective
 * @package App\Extensions\Blade
 */
abstract class AbstractDirective implements BladeDirectiveInterface
{
    /**
     * @var string
     */
    protected static $directive;

    /**
     * @var Application $app
     */
    protected $app;

    /**
     * @param  Application  $application
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * @return string
     */
    abstract public function __invoke(): string;

    /**
     * @return string
     */
    public function getDirective(): string
    {
        return static::$directive;
    }

    /**
     * @param  Application  $application
     */
    public static function boot(Application $application)
    {
        $instance = new static($application);

        $application->instance(static::class, $instance);

        Blade::directive(
            $instance->getDirective(),
            function (...$parameters) {
                return sprintf(
                    '<?php echo app("%s")(%s); ?>',
                    static::class,
                    implode(', ', $parameters)
                );
            }
        );
    }
}
