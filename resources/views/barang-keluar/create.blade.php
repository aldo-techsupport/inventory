{{-- ===================== MODAL TAMBAH BARANG KELUAR (Native CSS/JS) ===================== --}}

<style>
  /* ===== OVERLAY ===== */
  #modal_tambah_barangKeluar {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
    background: rgba(0,0,0,0.45);
    align-items: center;
    justify-content: center;
    padding: 16px;
    box-sizing: border-box;
  }
  #modal_tambah_barangKeluar.active { display: flex; }

  /* ===== DIALOG ===== */
  #modal_tambah_barangKeluar .modal-box {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 760px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 64px rgba(0,0,0,0.18);
    animation: bkIn 0.22s ease;
    overflow: hidden;
  }
  @keyframes bkIn {
    from { transform: translateY(-14px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  /* ===== HEADER ===== */
  #modal_tambah_barangKeluar .mb-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_barangKeluar .mb-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f1f33;
  }
  #modal_tambah_barangKeluar .mb-close {
    background: none;
    border: none;
    font-size: 24px;
    line-height: 1;
    color: #999;
    cursor: pointer;
    padding: 0 4px;
    flex-shrink: 0;
  }
  #modal_tambah_barangKeluar .mb-close:hover { color: #333; }

  /* ===== BODY ===== */
  #modal_tambah_barangKeluar .mb-body {
    padding: 20px 22px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
  }

  /* ===== GRID 2 KOLOM ===== */
  #modal_tambah_barangKeluar .mb-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 24px;
  }
  @media (max-width: 600px) {
    #modal_tambah_barangKeluar .mb-grid { grid-template-columns: 1fr; }
  }

  /* ===== FORM ===== */
  #modal_tambah_barangKeluar .mb-group { margin-bottom: 14px; }
  #modal_tambah_barangKeluar .mb-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #444;
    margin-bottom: 5px;
  }
  #modal_tambah_barangKeluar .mb-group input,
  #modal_tambah_barangKeluar .mb-group select,
  #modal_tambah_barangKeluar .mb-group textarea {
    width: 100%;
    padding: 10px 12px;
    font-size: 15px;
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
  #modal_tambah_barangKeluar .mb-group input:focus,
  #modal_tambah_barangKeluar .mb-group select:focus,
  #modal_tambah_barangKeluar .mb-group textarea:focus {
    border-color: #6e7eff;
    box-shadow: 0 0 0 3px rgba(110,126,255,0.15);
  }
  #modal_tambah_barangKeluar .mb-group input[readonly],
  #modal_tambah_barangKeluar .mb-group input:disabled {
    background: #f5f5f5;
    color: #888;
    cursor: not-allowed;
  }
  #modal_tambah_barangKeluar .mb-group textarea {
    resize: vertical;
    min-height: 70px;
  }

  /* Input jumlah + satuan */
  #modal_tambah_barangKeluar .input-row {
    display: flex;
    gap: 8px;
  }
  #modal_tambah_barangKeluar .input-row input:first-child { flex: 1; min-width: 0; }
  #modal_tambah_barangKeluar .input-row input:last-child  { max-width: 90px; flex-shrink: 0; }

  /* ===== ERROR ===== */
  #modal_tambah_barangKeluar .mb-error {
    display: none;
    margin-top: 6px;
    padding: 7px 12px;
    background: #fff0f0;
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    color: #842029;
    font-size: 13px;
  }
  #modal_tambah_barangKeluar .mb-error.show { display: block; }

  /* ===== CUSTOMER BARU INLINE ===== */
  #form_customer_baru {
    display: none;
    background: #f9f9ff;
    border: 1px solid #e0e0ff;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 14px;
  }
  #form_customer_baru .mb-group { margin-bottom: 12px; }
  #form_customer_baru .mb-group:last-of-type { margin-bottom: 10px; }
  #form_customer_baru .inline-actions {
    display: flex;
    gap: 8px;
  }
  #btn_simpan_customer_baru {
    padding: 8px 16px;
    background: #22c55e;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-family: inherit;
    cursor: pointer;
    transition: background 0.2s;
  }
  #btn_simpan_customer_baru:hover    { background: #16a34a; }
  #btn_simpan_customer_baru:disabled { background: #86efac; cursor: not-allowed; }
  #btn_batal_customer_baru {
    padding: 8px 16px;
    background: #f5f5f5;
    color: #555;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 13px;
    font-family: inherit;
    cursor: pointer;
  }
  #btn_batal_customer_baru:hover { background: #eaeaea; }

  /* ===== FOOTER ===== */
  #modal_tambah_barangKeluar .mb-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 22px;
    border-top: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_barangKeluar .mb-btn-cancel {
    padding: 9px 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f5f5f5;
    color: #555;
    font-size: 14px;
    cursor: pointer;
    font-family: inherit;
  }
  #modal_tambah_barangKeluar .mb-btn-cancel:hover { background: #eaeaea; }
  #modal_tambah_barangKeluar .mb-btn-submit {
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
  #modal_tambah_barangKeluar .mb-btn-submit:hover    { background: #4338ca; }
  #modal_tambah_barangKeluar .mb-btn-submit:disabled { background: #a5b4fc; cursor: not-allowed; }
