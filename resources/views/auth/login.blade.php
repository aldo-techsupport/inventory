<x-guest-layout>
<div class="login-box">

  <!--begin::Logo-->
  <div class="login-logo">
    <a href="/"><img src="/adminlte/assets/img/AdminLTELogo.png" alt="Logo" width="40" class="me-2">
    <b>Inventory</b> Gudang</a>
  </div>
  <!--end::Logo-->

  <!--begin::Card-->
  <div class="card shadow-sm">
    <div class="card-body login-card-body">

      <p class="login-box-msg">Masuk untuk mengakses sistem</p>

      @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('status') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ $errors->first() }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!--begin::Email-->
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username" />
          <div class="input-group-text"><span class="bi bi-envelope"></span></div>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!--end::Email-->

        <!--begin::Password-->
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            placeholder="Password" required autocomplete="current-password" />
          <div class="input-group-text"><span class="bi bi-lock"></span></div>
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!--end::Password-->

        <!--begin::Remember + Submit-->
        <div class="row">
          <div class="col-7 d-flex align-items-center">
            <div class="form-check mb-0">
              <input class="form-check-input" type="checkbox" name="remember" id="remember_me" />
              <label class="form-check-label" for="remember_me">Ingat saya</label>
            </div>
          </div>
          <div class="col-5">
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
          </div>
        </div>
        <!--end::Remember + Submit-->

      </form>

    </div>
    <!--end::Card Body-->
  </div>
  <!--end::Card-->

</div>
<!--end::Login Box-->
</x-guest-layout>
