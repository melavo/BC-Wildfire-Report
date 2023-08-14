<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Services\ApiOpenmaps;
use App\Models\Properties;
use App\Helpers\Helper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportFires;
use App\Models\VisitorLogs;
use function base64_decode;
class HomeController extends Controller
{
    public function index(){
        $resultReturn = [];
        $dataApi = new ApiOpenmaps();
        $dataApi->fetchAllStatusAndCause();
        
        return view('front.home.index');
    }
    public function export(Request $request){
        
        $downloadType =  $request->dltype;
        
        $logsArr = [
            'apiurl'    => '',
            'message'   => '',
            'status'    => '',
            'referer'   => $request->headers->get('referer'),
            'useragent' => $request->userAgent(),
            'ip'        => $request->ip(),
        ];
        $apiFetch = new ApiOpenmaps();
        try {
            $exportModel = new ExportFires();
            
            $exportModel->fireCollection = new Collection();
            $paramFilter = [];
            if ($downloadType == 'filter'){
                
                $df = $request->query('df');
                if ($df){
                    $df = base64_decode($df);
                    if ($df){
                        $filters = json_decode($df,true);
                       
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
                    }
                }
            } 
            
            $dataFecth = $apiFetch->fetchResults($paramFilter);
            if (is_array($dataFecth) && isset($dataFecth['numberReturned']) && $dataFecth['numberReturned']>0){
                foreach ($dataFecth['features'] as $item){
                    if (!$exportModel->headings){
                        $exportModel->headings = array_keys($item['properties']);
                    }
                    $exportModel->fireCollection->push((object)$item['properties']);
                }
            }
            $logsArr['message'] = 'DOWNLOAD';
            $logsArr['status']  = 'OK';
        } catch (\Exception $e) {
            $logsArr['message'] = $e->getMessage();
            $logsArr['status']  = 'ERROR';
            app('log')->error("export", ['error'=>$e->getTraceAsString()]);
        }
        
        $logsArr['apiurl'] = $apiFetch->getCurrApiUrl();
        VisitorLogs::create($logsArr);
        
        return Excel::download($exportModel, 'fire-properties.csv', \Maatwebsite\Excel\Excel::CSV, [
              'Content-Type' => 'text/csv',
        ]);
    }
}
