<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  @stack('styles')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="wrapper d-flex">
  <!-- Sidebar -->
  <nav id="sidebar" class="collapse d-md-block bg-white text-black sidebar" style="z-index: 999;">
    <div class="position-sticky pt-3">
      <!-- Sidebar Header (Logo) -->
      <div class="text-center p-3 border-bottom">
        <img src="{{ asset('photos/pnlogo.png') }}" alt="Logo" class="logo" style="height: 40px;">
      </div>
      <!-- Navigation Links -->
      <ul class="nav flex-column mt-2">
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
            <i class="fas fa-tachometer-alt me-2"></i>
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.manageUsers') }}" class="nav-link @if(request()->routeIs('admin.manageUsers')) active @endif">
            <i class="fas fa-users me-2"></i>
            Manage Users
          </a>
        </li>
        <li class="nav-item">
          {{--<a href="{{ route('admin.transactions') }}" class="nav-link @if(request()->routeIs('admin.transactions')) active @endif"> --}}
            <i class="fas fa-file-invoice-dollar me-2"></i>
            Transactions
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content w-100">
    <header class="header shadow-sm p-3" style="background-color: #22BBEA;">
      <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <!-- Mobile Menu Toggle Button (Burger Icon) -->
          <button class="btn d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="title h4 mb-0">@yield('page-title', 'Admin Dashboard')</h1>
        </div>
        <div class="dropdown">
          <button class="btn btn-link dropdown-toggle text-white" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->profile_name }}
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            {{-- <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li> --}}
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Logout</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </header>
    
    <main class="content p-4">
      @yield('content')
    </main>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/adminSidebar.js') }}"></script>
@stack('scripts')

</body>
</html>