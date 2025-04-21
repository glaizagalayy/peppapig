<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Finance Dashboard')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/finance.css') }}">
  @stack('styles')
</head>
<body>

<div class="wrapper d-flex">
  <!-- Sidebar -->
  <nav id="sidebar" class="collapse d-md-block bg-white text-black sidebar" style="z-index: 999;">
    <div class="position-sticky pt-3">
      <!-- Logo -->
      <div class="text-center p-3 border-bottom">
        <img src="{{ asset('photos/pnlogo.png') }}" alt="Logo" class="logo" style="height: 40px;">
      </div>

      <!-- Navigation Links -->
      <ul class="nav flex-column mt-2">
        <li class="nav-item">
          <a href="{{ route('finance.financeDashboard') }}" class="nav-link @if(request()->routeIs('finance.financeDashboard')) active @endif">
            <i class="fas fa-tachometer-alt me-2"></i>
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('finance.financePayments') }}" class="nav-link @if(request()->routeIs('finance.financePayments')) active @endif">
            <i class="fas fa-credit-card me-2"></i>
            Payments
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('finance.financeReports') }}" class="nav-link @if(request()->routeIs('finance.financeReports')) active @endif">
            <i class="fas fa-chart-bar me-2"></i>
            Reports
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('finance.notifications') }}" class="nav-link @if(request()->routeIs('finance.notifications')) active @endif">
            <i class="fas fa-bell me-2"></i>
            Notifications
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
          <h1 class="title h4 mb-0">@yield('page-title', 'Finance Dashboard')</h1>
        </div>
        <div class="dropdown">
          <button class="btn btn-link dropdown-toggle text-white" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->profile_name }}
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item" href="{{ route('finance.profile') }}">Profile</a></li>
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

<!-- Mobile Overlay -->
<div id="overlay" class="overlay"></div>
<!-- Close Button for Mobile Sidebar -->
<button id="closeSidebar" class="close-sidebar-btn d-md-none">
  <i class="fas fa-times"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/financeSidebar.js') }}"></script>
@stack('scripts')

</body>
</html>
