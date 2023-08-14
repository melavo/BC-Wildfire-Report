@extends('layouts.front')

@section('content')
@include('partials.core.modal')

<!--<div class="container">
    <div class="card skin skin-square border-0">
        <div class="card-header on-filters">
            <h5>Login</h5>
        </div>
        <div class="card-body">
            <a href="{{ url('auth/github') }}" class="signup-btn ml-2"><strong>Github Login</strong></a>
        </div>
    </div>
</div>-->

<div class="page-wrapper homepage">
    
    <div class="container homepage">
    
        <div class="homepage-banner-text-wrapper">

            <h1>B.C Wild Fire Report</h1>

            <h2>Monitor Status of BC Wild Fire</h2>

            <p>Use your GitHub account to sign in to the B.C Wildfire Report.</p>

            <a href="{{ url('auth/github') }}" class="signup-btn ml-2"><strong><div class="magnifier-icon">&#9906;</div> Github Login</strong></a>
            
        </div>

    </div>
    
</div>


@endsection
