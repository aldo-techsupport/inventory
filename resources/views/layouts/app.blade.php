<!doctype html>
<html lang="id">
<!--begin::Head-->
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Inventory Gudang</title>

  <!--begin::Fonts-->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    crossorigin="anonymous" media="print" onload="this.media='all'" />
  <!--end::Fonts-->

  <!--begin::OverlayScrollbars-->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
    crossorigin="anonymous" />
  <!--end::OverlayScrollbars-->

  <!--begin::Bootstrap Icons-->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    crossorigin="anonymous" />
  <!--end::Bootstrap Icons-->

  <!--begin::Font Awesome-->
  <link rel="stylesheet" href="/assets/modules/fontawesome/css/all.min.css" />
  <!--end::Font Awesome-->

  <!--begin::AdminLTE-->
  <link rel="stylesheet" href="/adminlte/css/adminlte.min.css" />
  <!--end::AdminLTE-->

  <!--begin::Select2-->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!--end::Select2-->

  <!--begin::DataTables-->
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.4.1/css/dataTables.dateTime.min.css" />
  <!--end::DataTables-->

  <!--begin::Lottie-->
  <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
  <!--end::Lottie-->

  <style>
    /* =====================================================
       COMPATIBILITY LAYER — class lama tetap berfungsi
       di AdminLTE 4
    ====================================================== */

    /* section-header: judul halaman + tombol aksi */
    .section-header {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 8px;
      margin-bottom: 1.25rem;
    }
    .section-header h1 {
      font-size: 1.4rem;
      font-weight: 700;
      margin: 0;
      color: var(--bs-body-color);
    }
    .section-header .ml-auto {
      margin-left: auto !important;
    }
    @media (max-width: 576px) {
      .section-header { flex-direction: column; align-items: flex-start; }
      .section-header .ml-auto { margin-left: 0 !important; width: 100%; }
      .section-header .ml-auto .btn { width: 100%; }
      .section-header h1 { font-size: 1.15rem; }
    }

    /* card-statistic-1 (dashboard lama) */
    .card-statistic-1 {
      display: flex;
      align-items: center;
      background: var(--bs-body-bg);
      border-radius: 0.5rem;
      box-shadow: 0 2px 8px rgba(0,0,0,.06);
      transition: transform .25s, box-shadow .25s;
    }
    .card-statistic-1:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 18px rgba(0,0,0,.1);
    }
    .card-statistic-1 .card-icon {
      width: 68px; height: 68px;
      border-radius: 0.5rem;
      display: flex; align-items: center; justify-content: center;
      font-size: 26px; margin: 16px; color: #fff;
      background: var(--bs-primary);
    }
    .card-statistic-1 .card-wrap { flex: 1; padding: 8px 16px 8px 0; }
    .card-statistic-1 .card-header {
      border: none; background: transparent; padding: 0 0 2px;
    }
    .card-statistic-1 .card-header h4 {
      color: var(--bs-secondary-color); font-size: .9rem; margin: 0; font-weight: 500;
    }
    .card-statistic-1 .card-body {
      padding: 0; font-size: 1.75rem; font-weight: 700;
      color: var(--bs-body-color);
    }

    /* badge-warning lama */
    .badge-warning {
      background: #ffc107; color: #212529;
      border-radius: 0.375rem; padding: 4px 10px; font-weight: 600;
    }

    /* graph-card / stock-card lama */
    .graph-card, .stock-card {
      background: var(--bs-body-bg);
      border: 1px solid var(--bs-border-color);
      border-radius: 0.5rem;
      box-shadow: 0 2px 8px rgba(0,0,0,.05);
      padding: 20px 24px;
      margin-bottom: 1.25rem;
    }
    .graph-card h4, .stock-card h4 {
      font-size: 1rem; font-weight: 600; margin-bottom: 16px;
    }
    .graph-container { position: relative; width: 100%; height: 340px; }

    /* =====================================================
       END COMPATIBILITY LAYER
    ====================================================== */

    /* Page Loader */
    #page-loader {
      position: fixed;
      inset: 0;
      z-index: 99999;
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(6px);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity 0.4s ease, visibility 0.4s ease;
    }
    #page-loader.hide {
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
    }
    #page-loader-inner {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }
    #page-loader-text {
      font-size: 0.9rem;
      color: #0d6efd;
      font-weight: 600;
      letter-spacing: 0.5px;
      margin: 0;
      animation: pulse-text 1.2s ease-in-out infinite;
    }
    @keyframes pulse-text {
      0%, 100% { opacity: 1; }
      50%       { opacity: 0.4; }
    }

    /* Prevent modal flash */
    .modal:not(.show) { display: none !important; }
    .modal.show       { display: block !important; }

    /* Select2 fix inside AdminLTE 4 */
    .select2-container--default .select2-selection--single {
      height: calc(1.5em + 0.75rem + 2px);
      padding: 0.375rem 0.75rem;
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 1.5;
      color: #212529;
      padding: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 100%;
    }
  </style>
