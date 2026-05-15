{{-- ===================== MODAL TAMBAH PENGGUNA (Native CSS/JS) ===================== --}}

<style>
  /* ===== OVERLAY ===== */
  #modal_tambah_pengguna {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
    background: rgba(0, 0, 0, 0.45);
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  #modal_tambah_pengguna.active {
    display: flex;
  }

  /* ===== DIALOG ===== */
  #modal_tambah_pengguna .modal-box {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    animation: pgIn 0.22s ease;
  }
  @keyframes pgIn {
    from { transform: translateY(-16px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  /* ===== HEADER ===== */
  #modal_tambah_pengguna .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_pengguna .modal-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f1f33;
  }
  #modal_tambah_pengguna .btn-close-modal {
    background: none;
    border: none;
    font-size: 22px;
    line-height: 1;
    color: #888;
    cursor: pointer;
    padding: 0 4px;
  }
  #modal_tambah_pengguna .btn-close-modal:hover {
    color: #333;
  }

  /* ===== BODY ===== */
  #modal_tambah_pengguna .modal-body {
    padding: 20px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
  }

  /* ===== FORM ===== */
  #modal_tambah_pengguna .form-group {
    margin-bottom: 16px;
  }
  #modal_tambah_pengguna label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #444;
    margin-bottom: 6px;
  }
  #modal_tambah_pengguna input,
  #modal_tambah_pengguna select {
    width: 100%;
    padding: 10px 12px;
    font-size: 15px;       /* 15px+ mencegah iOS auto-zoom */
    font-family: inherit;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.2s;
    -webkit-appearance: none;
    appearance: none;
    background: #fff;
    color: #333;
  }
  #modal_tambah_pengguna input:focus,
  #modal_tambah_pengguna select:focus {
    border-color: #6e7eff;
    box-shadow: 0 0 0 3px rgba(110, 126, 255, 0.15);
  }

  /* Arrow custom untuk select — menggantikan default browser/Bootstrap */
  #modal_tambah_pengguna select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23888' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
  }

  /* ===== ERROR ===== */
  #modal_tambah_pengguna .alert-error {
    display: none;
    margin-top: 6px;
    padding: 8px 12px;
    background: #fff0f0;
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    color: #842029;
    font-size: 13px;
  }
  #modal_tambah_pengguna .alert-error.show {
    display: block;
  }

  /* ===== FOOTER ===== */
  #modal_tambah_pengguna .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 20px;
    border-top: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_pengguna .btn-cancel {
    padding: 9px 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f5f5f5;
    color: #555;
    font-size: 14px;
    cursor: pointer;
    font-family: inherit;
  }
  #modal_tambah_pengguna .btn-cancel:hover {
    background: #eaeaea;
  }
  #modal_tambah_pengguna .btn-submit {
    padding: 9px 22px;
    border: none;
    border-radius: 8px;
    background: #4f46e5;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.2s;
  }
  #modal_tambah_pengguna .btn-submit:hover {
    background: #4338ca;
  }
  #modal_tambah_pengguna .btn-submit:disabled {
    background: #a5b4fc;
    cursor: not-allowed;
  }
</style>

<div id="modal_tambah_pengguna" role="dialog" aria-modal="true" aria-labelledby="modal_pg_title">
  <div class="modal-box">

    <div class="modal-header">
      <h5 id="modal_pg_title">Tambah Pengguna</h5>
      <button type="button" class="btn-close-modal" id="cancel_modal_tambah_pengguna" aria-label="Tutup">&times;</button>
    </div>

    <div class="modal-body">
      <div class="form-group">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" placeholder="Masukkan nama pengguna...">
        <div class="alert-error" id="alert-name"></div>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Masukkan email..."
               autocomplete="off" autocapitalize="none">
        <div class="alert-error" id="alert-email"></div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan password..."
               autocomplete="new-password">
        <div class="alert-error" id="alert-password"></div>
      </div>

      <div class="form-group">
        <label for="role_id">Pilih Role</label>
        <select id="role_id" name="role_id">
          <option value="">-- Pilih Role --</option>
          @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ $role->role }}</option>
          @endforeach
        </select>
        <div class="alert-error" id="alert-role_id"></div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn-cancel" id="cancel_modal_tambah_pengguna_footer">Keluar</button>
      <button type="button" class="btn-submit" id="store_pengguna">Tambah</button>
    </div>

  </div>
</div>
