<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Maid Hiring Management System') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- External CSS Links -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Background Image Style -->
    <style>
        body {
            background-image: url('{{ asset('images/homeimg.avif') }}');
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand font-weight-bold" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Maid Hiring" width="180" class="d-inline-block align-top"> 
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
    <!-- Always Show Home -->
    <li class="nav-item">
        <a class="nav-link text-dark" href="{{ route('home') }}">Home</a>
    </li>
 
    <!-- Not Logged In -->
    @guest
        @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('login') }}">Login</a>
            </li>
        @endif

    @else
        <!-- If Logged In As USER -->
        @if(Auth::user()->role === 'user')
        <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('user.requests') }}">Request</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('profile') }}">Profile</a>
            </li>
            
        @endif
 @auth

 @if(Auth::user() && Auth::user()->role === 'user')
    <li class="nav-item">
        <a class="nav-link text-dark" href="{{ route('about') }}">About</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-dark" href="{{ route('contact') }}">Contact</a>
    </li>
    @endif

        <!-- Common for both user & admin -->
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
        @endauth
    @endguest
</ul>

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container_main mt-5">
        @yield('content')
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- External JS Files -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Toggle Form Script (if required somewhere in the page) -->
    <script>
        var btn = document.getElementById("postRequestBtn");
        var form = document.getElementById("requestForm");

        if (btn && form) {
            btn.onclick = function () {
                if (form.style.display === "none" || form.style.display === "") {
                    form.style.display = "block";
                    btn.innerText = "Hide Form";
                } else {
                    form.style.display = "none";
                    btn.innerText = "Post Request Here";
                }
            };
        }
    </script>

       <!-- ✅ Support for additional page-specific scripts like alert() -->
       @yield('scripts')
</body>
</html>
