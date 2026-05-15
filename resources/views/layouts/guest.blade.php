<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ config('app.name', 'Inventory Gudang') }}</title>

  <!--begin::Bootstrap Icons-->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    crossorigin="anonymous" />
  <!--end::Bootstrap Icons-->

  <!--begin::AdminLTE-->
  <link rel="stylesheet" href="/adminlte/css/adminlte.min.css" />
  <!--end::AdminLTE-->
</head>
<body class="login-page bg-body-secondary">

  {{ $slot }}

  <!--begin::Scripts-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
    crossorigin="anonymous"></script>
  <script src="/adminlte/js/adminlte.min.js"></script>
  <!--end::Scripts-->

</body>
</html>
