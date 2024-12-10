<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Restaurant</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!-- Favicons -->
    {{--
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon"> --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/logo.svg') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{ asset('assets/img/logo.svg') }}" alt="logo">
                <span>.</span>
            </a>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li>
                        <a href="{{ route('home') }}"
                            class="{{Route::currentRouteName() == 'home' ? 'active': ''}}">Home<br></a>
                    </li>
                    <li>
                        <a href="{{ route('todayDeals') }}"
                            class="{{Route::currentRouteName() == 'todayDeals' ? 'active': ''}}">today deals</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}"
                            class="{{Route::currentRouteName() == 'about' ? 'active': ''}}">About</a>
                    </li>
                    <li>
                        <a href="{{ route('menu') }}"
                            class="{{Route::currentRouteName() == 'menu' ? 'active': ''}}">Menu</a>
                    </li>
                    <li>
                        <a href="{{ route('tableBooking') }}"
                            class="{{Route::currentRouteName() == 'tableBooking' ? 'active': ''}}">Table Booking</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}"
                            class="{{Route::currentRouteName() == 'contact' ? 'active': ''}}">Contact</a>
                    </li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            <a class="cart-bx" href="{{ route('addToCart') }}" data-aos="fade" data-aos-duration="1000">
                <span class="cart-count">2</span>
                <img src="{{ asset('assets/img/cart.png') }}" alt="cart">
            </a>
        </div>
    </header>