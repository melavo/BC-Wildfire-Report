<?php

namespace App\Extensions\Blade;

use App\Services\Google;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class Gmap
 * @package App\Extensions\Blade
 */
class Gmap extends AbstractDirective
{
    /**
     * @var string
     */
    protected static $directive = 'gmap';

    protected static $queryParameters = [
        'center',
        'zoom',
        'size',
        'maptype',
    ];

    /**
     * @var Google $google
     */
    protected $google;

    /**
     * @param  Application  $application
     * @return void
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);

        $this->google = $application->make(Google::class);
    }

    /**
     * @param  array|string  $parameters
     * @param  array|null  $libraries
     * @param  bool  $cluster
     * @return string
     */
    public function __invoke($parameters = [], ?array $libraries = [], bool $cluster = false): string
    {
        if ($parameters === 'script') {
            return sprintf(
                '<script src="https://maps.googleapis.com/maps/api/js?key=%s&callback=Function.prototype%s"></script>%s',
                $this->google->getPublicKey(),
                !empty($libraries) ? '&libraries='.implode(',', $libraries) : '',
                $cluster ? sprintf(
                    '<script src="%s" type="text/javascript"></script>',
                    asset('/js/markerclusterer.js')
                ) : ''
            );
        }

        $query = array_merge(
            [
                'zoom'    => 10,
                'size'    => '600x300',
                'maptype' => 'roadmap',
                'key'     => $this->google->getPublicKey(),
            ],
            array_intersect_key(
                $parameters,
                array_flip(static::$queryParameters)
            )
        );

        if (isset($parameters['lat']) && isset($parameters['lon'])) {
            $query['markers'] = sprintf(
                'color:red|label:R|%s,%s',
                $parameters['lat'],
                $parameters['lon']
            );
        }

        return sprintf(
            '<img alt="Map of lead location" src="https://maps.googleapis.com/maps/api/staticmap?%s" style="%s">',
            http_build_query($query),
            'width:100%; height:auto;'
        );
    }
}
