<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Maid Hiring Management System') }}</title>

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

        <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>
    

</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3" id="sidebar" style="flex-shrink: 0;">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo2.png') }}" alt="Admin Logo" width="180" class="img-fluid">
        </div>
        <div class="text-center mb-4">
        <h5>{{ Str::title(Auth::user()->name ?? 'admin@gmail.com') }}</h5>
        <small>Admin User</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a></li>
            <li class="nav-item">
                <a href="#categorySub" data-toggle="collapse" class="nav-link"><i class="fas fa-folder mr-2"></i> Category</a>
                <div id="categorySub" class="collapse {{ Route::is('category.*') ? 'show' : '' }}">
                    <a href="{{ route('category.create') }}" class="nav-link pl-4 {{ Route::is('category.create') ? 'active' : '' }}">Add Category</a>
                    <a href="{{ route('category.manage') }}" class="nav-link pl-4 {{ Route::is('category.manage') ? 'active' : '' }}">Manage Categories</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="#maidSub" data-toggle="collapse" class="nav-link"><i class="fas fa-user-friends mr-2"></i> Team Member</a>
                <div id="maidSub" class="collapse {{ Route::is('member.create', 'team-members.index') ? 'show' : '' }}">
                    <a href="{{ route('member.create') }}" class="nav-link pl-4 {{ Route::is('member.create') ? 'active' : '' }}">Add Team Member</a>
                    <a href="{{ route('team-members.index') }}" class="nav-link pl-4 {{ Route::is('team-members.index') ? 'active' : '' }}">View Team Member</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="#requestSub" data-toggle="collapse" class="nav-link"><i class="fas fa-tasks mr-2"></i>Request</a>
                <div id="requestSub" class="collapse {{ Route::is('admin.requests.*') ? 'show' : '' }}">
                    <a href="{{ route('admin.requests.new') }}" class="nav-link pl-4 {{ Route::is('admin.requests.new') ? 'active' : '' }}">New Requests</a>
                    <a href="{{ route('admin.requests.assigned') }}" class="nav-link pl-4 {{ Route::is('admin.requests.assigned') ? 'active' : '' }}">Assigned Requests</a>
                    <a href="{{ route('admin.requests.rejected') }}" class="nav-link pl-4 {{ Route::is('admin.requests.rejected') ? 'active' : '' }}">Rejected Requests</a>
                </div>
            </li>
         
            <li class="nav-item mt-4">
                <a class="nav-link text-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </li>
        </ul>
    </div>

    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <!-- Topbar with toggle -->
        <div class="p-3 d-flex justify-content-between align-items-center">
            <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
        </div>

        <!-- Page Content -->
        <main class="p-4">
            @yield('content')
        </main>
    </div>
</div>


</body>
</html>


@yield('scripts')