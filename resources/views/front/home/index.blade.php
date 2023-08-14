@extends('layouts.front')

@section('content')
@include('partials.core.modal')

<div class="container">
    <div class="card skin skin-square border-0">
        <div class="card-header on-filters">
            <div class="row">
                <div class="col-6">
                    <h1 class="page-title">BC Wild Fire Report</h1>
                    <h5 class="filter-title">Filter By:</h5>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <div class="float-start">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" id="inputGroup-geo-desc-sm">Fire Cause</span>
                            <select class="form-control status-dropdown" id="operation_fire_cause">
                                <option value="Equal">Equal</option>
                                <option value="NotEqual">Not Equal</option>
                            </select>
                            <select class="form-control status-dropdown" id="select_fire_cause">
                                <option value="">Select</option>
                                @if (session()->has('fire_cause'))
                                    @foreach (Session::get('fire_cause') as $fire_cause)
                                        <option value="{{$fire_cause}}">{{$fire_cause}}</option>
                                    @endforeach
                                @endif
                            </select>
                          
                        </div>
                    </div>
                    <div class="float-start ms-5">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" id="inputGroup-geo-desc-sm">Fire Status</span>
                            <select class="form-control status-dropdown" id="operation_fire_status">
                                <option value="Equal">Equal</option>
                                <option value="NotEqual">Not Equal</option>
                            </select>
                            <select class="form-control status-dropdown" id="select_fire_status">
								<option value="">Select</option>
                                @if (session()->has('fire_status'))
                                    @foreach (Session::get('fire_status') as $fire_status)
                                        <option value="{{$fire_status}}">{{$fire_status}}</option>
                                    @endforeach
                                @endif
							</select>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group input-group-sm mb-3 float-end autocomplete">
                        <span class="input-group-text" id="inputGroup-geo-desc-sm">Geographical Description</span>
                        <input type="text" class="form-control" onClick="this.setSelectionRange(0, this.value.length)" name="filter_geo_desc" id="filter_geo_desc" aria-describedby="inputGroup-geo-desc-sm">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="float-end">
                        <div class="input-group input-group-sm mb-3 float-end">
                            <select class="form-control status-dropdown" id="condition_filters">
                                <option value="AND">Condition AND</option>
                                <option value="OR">Condition OR</option>
                            </select>
                            <button type="button" id="apply-filters" class="btn btn-dark btn-sm  ms-3">Apply</button>
                            <button type="button" id="clear-all-filters" class="btn btn-danger btn-sm ms-3">Clear All Filters</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="table_properties" class="table table-striped display" style="width:100%">
                <thead>
                    <tr>
                        <th>NUMBER</th>
                        <th>IGNITION DATE</th>
                        <th>FIREOUT DATE</th>
                        <th>STATUS</th>
                        <th>CAUSE</th>
                        <th>INCIDENT NAME</th>
                        <th>GEOGRAPHICAL DESCRIPTION</th>
                        <th class="no-sort">LOCATION</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
