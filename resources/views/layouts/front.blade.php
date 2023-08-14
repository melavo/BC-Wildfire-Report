<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="description" content="">
        <meta name="author" content="Wayne">
        <title>BC Wildfire Report</title>
        
        <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="{{ asset('assets/vendor/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendor/css/datatables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style.css?ver=1') }}" rel="stylesheet">
        <script>
            var baseUrl  = '{{url('/')}}';
        </script>
    </head>
<body>
    <!-- ***** Preloader Start ***** -->
    {{-- <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
    </div> --}}
    <!-- ***** Preloader End ***** -->
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="/" class="logo">
                            <img src="assets/images/gov_bc_logo.svg" alt="BC Gov Logo">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="/" class="active">Home</a></li>
                            <li>
                                @if(auth()->check())
                                <a href="{{ url('logout') }}" class="logout-btn ml-2">
                                    <strong>Logout</strong>
                                @else
                                <a href="{{ url('auth/github') }}" class="signup-btn ml-2">
                                    <strong>Github Login</strong>
                                @endif</a>
                            </li>
                        </ul>   
                        <!--<a class='menu-trigger'>
                            <span>Menu</span>
                        </a>-->
                        <div class="header-mobile-btn-wrapper">
                            @if(auth()->check())
                            <a href="{{ url('logout') }}" class="logout-btn ml-2">
                                <strong>Logout</strong>
                            @else
                            <a href="{{ url('auth/github') }}" class="signup-btn ml-2">
                                <strong>Github Login</strong>
                            @endif</a>
                        </div>
                        
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->
    <main>
        @yield('content')
    </main>
    
    <footer>
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <p>Copyright © 2023. All rights reserved.</p>
            </div>
          </div>
        </div>
    </footer>
    
    @stack('footer')

    <script src="{{ asset('assets/vendor/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/base64.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js?ver=15') }}"></script>
</body>
</html>
