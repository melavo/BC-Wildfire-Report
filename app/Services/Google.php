<?php

namespace App\Services;

use App\Models\Project;
use App\Utility\Spatial\Objects\Point;
use Illuminate\Support\Facades\Config;

/**
 * Class Google
 * @package App\Integration
 */
class Google
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * RentalMan constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return env('GOOGLE_MAPS_PUBLIC_KEY');
    }

    /**
     * @param  string  $address
     * @param  string|null  $city
     * @param  string|null  $state
     * @param  string|int|null  $zip
     * @return array
     */
    public function geocode(string $address, string $city = null, string $state = null, $zip = null)
    {
        $search = $address;

        if (!empty($city)) {
            $search .= ', ' . $city;
        }

        if (!empty($state)) {
            $search .= ', ' . $state;
        }

        if (!empty($zip)) {
            $search .= ' ' . $zip;
        }

        $url = "https://maps.google.com/maps/api/geocode/json?key={$this->getPublicKey()}&address=" . urlencode($search);

        // get the decoded son response
        $response = json_decode(file_get_contents($url), true);

        if ($response['status'] !== 'OK') {
            throw new \RuntimeException(
                sprintf(
                    'An error occurred while geocoding "%s". Error:%s',
                    $search,
                    $response['error_message'] ?? json_encode($response)
                )
            );
        }

        return $response['results'][0]['geometry']['location'];
    }

    public function reverseGeocode($lat, $lon)
    {
        return '';
    }

    /**
     * @param  Project  $project
     */
    public function geocodeProject(Project $project)
    {
        if (!empty($project->address) && !empty($project->city) && !empty($project->state) && !empty($project->zip)) {
            $geocode = $this->geocode($project->address, $project->city, $project->state, $project->zip);

            $project->geo_lat = $geocode['lat'];
            $project->geo_lon = $geocode['lng'];

            $project->location = new Point($geocode['lat'], $geocode['lng']);
        }
    }
    
    /**
     * getAddressFromLatLng function
     *
     * @param float $lat
     * @param float $lng
     * @return string
     */
    public function getAddressFromLatLng(float $lat, float $lng) : string {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&sensor=false&key={$this->getPublicKey()}";

        // get the decoded son response
        $response = json_decode(file_get_contents($url), true);

        if ($response['status'] !== 'OK') {
            throw new \RuntimeException(
                sprintf(
                    'An error occurred while geocoding "%s". Error:%s',
                    $search,
                    $response['error_message'] ?? json_encode($response)
                )
            );
        }

        return $response['results'][0]['formatted_address'];
    }
}
