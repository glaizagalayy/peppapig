<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Finance Dashboard')</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <!-- DateRangePicker CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


  <!-- Custom Styles -->
  <style>
    :root {
      --primary-skyblue: #22BBEA;
      --sidebar-bg: #ffffff;
      --sidebar-hover: #e2f4fb;
      --text-primary: #343a40;
      --content-bg: #f1f6fb;
    }
    * { box-sizing: border-box; }
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--content-bg);
      margin: 0; padding: 0;
      overflow-x: hidden;
    }
    .wrapper { display: flex; min-height: 100vh; }
    /* Sidebar */
    .sidebar {
      width: 240px;
      background: var(--sidebar-bg);
      border-right: 1px solid #dee2e6;
      transform: translateX(-240px);
      transition: transform .3s ease;
      position: fixed; top: 0; left: 0; bottom: 0; z-index: 1000;
    }
    .sidebar.show { transform: translateX(0); }
    .sidebar .logo { height: 40px; margin: 1rem auto; display: block; }
    .sidebar .nav-link {
      color: var(--text-primary);
      padding: 10px 20px; margin: 4px 8px;
      border-radius: 8px;
      transition: background .3s, color .3s;
      display: flex; align-items: center;
      font-size: 0.95rem;
    }
    .sidebar .nav-link i { margin-right: 10px; }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active { background: var(--sidebar-hover); color: var(--primary-skyblue); }
    /* Main Content */
    .main-content { flex-grow: 1; margin-left: 0; transition: margin-left .3s ease; }
    .main-content.shift { margin-left: 240px; }
    /* Header */
    .header {
      background: var(--primary-skyblue);
      display: flex; align-items: center;
      padding: 0.75rem 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      position: relative; z-index: 900;
    }
    #sidebarToggle { background: none; border: none; color: #fff; font-size: 1.5rem; padding: 4px; }
    .title {
      flex: 1;
      color: #fff;
      font-weight: 700;
      text-align: center;
      margin: 0;
      font-size: 1.25rem;
    }
    .header-profile {
      position: relative;
      flex: 0;
      margin-left: auto;
    }
    .header-profile .dropdown-toggle {
      display: inline-flex;
      align-items: center;
      white-space: nowrap;
      color: #fff;
      font-weight: 500;
      padding: 4px 8px;
      background: transparent;
      border: none;
      font-size: 0.9rem;
    }
    .header-profile .dropdown-toggle:focus { outline: none; box-shadow: none; }
    .header-profile .dropdown-menu {
      position: absolute;
      top: 100%;
      right: 0;
      z-index: 2000;
      min-width: 150px;
      font-size: 0.9rem;
    }
    /* Content & sections */
    .content { padding: 24px; }
    .content-section {
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }
    /* Overlay for mobile */
    #overlay {
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.4);
      display: none; z-index: 950;
    }
    #overlay.show { display: block; }
    /* Mobile-specific styles */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }
        .main-content.shift {
            margin-left: 200px;
        }
        .header {
            padding: 0.5rem;
            flex-wrap: wrap;
        }
        .title {
            font-size: 1rem;
            margin: 0.25rem 0;
        }
        .header-profile .dropdown-toggle {
            font-size: 0.8rem;
            padding: 2px 6px;
        }
        .header-profile .dropdown-menu {
            min-width: 120px;
        }
        .content {
            padding: 16px;
        }
        .content-section {
            padding: 16px;
        }
        #sidebarToggle {
            font-size: 1.2rem;
        }
    }
    @media (max-width: 576px) {
        .sidebar .nav-link {
            font-size: 0.85rem;
            padding: 8px 16px;
        }
        .sidebar .logo {
            height: 32px;
            margin: 0.75rem auto;
        }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <!-- Sidebar -->
  <nav id="sidebar" class="sidebar">
    <img src="{{ asset('photos/pnlogo.png') }}" alt="Logo" class="logo">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a href="{{ route('finance.financeDashboard') }}" class="nav-link @if(request()->routeIs('finance.financeDashboard')) active @endif">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('finance.financePayments') }}" class="nav-link @if(request()->routeIs('finance.financePayments')) active @endif">
          <i class="fas fa-credit-card"></i> Payments
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('finance.financeReports') }}" class="nav-link @if(request()->routeIs('finance.financeReports')) active @endif">
          <i class="fas fa-chart-bar"></i> Reports
        </a>
      </li>
      {{-- <li class="nav-item">
        <a href="{{ route('finance.history') }}" class="nav-link @if(request()->routeIs('finance.history')) active @endif">
          <i class="fas fa-history"></i> Payment History
        </a>
      </li> --}}
      <li class="nav-item">
        <a href="{{ route('finance.notifications') }}" class="nav-link @if(request()->routeIs('finance.notifications')) active @endif">
          <i class="fas fa-bell"></i> Notifications
        </a>
      </li>
    </ul>
  </nav>
  <!-- Main Content -->
  <div id="mainContent" class="main-content">
    <header class="header">
      <button id="sidebarToggle"><i id="sidebarToggleIcon" class="fas fa-bars"></i></button>
      <h1 class="title h4 mb-0">@yield('page-title', 'Finance Dashboard')</h1>
      <div class="header-profile dropdown">
        <button class="dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          {{ Auth::user()->profile_name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="{{ route('finance.profile') }}">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item w-100 text-start">Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </header>
    <main class="content">
      <div class="content-section">
        @yield('content')
      </div>
    </main>
  </div>
</div>
<div id="overlay"></div>
<!-- Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');
  const overlay = document.getElementById('overlay');
  const toggleBtn = document.getElementById('sidebarToggle');
  const toggleIcon = document.getElementById('sidebarToggleIcon');
  function toggleSidebar() {
    const isOpen = sidebar.classList.toggle('show');
    overlay.classList.toggle('show', isOpen);
    mainContent.classList.toggle('shift', isOpen);
    toggleIcon.classList.toggle('fa-times', isOpen);
    toggleIcon.classList.toggle('fa-bars', !isOpen);
  }
  toggleBtn.addEventListener('click', toggleSidebar);
  overlay.addEventListener('click', toggleSidebar);
</script>
@yield('scripts')
@stack('scripts')
@stack('styles')
</body>
</html>