{{-- ===================== MODAL TAMBAH CUSTOMER (Native CSS/JS) ===================== --}}

<style>
  /* Overlay */
  #modal_tambah_customer {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
    background: rgba(0, 0, 0, 0.45);
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  #modal_tambah_customer.active {
    display: flex;
  }

  /* Dialog */
  #modal_tambah_customer .modal-box {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    animation: modalIn 0.22s ease;
  }

  @keyframes modalIn {
    from { transform: translateY(-16px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  /* Header */
  #modal_tambah_customer .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_customer .modal-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f1f33;
  }
  #modal_tambah_customer .btn-close-modal {
    background: none;
    border: none;
    font-size: 22px;
    line-height: 1;
    color: #888;
    cursor: pointer;
    padding: 0 4px;
  }
  #modal_tambah_customer .btn-close-modal:hover {
    color: #333;
  }

  /* Body */
  #modal_tambah_customer .modal-body {
    padding: 20px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
  }

  /* Form */
  #modal_tambah_customer .form-group {
    margin-bottom: 16px;
  }
  #modal_tambah_customer label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #444;
    margin-bottom: 6px;
  }
  #modal_tambah_customer input,
  #modal_tambah_customer textarea {
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
  }
  #modal_tambah_customer input:focus,
  #modal_tambah_customer textarea:focus {
    border-color: #6e7eff;
    box-shadow: 0 0 0 3px rgba(110, 126, 255, 0.15);
  }
  #modal_tambah_customer textarea {
    resize: vertical;
    min-height: 80px;
  }

  /* Alert error */
  #modal_tambah_customer .alert-error {
    display: none;
    margin-top: 6px;
    padding: 8px 12px;
    background: #fff0f0;
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    color: #842029;
    font-size: 13px;
  }
  #modal_tambah_customer .alert-error.show {
    display: block;
  }

  /* Footer */
  #modal_tambah_customer .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 20px;
    border-top: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_customer .btn-cancel {
    padding: 9px 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f5f5f5;
    color: #555;
    font-size: 14px;
    cursor: pointer;
    font-family: inherit;
  }
  #modal_tambah_customer .btn-cancel:hover {
    background: #eaeaea;
  }
  #modal_tambah_customer .btn-submit {
    padding: 9px 22px;
    border: none;
    border-radius: 8px;
    background: #6e7eff;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.2s;
  }
  #modal_tambah_customer .btn-submit:hover {
    background: #5a6bff;
  }
  #modal_tambah_customer .btn-submit:disabled {
    background: #aab0ff;
    cursor: not-allowed;
  }
</style>

<div id="modal_tambah_customer" role="dialog" aria-modal="true" aria-labelledby="modal_tambah_customer_title">
  <div class="modal-box">

    <div class="modal-header">
      <h5 id="modal_tambah_customer_title">Tambah Customer</h5>
      <button type="button" class="btn-close-modal" id="close_modal_tambah_customer" aria-label="Tutup">&times;</button>
    </div>

    <div class="modal-body">
      <div class="form-group">
        <label for="customer">Nama Perusahaan</label>
        <input type="text" id="customer" name="customer" placeholder="Masukkan nama perusahaan...">
        <div class="alert-error" id="alert-customer"></div>
      </div>

      <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat..."></textarea>
        <div class="alert-error" id="alert-alamat"></div>
      </div>

      <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Opsional..."></textarea>
      </div>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn-cancel" id="cancel_modal_tambah_customer">Keluar</button>
      <button type="button" class="btn-submit" id="store_customer">Tambah</button>
    </div>

  </div>
</div>
