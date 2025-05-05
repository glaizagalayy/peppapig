<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Student Dashboard')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/payment-history.css') }}">
 
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
    .sidebar {
      width: 240px;
      background: var(--sidebar-bg);
      border-right: 1px solid #dee2e6;
      transform: translateX(-240px);
      transition: transform .3s ease;
      position: fixed; top: 0; left: 0; bottom: 0; z-index: 1000;
    }
    .sidebar.show { transform: translateX(0); }
    .sidebar .logo { height: 50px; margin: 1rem auto; display: block; }
    .sidebar .nav-link {
      color: var(--text-primary);
      padding: 12px 20px; margin: 4px 8px;
      border-radius: 8px;
      transition: background .3s ease, color .3s ease;
      display: flex; align-items: center;
    }
    .sidebar .nav-link i { margin-right: 12px; }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active { background: var(--sidebar-hover); color: var(--primary-skyblue); }
    .main-content { flex-grow: 1; margin-left: 0; transition: margin-left .3s ease; }
    .main-content.shift { margin-left: 240px; }
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
    }
    .header-profile {
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
    }
    .header-profile .dropdown-toggle:focus { outline: none; box-shadow: none; }
    .content { padding: 24px; }
    .content-section {
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }
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
            height: 40px;
            margin: 0.75rem auto;
        }
        .header-profile .dropdown-menu {
            min-width: 120px;
        }
    }
</style>
</head>
<body>
<div class="wrapper">
  <nav id="sidebar" class="sidebar">
    <img src="{{ asset('photos/pnlogo.png') }}" alt="Logo" class="logo">
    <ul class="nav flex-column mt-3">
      <li class="nav-item">
        <a href="{{ route('student.studentDashboard') }}" class="nav-link @if(request()->routeIs('student.studentDashboard')) active @endif">
          <i class="fas fa-tachometer-alt"></i>Dashboard</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('student.studentPayments') }}" class="nav-link @if(request()->routeIs('student.studentPayments')) active @endif">
          <i class="fas fa-credit-card"></i>Payments</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('student.paymentForm') }}" class="nav-link @if(request()->routeIs('student.paymentForm.blade')) active @endif">
          <i class="fas fa-upload"></i>Proof of Payment</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('student.notifications') }}" class="nav-link @if(request()->routeIs('student.notifications')) active @endif">
          <i class="fas fa-bell"></i>Notifications</a>
      </li>
    </ul>
  </nav>

  <div id="mainContent" class="main-content">
    <header class="header">
      <button id="sidebarToggle"><i id="sidebarToggleIcon" class="fas fa-bars"></i></button>
      <h1 class="title h5 mb-0">@yield('page-title', 'Student Dashboard')</h1>
      <div class="header-profile dropdown">
        <button class="dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          {{ Auth::user()->profile_name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="{{ route('student.profile') }}">Profile</a></li>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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