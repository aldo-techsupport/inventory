@extends('layouts.app')

@section('content')
<style>
  .permission-card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 4px 18px rgba(110, 126, 255, 0.08);
  }
  .group-header {
    background: linear-gradient(90deg, #f0f3ff, #e8ecff);
    border-left: 4px solid #6e7eff;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 700;
    color: #3a3a5c;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 4px;
  }
  .permission-row {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-radius: 10px;
    transition: background 0.2s;
    border-bottom: 1px solid #f0f2ff;
  }
  .permission-row:last-child {
    border-bottom: none;
  }
  .permission-row:hover {
    background: #f8f9ff;
  }
  .menu-label {
    flex: 1;
    font-weight: 500;
    color: #444;
    font-size: 0.92rem;
  }
  .menu-label i {
    color: #8c9aff;
    margin-right: 8px;
    width: 18px;
    text-align: center;
  }
  .permission-checks {
    display: flex;
    gap: 24px;
    align-items: center;
  }
  .check-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    min-width: 70px;
  }
  .check-item label {
    font-size: 0.75rem;
    color: #888;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin: 0;
  }
  /* Custom checkbox */
  .custom-check {
    width: 22px;
    height: 22px;
    cursor: pointer;
    accent-color: #6e7eff;
  }
  .check-view .custom-check {
    accent-color: #54c8e8;
  }
  .check-add .custom-check {
    accent-color: #6e7eff;
  }
  .badge-role {
    background: linear-gradient(135deg, #6e73ff, #8c9aff);
    color: #fff;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 0.85rem;
    font-weight: 600;
  }
  .legend-box {
    display: flex;
    gap: 20px;
    align-items: center;
    background: #f8f9ff;
    border-radius: 10px;
    padding: 10px 16px;
    margin-bottom: 20px;
    font-size: 0.82rem;
    color: #666;
  }
  .legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 3px;
  }
  .dot-view { background: #54c8e8; }
  .dot-add  { background: #6e7eff; }
  .select-all-row {
    background: #f0f3ff;
    border-radius: 10px;
    padding: 10px 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .select-all-row label {
    font-weight: 600;
    color: #555;
    font-size: 0.85rem;
    margin: 0;
    cursor: pointer;
  }
</style>

<div class="section-header">
  <h1>Atur Hak Akses</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item"><a href="/hak-akses">Hak Akses</a></div>
    <div class="breadcrumb-item active">Atur Akses: <span class="badge-role ml-2">{{ $role->role }}</span></div>
  </div>
</div>

<div class="row">
  <div class="col-lg-10 col-xl-8 mx-auto">

    <div class="card permission-card">
      <div class="card-header d-flex align-items-center justify-content-between" style="border-bottom: 1px solid #eef0ff; padding: 18px 24px;">
        <div>
          <h5 class="mb-0" style="font-weight:700; color:#2c2c4a;">
            <i class="fa fa-user-shield mr-2" style="color:#6e7eff;"></i>
            Pengaturan Akses Menu
          </h5>
          <small class="text-muted">Role: <strong>{{ $role->role }}</strong>
            @if($role->deskripsi) — {{ $role->deskripsi }} @endif
          </small>
        </div>
        <a href="/hak-akses" class="btn btn-sm btn-outline-secondary">
          <i class="fa fa-arrow-left mr-1"></i> Kembali
        </a>
      </div>

      <div class="card-body" style="padding: 24px;">

        <!-- Legend -->
        <div class="legend-box">
          <strong style="color:#444;">Keterangan:</strong>
          <div class="legend-item">
            <div class="legend-dot dot-view"></div>
            <span><strong>Lihat</strong> — Bisa mengakses & melihat halaman</span>
          </div>
          <div class="legend-item">
            <div class="legend-dot dot-add"></div>
            <span><strong>Tambah/Edit/Hapus</strong> — Bisa melakukan aksi (otomatis aktifkan Lihat)</span>
          </div>
        </div>

        <form action="{{ route('hak-akses.save-permissions', $role->id) }}" method="POST" id="form-permissions">
          @csrf

          <!-- Select All -->
          <div class="select-all-row">
            <span style="font-weight:600; color:#555; font-size:0.85rem;"><i class="fa fa-check-double mr-1" style="color:#6e7eff;"></i> Pilih Semua:</span>
            <div class="d-flex gap-3 align-items-center" style="gap:16px; display:flex;">
              <label class="d-flex align-items-center" style="gap:6px; cursor:pointer; margin:0;">
                <input type="checkbox" id="select_all_view" class="custom-check" style="accent-color:#54c8e8;">
                <span style="font-size:0.82rem; color:#666;">Semua Lihat</span>
              </label>
              <label class="d-flex align-items-center" style="gap:6px; cursor:pointer; margin:0;">
                <input type="checkbox" id="select_all_add" class="custom-check" style="accent-color:#6e7eff;">
                <span style="font-size:0.82rem; color:#666;">Semua Tambah/Edit/Hapus</span>
              </label>
            </div>
          </div>

          @php
            $groups = [];
            foreach ($menuList as $key => $menu) {
                $groups[$menu['group']][$key] = $menu;
            }
            $groupIcons = [
                'Umum'           => 'fas fa-home',
                'Data Master'    => 'fas fa-database',
                'Transaksi'      => 'fas fa-exchange-alt',
                'Laporan'        => 'fas fa-file-alt',
                'Manajemen User' => 'fas fa-users-cog',
            ];
            $menuIcons = [
                'dashboard'             => 'fas fa-tachometer-alt',
                'barang'                => 'fas fa-box',
                'jenis-barang'          => 'fas fa-tags',
                'satuan-barang'         => 'fas fa-ruler',
                'supplier'              => 'fas fa-truck',
                'customer'              => 'fas fa-user-tie',
                'barang-masuk'          => 'fas fa-arrow-circle-down',
                'barang-keluar'         => 'fas fa-arrow-circle-up',
                'laporan-stok'          => 'fas fa-chart-bar',
                'laporan-barang-masuk'  => 'fas fa-file-import',
                'laporan-barang-keluar' => 'fas fa-file-export',
                'data-pengguna'         => 'fas fa-users',
                'hak-akses'             => 'fas fa-user-lock',
                'aktivitas-user'        => 'fas fa-list-alt',
            ];
          @endphp

          @foreach ($groups as $groupName => $menus)
          <div class="mb-4">
            <div class="group-header">
              <i class="{{ $groupIcons[$groupName] ?? 'fas fa-circle' }} mr-2"></i>
              {{ $groupName }}
            </div>

            <div style="background:#fff; border-radius:10px; border:1px solid #eef0ff; overflow:hidden;">
              @foreach ($menus as $menuKey => $menu)
              @php
                $isView = $currentPermissions[$menuKey]['can_view'] ?? false;
                $isAdd  = $currentPermissions[$menuKey]['can_add'] ?? false;
              @endphp
              <div class="permission-row">
                <div class="menu-label">
                  <i class="{{ $menuIcons[$menuKey] ?? 'fas fa-circle' }}"></i>
                  {{ $menu['label'] }}
                </div>
                <div class="permission-checks">
                  <div class="check-item check-view">
                    <label for="view_{{ $menuKey }}">Lihat</label>
                    <input
                      type="checkbox"
                      class="custom-check cb-view"
                      id="view_{{ $menuKey }}"
                      name="permissions[{{ $menuKey }}][can_view]"
                      value="1"
                      data-key="{{ $menuKey }}"
                      {{ $isView ? 'checked' : '' }}
                    >
                  </div>
                  <div class="check-item check-add">
                    <label for="add_{{ $menuKey }}">Tambah/Edit/Hapus</label>
                    <input
                      type="checkbox"
                      class="custom-check cb-add"
                      id="add_{{ $menuKey }}"
                      name="permissions[{{ $menuKey }}][can_add]"
                      value="1"
                      data-key="{{ $menuKey }}"
                      {{ $isAdd ? 'checked' : '' }}
                    >
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach

          <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid #eef0ff;">
            <a href="/hak-akses" class="btn btn-secondary">
              <i class="fa fa-times mr-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary px-4" id="btn-save">
              <i class="fa fa-save mr-1"></i> Simpan Hak Akses
            </button>
          </div>
        </form>

      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {

  // Jika "Tambah/Edit/Hapus" dicentang → otomatis centang "Lihat"
  $(document).on('change', '.cb-add', function () {
    const key = $(this).data('key');
    if ($(this).is(':checked')) {
      $(`#view_${key}`).prop('checked', true);
    }
  });

  // Jika "Lihat" di-uncheck → otomatis uncheck "Tambah/Edit/Hapus"
  $(document).on('change', '.cb-view', function () {
    const key = $(this).data('key');
    if (!$(this).is(':checked')) {
      $(`#add_${key}`).prop('checked', false);
    }
  });

  // Select All View
  $('#select_all_view').on('change', function () {
    const checked = $(this).is(':checked');
    $('.cb-view').prop('checked', checked);
    if (!checked) {
      $('.cb-add').prop('checked', false);
    }
  });

  // Select All Add
  $('#select_all_add').on('change', function () {
    const checked = $(this).is(':checked');
    $('.cb-add').prop('checked', checked);
    if (checked) {
      $('.cb-view').prop('checked', true);
    }
  });

  // Sinkronisasi state select-all saat ada perubahan individual
  function syncSelectAll() {
    const allView = $('.cb-view').length === $('.cb-view:checked').length;
    const allAdd  = $('.cb-add').length  === $('.cb-add:checked').length;
    $('#select_all_view').prop('checked', allView);
    $('#select_all_add').prop('checked', allAdd);
  }
  $(document).on('change', '.cb-view, .cb-add', syncSelectAll);
  syncSelectAll();

  // Konfirmasi sebelum simpan
  $('#form-permissions').on('submit', function (e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
      title: 'Simpan Hak Akses?',
      text: 'Perubahan hak akses akan langsung berlaku untuk semua pengguna dengan role ini.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Simpan!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#6e7eff',
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });

});
</script>
@endpush
@endsection