</head>
<!--end::Head-->

<!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

  <!--begin::Page Loader-->
  <div id="page-loader">
    <div id="page-loader-inner">
      <dotlottie-player
        src="https://lottie.host/1fbb029c-6a9b-456d-affe-2619a4a245cf/OYMAUYue59.lottie"
        background="transparent"
        speed="1"
        style="width:180px;height:180px;"
        autoplay loop>
      </dotlottie-player>
      <p id="page-loader-text">Memuat...</p>
    </div>
  </div>
  <!--end::Page Loader-->

  <!--begin::App Wrapper-->
  <div class="app-wrapper">

    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid">

        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">

          <!--begin::Fullscreen-->
          <li class="nav-item">
            <a class="nav-link" href="#" data-lte-toggle="fullscreen">
              <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
              <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none"></i>
            </a>
          </li>
          <!--end::Fullscreen-->

          <!--begin::User Menu-->
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img src="/adminlte/assets/img/user2-160x160.jpg"
                class="user-image rounded-circle shadow" alt="User" />
              <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <!--begin::User Image-->
              <li class="user-header text-bg-primary">
                <img src="/adminlte/assets/img/user2-160x160.jpg"
                  class="rounded-circle shadow" alt="User" />
                <p>
                  {{ auth()->user()->name }}
                  <small>{{ auth()->user()->email }}</small>
                </p>
              </li>
              <!--end::User Image-->
              <!--begin::Menu Footer-->
              <li class="user-footer">
                <a href="/ubah-password" class="btn btn-outline-secondary btn-sm">
                  <i class="bi bi-lock me-1"></i> Ubah Password
                </a>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm float-end"
                  onclick="event.preventDefault();
                    Swal.fire({
                      title: 'Konfirmasi Keluar',
                      text: 'Apakah Anda yakin ingin keluar?',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#0d6efd',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Ya, Keluar!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                      }
                    });">
                  <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
              <!--end::Menu Footer-->
            </ul>
          </li>
          <!--end::User Menu-->

        </ul>
        <!--end::End Navbar Links-->
      </div>
    </nav>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <a href="/" class="brand-link">
          <img src="/adminlte/assets/img/AdminLTELogo.png"
            alt="Logo" class="brand-image opacity-75 shadow" />
          <span class="brand-text fw-light">Inventory Gudang</span>
        </a>
      </div>
      <!--end::Sidebar Brand-->

      <!--begin::Sidebar Wrapper-->
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="navigation"
            aria-label="Main navigation"
            data-accordion="false">

            @php $user = auth()->user(); @endphp

            {{-- ===== SUPERADMIN: tampilkan semua menu ===== --}}
            @if ($user->isSuperAdmin())

              <li class="nav-item">
                <a href="/" class="nav-link {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer2"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <li class="nav-header">DATA MASTER</li>

              <li class="nav-item {{ Request::is('barang') || Request::is('jenis-barang') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('barang') || Request::is('jenis-barang') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-boxes"></i>
                  <p>Data Barang <i class="nav-arrow bi bi-chevron-right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="/barang" class="nav-link {{ Request::is('barang') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i><p>Nama Barang</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/jenis-barang" class="nav-link {{ Request::is('jenis-barang') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i><p>Jenis Barang</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item {{ Request::is('supplier') || Request::is('customer') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('supplier') || Request::is('customer') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-building"></i>
                  <p>Perusahaan <i class="nav-arrow bi bi-chevron-right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="/supplier" class="nav-link {{ Request::is('supplier') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i><p>Supplier</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/customer" class="nav-link {{ Request::is('customer') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i><p>Customer</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-header">TRANSAKSI</li>

              <li class="nav-item">
                <a href="/barang-masuk" class="nav-link {{ Request::is('barang-masuk') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-arrow-down-circle"></i><p>Barang Masuk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/barang-keluar" class="nav-link {{ Request::is('barang-keluar') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-arrow-up-circle"></i><p>Barang Keluar</p>
                </a>
              </li>

              <li class="nav-header">LAPORAN</li>

              <li class="nav-item">
                <a href="/laporan-stok" class="nav-link {{ Request::is('laporan-stok') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-file-earmark-text"></i><p>Stok</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/laporan-barang-masuk" class="nav-link {{ Request::is('laporan-barang-masuk') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-file-earmark-arrow-down"></i><p>Barang Masuk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/laporan-barang-keluar" class="nav-link {{ Request::is('laporan-barang-keluar') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-file-earmark-arrow-up"></i><p>Barang Keluar</p>
                </a>
              </li>

              <li class="nav-header">MANAJEMEN USER</li>

              <li class="nav-item">
                <a href="/data-pengguna" class="nav-link {{ Request::is('data-pengguna') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people"></i><p>Data Pengguna</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/hak-akses" class="nav-link {{ Request::is('hak-akses') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-shield-lock"></i><p>Hak Akses / Role</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/aktivitas-user" class="nav-link {{ Request::is('aktivitas-user') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-clock-history"></i><p>Aktivitas User</p>
                </a>
              </li>

            @else
              {{-- ===== NON-SUPERADMIN: berdasarkan permission ===== --}}

              @if ($user->canViewMenu('dashboard'))
              <li class="nav-item">
                <a href="/" class="nav-link {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p>
                </a>
              </li>
              @endif

              @php
                $showDataMaster = $user->canViewMenu('barang') || $user->canViewMenu('jenis-barang') || $user->canViewMenu('supplier') || $user->canViewMenu('customer');
                $showTransaksi  = $user->canViewMenu('barang-masuk') || $user->canViewMenu('barang-keluar');
                $showLaporan    = $user->canViewMenu('laporan-stok') || $user->canViewMenu('laporan-barang-masuk') || $user->canViewMenu('laporan-barang-keluar');
                $showManajemen  = $user->canViewMenu('data-pengguna') || $user->canViewMenu('hak-akses') || $user->canViewMenu('aktivitas-user');
              @endphp

              @if ($showDataMaster)
              <li class="nav-header">DATA MASTER</li>

                @if ($user->canViewMenu('barang') || $user->canViewMenu('jenis-barang'))
                <li class="nav-item {{ Request::is('barang') || Request::is('jenis-barang') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Request::is('barang') || Request::is('jenis-barang') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-boxes"></i>
                    <p>Data Barang <i class="nav-arrow bi bi-chevron-right"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    @if ($user->canViewMenu('barang'))
                    <li class="nav-item">
                      <a href="/barang" class="nav-link {{ Request::is('barang') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i><p>Nama Barang</p>
                      </a>
                    </li>
                    @endif
                    @if ($user->canViewMenu('jenis-barang'))
                    <li class="nav-item">
                      <a href="/jenis-barang" class="nav-link {{ Request::is('jenis-barang') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i><p>Jenis Barang</p>
                      </a>
                    </li>
                    @endif
                  </ul>
                </li>
                @endif

                @if ($user->canViewMenu('supplier') || $user->canViewMenu('customer'))
                <li class="nav-item {{ Request::is('supplier') || Request::is('customer') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Request::is('supplier') || Request::is('customer') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-building"></i>
                    <p>Perusahaan <i class="nav-arrow bi bi-chevron-right"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    @if ($user->canViewMenu('supplier'))
                    <li class="nav-item">
                      <a href="/supplier" class="nav-link {{ Request::is('supplier') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i><p>Supplier</p>
                      </a>
                    </li>
                    @endif
                    @if ($user->canViewMenu('customer'))
                    <li class="nav-item">
                      <a href="/customer" class="nav-link {{ Request::is('customer') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i><p>Customer</p>
                      </a>
                    </li>
                    @endif
                  </ul>
                </li>
                @endif
              @endif

              @if ($showTransaksi)
              <li class="nav-header">TRANSAKSI</li>
                @if ($user->canViewMenu('barang-masuk'))
                <li class="nav-item">
                  <a href="/barang-masuk" class="nav-link {{ Request::is('barang-masuk') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-arrow-down-circle"></i><p>Barang Masuk</p>
                  </a>
                </li>
                @endif
                @if ($user->canViewMenu('barang-keluar'))
                <li class="nav-item">
                  <a href="/barang-keluar" class="nav-link {{ Request::is('barang-keluar') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-arrow-up-circle"></i><p>Barang Keluar</p>
                  </a>
                </li>
                @endif
              @endif

              @if ($showLaporan)
              <li class="nav-header">LAPORAN</li>
                @if ($user->canViewMenu('laporan-stok'))
                <li class="nav-item">
                  <a href="/laporan-stok" class="nav-link {{ Request::is('laporan-stok') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-file-earmark-text"></i><p>Stok</p>
                  </a>
                </li>
                @endif
                @if ($user->canViewMenu('laporan-barang-masuk'))
                <li class="nav-item">
                  <a href="/laporan-barang-masuk" class="nav-link {{ Request::is('laporan-barang-masuk') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-file-earmark-arrow-down"></i><p>Barang Masuk</p>
                  </a>
                </li>
                @endif
                @if ($user->canViewMenu('laporan-barang-keluar'))
                <li class="nav-item">
                  <a href="/laporan-barang-keluar" class="nav-link {{ Request::is('laporan-barang-keluar') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-file-earmark-arrow-up"></i><p>Barang Keluar</p>
                  </a>
                </li>
                @endif
              @endif

              @if ($showManajemen)
              <li class="nav-header">MANAJEMEN USER</li>
                @if ($user->canViewMenu('data-pengguna'))
                <li class="nav-item">
                  <a href="/data-pengguna" class="nav-link {{ Request::is('data-pengguna') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-people"></i><p>Data Pengguna</p>
                  </a>
                </li>
                @endif
                @if ($user->canViewMenu('hak-akses'))
                <li class="nav-item">
                  <a href="/hak-akses" class="nav-link {{ Request::is('hak-akses') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-shield-lock"></i><p>Hak Akses / Role</p>
                  </a>
                </li>
                @endif
                @if ($user->canViewMenu('aktivitas-user'))
                <li class="nav-item">
                  <a href="/aktivitas-user" class="nav-link {{ Request::is('aktivitas-user') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-clock-history"></i><p>Aktivitas User</p>
                  </a>
                </li>
                @endif
              @endif

            @endif

          </ul>
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->

    </aside>
    <!--end::Sidebar-->

    <!--begin::App Main-->
    <main class="app-main">

      <!--begin::App Content Header-->
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">@yield('page-title', 'Dashboard')</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">@yield('page-title', 'Dashboard')</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!--end::App Content Header-->

      <!--begin::App Content-->
      <div class="app-content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
      <!--end::App Content-->

    </main>
    <!--end::App Main-->

    <!--begin::Footer-->
    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">
        <strong>Inventory Gudang</strong>
      </div>
      <strong>
        &copy; {{ date('Y') }} PT Vendora Solusi Digital dan Digital Tekno.
      </strong>
      All rights reserved.
    </footer>
    <!--end::Footer-->

  </div>
  <!--end::App Wrapper-->

  <!--begin::Scripts-->

  <!--begin::jQuery-->
  <script src="/assets/modules/jquery.min.js"></script>
  <!--end::jQuery-->

  <!--begin::OverlayScrollbars-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"></script>
  <!--end::OverlayScrollbars-->

  <!--begin::Popper + Bootstrap 5-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
    crossorigin="anonymous"></script>
  <!--end::Popper + Bootstrap 5-->

  <!--begin::AdminLTE-->
  <script src="/adminlte/js/adminlte.min.js"></script>
  <!--end::AdminLTE-->

  <!--begin::OverlayScrollbars Init-->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector('.sidebar-wrapper');
      const isMobile = window.innerWidth <= 992;
      if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined && !isMobile) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true },
        });
      }
    });
  </script>
  <!--end::OverlayScrollbars Init-->

  <!--begin::Select2-->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!--end::Select2-->

  <!--begin::DataTables-->
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <!--end::DataTables-->

  <!--begin::SweetAlert2-->
  @include('sweetalert::alert')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!--end::SweetAlert2-->

  <!--begin::DayJS-->
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
  <!--end::DayJS-->

  <!--begin::Page Loader Script-->
  <script>
    window.addEventListener('load', function () {
      const loader = document.getElementById('page-loader');
      if (loader) {
        loader.classList.add('hide');
        setTimeout(() => loader.remove(), 450);
      }
    });

    document.addEventListener('click', function (e) {
      const link = e.target.closest('a');
      if (
        link && link.href &&
        !link.href.startsWith('#') &&
        !link.href.startsWith('javascript') &&
        link.getAttribute('href') !== '#' &&
        !link.hasAttribute('data-bs-toggle') &&
        !link.hasAttribute('data-lte-toggle') &&
        link.target !== '_blank' &&
        !e.ctrlKey && !e.metaKey &&
        link.hostname === window.location.hostname
      ) {
        const loader = document.createElement('div');
        loader.id = 'page-loader';
        loader.innerHTML = `
          <div id="page-loader-inner">
            <dotlottie-player
              src="https://lottie.host/1fbb029c-6a9b-456d-affe-2619a4a245cf/OYMAUYue59.lottie"
              background="transparent" speed="1"
              style="width:180px;height:180px;" autoplay loop>
            </dotlottie-player>
            <p id="page-loader-text">Memuat...</p>
          </div>`;
        document.body.appendChild(loader);
      }
    });
  </script>
  <!--end::Page Loader Script-->

  @stack('scripts')

  <!--end::Scripts-->
</body>
<!--end::Body-->
</html>
