<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\VisitorLogs;
use App\Services\ApiOpenmaps;
use App\Helpers\Helper;
use Carbon\Carbon;
use View;
use function json_encode;
class DatafilterController extends BaseController 
{
    
    public function index(Request $request)
    {
        
        $logsArr = [
            'apiurl'    => '',
            'message'   => '',
            'status'    => '',
            'referer'   => $request->headers->get('referer'),
            'useragent' => $request->userAgent(),
            'ip'        => $request->ip(),
        ];
        
        $jsonData = [
            'draw' => (int)$request->get('draw'),
            'recordsTotal'=> 0,
            'recordsFiltered'=> 0,
            'data' => [],
        ];
        $apiFetch = new ApiOpenmaps();
        try {
            $search = (array)$request->get('search');
            $start = (int)$request->get('start', 0);
            $length = (int)$request->get('length',10);
            
            $ordering = $request->get('order', []);
            $columns = $request->get('columns', []);
            
            $paramFilter = ['count'=>$length,'startIndex'=>$start];
            
            foreach ($ordering as $orderDefinition) {
                if (isset($columns[$orderDefinition['column']])) {
                    $field = strtoupper($columns[$orderDefinition['column']]['data']);
                    if ($orderDefinition['dir'] == 'asc') {
                        $paramFilter['sortBy'] = $field.'+A';
                    } else {
                        $paramFilter['sortBy'] = $field.'+D';
                    }
                }
            }
            
            $filters = array_filter(
                (array)$request->get('filters', []),
                function ($value) {
                    return !is_null($value);
                }
            );
            $cql_filter = [];
            if (isset($filters['fire_status']) 
                && isset($filters['fire_status']['value'])) {
                
                $value = Helper::clean($filters['fire_status']['value']);
                if (strlen($value) > 0){
                    $operation = '=';
                    if ($filters['fire_status']['operation'] == 'NotEqual') {
                        $operation = '<>';
                    }
                    $cql_filter[] ="FIRE_STATUS{$operation}'{$value}'";
                }
            }
            
            if (isset($filters['fire_cause']) 
                && isset($filters['fire_cause']['value'])) {
                
                $value = Helper::clean($filters['fire_cause']['value']);
                if (strlen($value) > 0){
                    $operation = '=';
                    if ($filters['fire_cause']['operation'] == 'NotEqual') {
                        $operation = '<>';
                    }
                    $cql_filter[] ="FIRE_CAUSE{$operation}'{$value}'";
                }
            }
            
            if (isset($filters['geo_desc'])) {
                
                $value = Helper::cleanDesc($filters['geo_desc']);
                if (strlen($value) > 0){
                    
                    $pos = strpos($value, '$');
                    if ($pos === false) {
                        $value = "%25{$value}%25";
                    } else {
                        $value = str_replace("%","%25",$value);
                    }

                    
                    $cql_filter[] = "GEOGRAPHIC_DESCRIPTION LIKE '{$value}'";
                }
            }
            
            if (sizeof($cql_filter)){
                $whereCondion = ' AND ';
                if (isset($filters['condition_filters'])
                    && $filters['condition_filters']=='OR'){
                    $whereCondion = ' OR ';
                }
                $cql_filterStr = implode($whereCondion, $cql_filter);
                $paramFilter['cql_filter'] =  $cql_filterStr;
            }
            
            
            $dataFecth = $apiFetch->fetchResults($paramFilter);
            
            if (is_array($dataFecth) && isset($dataFecth['numberReturned']) && $dataFecth['numberReturned']>0){
                
                $jsonData['recordsTotal'] = $dataFecth['totalFeatures'];
                $jsonData['recordsFiltered'] = $dataFecth['numberMatched'];
                
                foreach ($dataFecth['features'] as $item){
                    $fireItem           = $item['properties'];
                    $jsonData['data'][] = [
                        "fire_number"               => View::exists('front.partials.column.fire_number')
                                                        ? view('front.partials.column.fire_number', ['fireItem' => $fireItem])->render()
                                                        : $fireItem['FIRE_NUMBER'],
                        "fire_year"                 => $fireItem['FIRE_YEAR'],
                        "response_type_desc"        => $fireItem['RESPONSE_TYPE_DESC'],
                        "ignition_date"             => Carbon::parse($fireItem['IGNITION_DATE'])->format('M, d Y'),
                        "fire_out_date"             => Carbon::parse($fireItem['FIRE_OUT_DATE'])->format('M, d Y'),
                        "fire_status"               => $fireItem['FIRE_STATUS'],
                        "fire_cause"                => $fireItem['FIRE_CAUSE'],
                        "fire_centre"               => $fireItem['FIRE_CENTRE'],
                        "zone"                      => $fireItem['ZONE'],
                        "fire_id"                   => $fireItem['FIRE_ID'],
                        "fire_type"                 => $fireItem['FIRE_TYPE'],
                        "incident_name"             => $fireItem['INCIDENT_NAME'],
                        "geographic_description"    => $fireItem['GEOGRAPHIC_DESCRIPTION'],
                        "latitude"                  => $fireItem['LATITUDE'],
                        "longitude"                 => $fireItem['LONGITUDE'],
                        "current_size"              => $fireItem['CURRENT_SIZE'],
                        "fire_url"                  => $fireItem['FIRE_URL'],
                        "feature_code"              => $fireItem['FEATURE_CODE'],
                        "objectid"                  => $fireItem['OBJECTID'],
                        "se_anno_cad_data"          => $fireItem['SE_ANNO_CAD_DATA'],
                        "location"                  => View::exists('front.partials.column.location')
                                                        ? view('front.partials.column.location', ['lat' => $fireItem['LATITUDE'],'lng'=>$fireItem['LONGITUDE'], 'fireItem' => $fireItem])->render()
                                                        : "{$fireItem['LATITUDE']}, {$fireItem['LONGITUDE']}",
                    ];
                }
            }
            $logsArr['message'] = '';
            $logsArr['status']  = 'OK';
        } catch (\Exception $e) {
            $logsArr['message'] = $e->getMessage();
            $logsArr['status']  = 'ERROR';
            app('log')->error("fetchAllResults", ['error'=>$e->getTraceAsString()]);
        }
        $logsArr['apiurl'] = $apiFetch->getCurrApiUrl();
        VisitorLogs::create($logsArr);
        
        return response()->json($jsonData, 200);
    }
}