</style>

<div id="modal_tambah_barangKeluar" role="dialog" aria-modal="true" aria-labelledby="modal_bk_title">
  <div class="modal-box">

    <div class="mb-header">
      <h5 id="modal_bk_title">Tambah Barang Keluar</h5>
      <button type="button" class="mb-close" id="cancel_modal_tambah_barangKeluar" aria-label="Tutup">&times;</button>
    </div>

    <div class="mb-body">
      <div class="mb-grid">

        {{-- KOLOM KIRI --}}
        <div>
          <div class="mb-group">
            <label for="tanggal_keluar">Tanggal Keluar</label>
            <input type="date" id="tanggal_keluar" name="tanggal_keluar">
            <div class="mb-error" id="alert-tanggal_keluar"></div>
          </div>

          <div class="mb-group">
            <label for="kode_transaksi">Kode Transaksi</label>
            <input type="text" id="kode_transaksi" name="kode_transaksi" readonly>
            <div class="mb-error" id="alert-kode_transaksi"></div>
          </div>

          <div class="mb-group">
            <label for="stok">Stok Saat Ini</label>
            <input type="number" id="stok" name="stok" disabled>
          </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div>
          <div class="mb-group">
            <label for="nama_barang">Pilih Barang</label>
            <select class="js-example-basic-single" id="nama_barang" name="nama_barang" style="width:100%">
              <option value="">-- Pilih Barang --</option>
              @foreach ($barangs as $barang)
                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
              @endforeach
            </select>
            <div class="mb-error" id="alert-nama_barang"></div>
          </div>

          <div class="mb-group">
            <label for="customer_id">Customer</label>
            <select id="customer_id" name="customer_id">
              <option value="">-- Pilih Customer --</option>
              @foreach ($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->customer }}</option>
              @endforeach
              <option value="other">+ Tambah Customer Baru</option>
            </select>
            <div class="mb-error" id="alert-customer_id"></div>
          </div>

          {{-- Form customer baru inline --}}
          <div id="form_customer_baru">
            <div class="mb-group">
              <label>Nama Perusahaan <span style="color:#e53e3e">*</span></label>
              <input type="text" id="new_customer_nama" placeholder="Nama perusahaan">
              <div class="mb-error" id="alert-new_customer_nama"></div>
            </div>
            <div class="mb-group">
              <label>Alamat <span style="color:#e53e3e">*</span></label>
              <textarea id="new_customer_alamat" rows="2" placeholder="Alamat"></textarea>
              <div class="mb-error" id="alert-new_customer_alamat"></div>
            </div>
            <div class="mb-group">
              <label>Deskripsi</label>
              <textarea id="new_customer_deskripsi" rows="2" placeholder="Deskripsi (opsional)"></textarea>
            </div>
            <div class="inline-actions">
              <button type="button" id="btn_simpan_customer_baru">
                <i class="fas fa-save"></i> Simpan Customer
              </button>
              <button type="button" id="btn_batal_customer_baru">Batal</button>
            </div>
          </div>

          <div class="mb-group">
            <label for="jumlah_keluar">Jumlah Keluar</label>
            <div class="input-row">
              <input type="number" id="jumlah_keluar" name="jumlah_keluar" min="0" placeholder="0">
              <input type="text" id="satuan_id" name="satuan" disabled placeholder="Satuan">
            </div>
            <div class="mb-error" id="alert-jumlah_keluar"></div>
          </div>
        </div>

      </div>
    </div>

    <div class="mb-footer">
      <button type="button" class="mb-btn-cancel" id="cancel_modal_tambah_barangKeluar_footer">Keluar</button>
      <button type="button" class="mb-btn-submit" id="store_barangKeluar">Tambah</button>
    </div>

  </div>
</div>
