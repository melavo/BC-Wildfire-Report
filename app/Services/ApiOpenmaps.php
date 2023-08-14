<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Session;
/**
 * Class ApiOpenmaps
 * @package App\Services
 */
class ApiOpenmaps {

    /**
     * @var string $apiUrl
     */
    protected $apiUrl = 'https://openmaps.gov.bc.ca/geo/pub/ows';
    
    protected $service = 'WFS';
    protected $ver = '2.0.0';
    protected $request = 'GetFeature';
    protected $outputFormat = 'application/json';
    protected $typeName = 'pub:WHSE_LAND_AND_NATURAL_RESOURCE.PROT_CURRENT_FIRE_PNTS_SP';
    protected $apiFullUri;
    /**
     * ApiOpenmaps constructor.
     * @param  Repository  $config
     */
    public function __construct(){
        
    }
    
    public function fetchAllStatusAndCause(){
        
        if (Session::has('fire_status') && Session::has('fire_cause')) {
            return;
        }
        try {
            $queryParams = [
                'service' => $this->service,
                'version' => $this->ver,
                'request' => $this->request,
                'typeName' => $this->typeName,
                'outputFormat' => $this->outputFormat,
            ];
           
        
            $response = Http::retry(3, 100)->withQueryParameters($queryParams)->get($this->apiUrl);
            if ($response->ok()){
                $jsonRes = $response->json('features');
                if (is_array($jsonRes)){
                    $status = [];
                    $cause = [];
                    foreach($jsonRes as $item){
                        $fireItem = $item['properties'];
                        $status[$fireItem['FIRE_STATUS']] = $fireItem['FIRE_STATUS'];
                        $cause[$fireItem['FIRE_CAUSE']] = $fireItem['FIRE_CAUSE'];
                    }
                    
                    Session::put('fire_status',collect($status));
                    Session::put('fire_cause',collect($cause));
                }
            }
        } catch (\Exception $e) {
            app('log')->error("fetchAllStatusAndCause", ['error'=>$e->getTraceAsString()]);
        }
    }
    
    public function getCurrApiUrl(){
        return $this->apiFullUri;
    }
    
    /*
     * return array
     */
    public function fetchResults($params = []){
        try {
            $queryParams = [
                'service' => $this->service,
                'version' => $this->ver,
                'request' => $this->request,
                'typeName' => $this->typeName,
                'outputFormat' => $this->outputFormat,
            ];
            
            if (isset($params['count']) && $params['count']>0){
                $queryParams['count'] = $params['count'];
                
                if (isset($params['startIndex']) && $params['startIndex']>=0){
                    $queryParams['startIndex'] = $params['startIndex'];
                }
            }
            
            $this->apiFullUri = $this->apiUrl. '?' . http_build_query($queryParams);
            if (isset($params['sortBy']) && strlen($params['sortBy']) > 0){
                $this->apiFullUri =  $this->apiFullUri.'&sortBy='.$params['sortBy'];
            } 
            
            if (isset($params['cql_filter']) && strlen($params['cql_filter']) > 0){
                $this->apiFullUri = $this->apiFullUri.'&cql_filter='.$params['cql_filter'];
            }
            app('log')->info("filterUrl", ['filterUrl'=>$this->apiFullUri]);
            
            $response = Http::retry(3, 100)->get( $this->apiFullUri );
            if ($response->ok()){
                return $response->json();
            } else {
                app('log')->error("fetchAllResults", ['error'=>$response->body()]);
                throw new \Exception('Fetch API error handling.');
                return false;
            }
        } catch (\Exception $e) {
            app('log')->error("fetchAllResults", ['error'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage());
            return false;
        }
        
    }
}
