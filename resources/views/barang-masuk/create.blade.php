{{-- ===================== MODAL TAMBAH BARANG MASUK (Native CSS/JS) ===================== --}}

<style>
  /* ===== OVERLAY ===== */
  #modal_tambah_barangMasuk {
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
  #modal_tambah_barangMasuk.active { display: flex; }

  /* ===== DIALOG ===== */
  #modal_tambah_barangMasuk .modal-box {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 760px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 64px rgba(0,0,0,0.18);
    animation: bmIn 0.22s ease;
    overflow: hidden;
  }
  @keyframes bmIn {
    from { transform: translateY(-14px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  /* ===== HEADER ===== */
  #modal_tambah_barangMasuk .mb-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_barangMasuk .mb-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f1f33;
  }
  #modal_tambah_barangMasuk .mb-close {
    background: none;
    border: none;
    font-size: 24px;
    line-height: 1;
    color: #999;
    cursor: pointer;
    padding: 0 4px;
    flex-shrink: 0;
  }
  #modal_tambah_barangMasuk .mb-close:hover { color: #333; }

  /* ===== BODY ===== */
  #modal_tambah_barangMasuk .mb-body {
    padding: 20px 22px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
  }

  /* ===== GRID 2 KOLOM ===== */
  #modal_tambah_barangMasuk .mb-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 24px;
  }
  @media (max-width: 600px) {
    #modal_tambah_barangMasuk .mb-grid { grid-template-columns: 1fr; }
  }

  /* ===== FORM ===== */
  #modal_tambah_barangMasuk .mb-group { margin-bottom: 14px; }
  #modal_tambah_barangMasuk .mb-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #444;
    margin-bottom: 5px;
  }
  #modal_tambah_barangMasuk .mb-group input,
  #modal_tambah_barangMasuk .mb-group select {
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
  #modal_tambah_barangMasuk .mb-group input:focus,
  #modal_tambah_barangMasuk .mb-group select:focus {
    border-color: #6e7eff;
    box-shadow: 0 0 0 3px rgba(110,126,255,0.15);
  }
  #modal_tambah_barangMasuk .mb-group input[readonly],
  #modal_tambah_barangMasuk .mb-group input:disabled {
    background: #f5f5f5;
    color: #888;
    cursor: not-allowed;
  }

  /* Input jumlah + satuan badge */
  #modal_tambah_barangMasuk .input-row {
    display: flex;
    gap: 8px;
    align-items: stretch;
  }
  #modal_tambah_barangMasuk .input-row input { flex: 1; min-width: 0; }
  #modal_tambah_barangMasuk .satuan-badge {
    padding: 10px 14px;
    background: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    color: #555;
    white-space: nowrap;
    display: flex;
    align-items: center;
  }

  /* ===== ERROR ===== */
  #modal_tambah_barangMasuk .mb-error {
    display: none;
    margin-top: 6px;
    padding: 7px 12px;
    background: #fff0f0;
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    color: #842029;
    font-size: 13px;
  }
  #modal_tambah_barangMasuk .mb-error.show { display: block; }

  /* ===== FOOTER ===== */
  #modal_tambah_barangMasuk .mb-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 22px;
    border-top: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_barangMasuk .mb-btn-cancel {
    padding: 9px 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f5f5f5;
    color: #555;
    font-size: 14px;
    cursor: pointer;
    font-family: inherit;
  }
  #modal_tambah_barangMasuk .mb-btn-cancel:hover { background: #eaeaea; }
  #modal_tambah_barangMasuk .mb-btn-submit {
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
  #modal_tambah_barangMasuk .mb-btn-submit:hover    { background: #4338ca; }
  #modal_tambah_barangMasuk .mb-btn-submit:disabled { background: #a5b4fc; cursor: not-allowed; }
</style>

<div id="modal_tambah_barangMasuk" role="dialog" aria-modal="true" aria-labelledby="modal_bm_title">
  <div class="modal-box">

    <div class="mb-header">
      <h5 id="modal_bm_title">Tambah Barang Masuk</h5>
      <button type="button" class="mb-close" id="close_modal_barangMasuk" aria-label="Tutup">&times;</button>
    </div>

    <div class="mb-body">
      <div class="mb-grid">

        {{-- KOLOM KIRI --}}
        <div>
          <div class="mb-group">
            <label for="tanggal_masuk">Tanggal Masuk</label>
            <input type="date" id="tanggal_masuk" name="tanggal_masuk">
            <div class="mb-error" id="alert-tanggal_masuk"></div>
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
            <label for="barang_id">Pilih Barang</label>
            <select id="barang_id" name="barang_id">
              <option value="">-- Pilih Barang --</option>
              @foreach ($barangs as $barang)
                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
              @endforeach
            </select>
            <div class="mb-error" id="alert-barang_id"></div>
          </div>

          <div class="mb-group">
            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" name="supplier_id">
              <option value="">-- Pilih Supplier --</option>
              @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
              @endforeach
            </select>
            <div class="mb-error" id="alert-supplier_id"></div>
          </div>

          <div class="mb-group">
            <label for="jumlah_masuk">Jumlah Masuk</label>
            <div class="input-row">
              <input type="number" id="jumlah_masuk" name="jumlah_masuk" min="0" placeholder="0">
              <span class="satuan-badge" id="satuan_text">-</span>
            </div>
            <div class="mb-error" id="alert-jumlah_masuk"></div>
          </div>
        </div>

      </div>
    </div>

    <div class="mb-footer">
      <button type="button" class="mb-btn-cancel" id="cancel_modal_barangMasuk">Keluar</button>
      <button type="button" class="mb-btn-submit" id="store_barangMasuk">Tambah</button>
    </div>

  </div>
</div>
