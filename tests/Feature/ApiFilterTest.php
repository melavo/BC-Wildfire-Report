<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use App\Services\ApiOpenmaps;
class ApiFilterTest extends TestCase
{
    public function test_apiopenmaps(): void
    {
        $data = new ApiOpenmaps();
        $res = $data->fetchResults();
        $this->assertArrayHasKey('features',$res);
    }
    
    public function test_api_unauthencation(): void
    {
        $this->json('post', 'api/v1/filter', [])
         ->assertStatus(401)
         ->assertJsonStructure(['message']);
    }
    
    public function test_api_authencation_getall(): void
    {
        $user = new User();
        $user->id = 1;
        $payload = [
        ];
        $this->actingAs($user)
            ->json('post', 'api/v1/filter', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'draw',
                'recordsTotal',
                'recordsFiltered',
                'data' => [
                     '*' => [
                        "fire_number",
                        "fire_year",
                        "response_type_desc",
                        "ignition_date",
                        "fire_out_date",
                        "fire_status",
                        "fire_cause",
                        "fire_centre",
                        "zone",
                        "fire_id",
                        "fire_type",
                        "incident_name",
                        "geographic_description",
                        "latitude",
                        "longitude",
                        "current_size",
                        "fire_url",
                        "feature_code",
                        "objectid",
                        "se_anno_cad_data",
                        "location",
                     ]
                 ]
             ]);
    }
    
    public function test_api_authencation_filter(): void
    {
        $user = new User();
        $user->id = 1;
        $payload = [
            'filters' =>[
                'fire_status'=>[
                    'value' =>'Out',
                    'operation' =>'NotEqual'
                ],
                'fire_cause'=>[
                    'value' =>'Person',
                    'operation' =>'NotEqual'
                ],
                'condition_filters' =>'AND'
            ]
        ];
        $this->actingAs($user)
            ->json('post', 'api/v1/filter', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'draw',
                'recordsTotal',
                'recordsFiltered',
                'data' => [
                     '*' => [
                        "fire_number",
                        "fire_year",
                        "response_type_desc",
                        "ignition_date",
                        "fire_out_date",
                        "fire_status",
                        "fire_cause",
                        "fire_centre",
                        "zone",
                        "fire_id",
                        "fire_type",
                        "incident_name",
                        "geographic_description",
                        "latitude",
                        "longitude",
                        "current_size",
                        "fire_url",
                        "feature_code",
                        "objectid",
                        "se_anno_cad_data",
                        "location",
                     ]
                 ]
             ]);
    }
}
