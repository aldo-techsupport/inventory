{{-- ===================== MODAL TAMBAH BARANG (Native CSS/JS) ===================== --}}

<style>
  /* ===== OVERLAY ===== */
  #modal_tambah_barang {
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
  #modal_tambah_barang.active { display: flex; }

  /* ===== DIALOG ===== */
  #modal_tambah_barang .modal-box {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 760px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 64px rgba(0,0,0,0.18);
    animation: mbrgIn 0.22s ease;
    overflow: hidden;
  }
  @keyframes mbrgIn {
    from { transform: translateY(-14px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  /* ===== HEADER ===== */
  #modal_tambah_barang .mb-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_barang .mb-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f1f33;
  }
  #modal_tambah_barang .mb-close {
    background: none;
    border: none;
    font-size: 24px;
    line-height: 1;
    color: #999;
    cursor: pointer;
    padding: 0 4px;
    flex-shrink: 0;
  }
  #modal_tambah_barang .mb-close:hover { color: #333; }

  /* ===== BODY ===== */
  #modal_tambah_barang .mb-body {
    padding: 20px 22px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
  }

  /* ===== GRID 2 KOLOM ===== */
  #modal_tambah_barang .mb-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 24px;
  }
  @media (max-width: 600px) {
    #modal_tambah_barang .mb-grid { grid-template-columns: 1fr; }
  }

  /* ===== FORM ===== */
  #modal_tambah_barang .mb-group { margin-bottom: 14px; }
  #modal_tambah_barang .mb-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #444;
    margin-bottom: 5px;
  }
  #modal_tambah_barang .mb-group input,
  #modal_tambah_barang .mb-group select,
  #modal_tambah_barang .mb-group textarea {
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
  #modal_tambah_barang .mb-group input[type="file"] {
    padding: 8px 10px;
    cursor: pointer;
  }
  #modal_tambah_barang .mb-group input:focus,
  #modal_tambah_barang .mb-group select:focus,
  #modal_tambah_barang .mb-group textarea:focus {
    border-color: #6e7eff;
    box-shadow: 0 0 0 3px rgba(110,126,255,0.15);
  }
  #modal_tambah_barang .mb-group textarea {
    resize: vertical;
    min-height: 90px;
  }

  /* Preview gambar */
  #preview_tambah_barang {
    display: none;
    margin-top: 10px;
    max-height: 200px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    object-fit: cover;
    width: 100%;
  }

  /* ===== ERROR ===== */
  #modal_tambah_barang .mb-error {
    display: none;
    margin-top: 6px;
    padding: 7px 12px;
    background: #fff0f0;
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    color: #842029;
    font-size: 13px;
  }
  #modal_tambah_barang .mb-error.show { display: block; }

  /* ===== FOOTER ===== */
  #modal_tambah_barang .mb-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 22px;
    border-top: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_tambah_barang .mb-btn-cancel {
    padding: 9px 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f5f5f5;
    color: #555;
    font-size: 14px;
    cursor: pointer;
    font-family: inherit;
  }
  #modal_tambah_barang .mb-btn-cancel:hover { background: #eaeaea; }
  #modal_tambah_barang .mb-btn-submit {
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
  #modal_tambah_barang .mb-btn-submit:hover    { background: #4338ca; }
  #modal_tambah_barang .mb-btn-submit:disabled { background: #a5b4fc; cursor: not-allowed; }
</style>

<div id="modal_tambah_barang" role="dialog" aria-modal="true" aria-labelledby="modal_brg_title">
  <div class="modal-box">

    <div class="mb-header">
      <h5 id="modal_brg_title">Tambah Barang</h5>
      <button type="button" class="mb-close" id="close_modal_tambah_barang" aria-label="Tutup">&times;</button>
    </div>

    <div class="mb-body">
      <form id="form_tambah_barang" enctype="multipart/form-data" autocomplete="off">
        <div class="mb-grid">

          {{-- KOLOM KIRI --}}
          <div>
            <div class="mb-group">
              <label for="gambar">
                Gambar <small style="color:#999;font-weight:400;">(maks. 20 foto)</small>
              </label>
              <input type="file" name="gambar[]" id="gambar" multiple accept="image/*"
                     onchange="previewTambahBarang(event)">
              <img id="preview_tambah_barang" src="" alt="preview">
            </div>
          </div>

          {{-- KOLOM KANAN --}}
          <div>
            <div class="mb-group">
              <label for="nama_barang">Nama Barang</label>
              <input type="text" name="nama_barang" id="nama_barang" placeholder="Masukkan nama barang...">
              <div class="mb-error" id="alert-nama_barang"></div>
            </div>

            <div class="mb-group">
              <label for="jenis_id">Jenis Barang</label>
              <select name="jenis_id" id="jenis_id">
                <option value="">-- Pilih Jenis --</option>
                @foreach ($jenis_barangs as $jenis)
                  <option value="{{ $jenis->id }}">{{ $jenis->jenis_barang }}</option>
                @endforeach
              </select>
              <div class="mb-error" id="alert-jenis_id"></div>
            </div>

            <div class="mb-group">
              <label for="satuan">Satuan Barang</label>
              <input type="text" name="satuan" id="satuan" placeholder="Misal: pcs, kg, dus, liter...">
              <div class="mb-error" id="alert-satuan"></div>
            </div>

            <div class="mb-group">
              <label for="stok_minimum">Stok Minimum</label>
              <input type="number" name="stok_minimum" id="stok_minimum" placeholder="Misal: 10" min="0">
              <div class="mb-error" id="alert-stok_minimum"></div>
            </div>

            <div class="mb-group">
              <label for="deskripsi">Deskripsi</label>
              <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Tuliskan deskripsi barang..."></textarea>
              <div class="mb-error" id="alert-deskripsi"></div>
            </div>
          </div>

        </div>
      </form>
    </div>

    <div class="mb-footer">
      <button type="button" class="mb-btn-cancel" id="cancel_modal_tambah_barang">Keluar</button>
      <button type="button" class="mb-btn-submit" id="store">Tambah</button>
    </div>

  </div>
</div>

<script>
  function previewTambahBarang(event) {
    var file = event.target.files[0];
    var img  = document.getElementById('preview_tambah_barang');
    if (file) {
      img.src = URL.createObjectURL(file);
      img.style.display = 'block';
    } else {
      img.style.display = 'none';
    }
  }
</script>
