{{-- ===================== MODAL EDIT BARANG (Native CSS/JS) ===================== --}}

<style>
  /* ===== OVERLAY ===== */
  #modal_edit_barang {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
    background: rgba(0,0,0,0.45);
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  #modal_edit_barang.active { display: flex; }

  /* ===== DIALOG ===== */
  #modal_edit_barang .modal-box {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 820px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 64px rgba(0,0,0,0.18);
    animation: mbEditIn 0.22s ease;
  }
  @keyframes mbEditIn {
    from { transform: translateY(-14px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  /* ===== HEADER ===== */
  #modal_edit_barang .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_edit_barang .modal-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f1f33;
  }
  #modal_edit_barang .btn-close-modal {
    background: none;
    border: none;
    font-size: 24px;
    line-height: 1;
    color: #999;
    cursor: pointer;
    padding: 0 4px;
  }
  #modal_edit_barang .btn-close-modal:hover { color: #333; }

  /* ===== BODY ===== */
  #modal_edit_barang .modal-body {
    padding: 20px 22px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
  }

  /* ===== GRID 2 KOLOM ===== */
  #modal_edit_barang .modal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 24px;
  }
  @media (max-width: 600px) {
    #modal_edit_barang .modal-grid { grid-template-columns: 1fr; }
  }

  /* ===== FORM ===== */
  #modal_edit_barang .form-group { margin-bottom: 14px; }
  #modal_edit_barang label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #444;
    margin-bottom: 5px;
  }
  #modal_edit_barang input[type="text"],
  #modal_edit_barang input[type="number"],
  #modal_edit_barang input[type="file"],
  #modal_edit_barang select,
  #modal_edit_barang textarea {
    width: 100%;
    padding: 9px 12px;
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
  }
  #modal_edit_barang input[type="file"] { padding: 7px 10px; cursor: pointer; }
  #modal_edit_barang input:focus,
  #modal_edit_barang select:focus,
  #modal_edit_barang textarea:focus {
    border-color: #6e7eff;
    box-shadow: 0 0 0 3px rgba(110,126,255,0.15);
  }
  #modal_edit_barang textarea { resize: vertical; min-height: 120px; }

  /* Preview gambar edit */
  #edit_gambar_preview {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
  }
  #edit_gambar_preview img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
  }

  /* ===== FOOTER ===== */
  #modal_edit_barang .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 22px;
    border-top: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_edit_barang .btn-cancel {
    padding: 9px 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f5f5f5;
    color: #555;
    font-size: 14px;
    cursor: pointer;
    font-family: inherit;
  }
  #modal_edit_barang .btn-cancel:hover { background: #eaeaea; }
  #modal_edit_barang .btn-submit {
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
  #modal_edit_barang .btn-submit:hover { background: #4338ca; }
  #modal_edit_barang .btn-submit:disabled { background: #a5b4fc; cursor: not-allowed; }
</style>

<div id="modal_edit_barang" role="dialog" aria-modal="true" aria-labelledby="modal_edit_barang_title">
  <div class="modal-box">

    <div class="modal-header">
      <h5 id="modal_edit_barang_title">Edit Barang</h5>
      <button type="button" class="btn-close-modal" id="close_modal_edit_barang" aria-label="Tutup">&times;</button>
    </div>

    <div class="modal-body">
      <form id="form_edit_barang" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" id="barang_id">

        <div class="modal-grid">

          {{-- KOLOM KIRI --}}
          <div>
            <div class="form-group">
              <label for="edit_gambar">Upload Gambar</label>
              <input type="file" id="edit_gambar" name="gambar[]" multiple accept="image/*"
                     onchange="previewEditBarang(event)">
              <small style="color:#999;font-size:12px;">Kosongkan jika tidak ingin mengganti gambar</small>
              <div id="edit_gambar_preview"></div>
            </div>
          </div>

          {{-- KOLOM KANAN --}}
          <div>
            <div class="form-group">
              <label for="edit_nama_barang">Nama Barang</label>
              <input type="text" id="edit_nama_barang" name="nama_barang" placeholder="Nama barang...">
            </div>

            <div class="form-group">
              <label for="edit_jenis_id">Jenis</label>
              <select id="edit_jenis_id" name="jenis_id">
                @foreach ($jenis_barangs as $jenis)
                  <option value="{{ $jenis->id }}">{{ $jenis->jenis_barang }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="edit_satuan">Satuan</label>
              <input type="text" id="edit_satuan" name="satuan" placeholder="Misal: pcs, kg, dus, liter...">
            </div>

            <div class="form-group">
              <label for="edit_stok_minimum">Stok Minimum</label>
              <input type="number" id="edit_stok_minimum" name="stok_minimum" min="0">
            </div>

            <div class="form-group">
              <label for="edit_deskripsi">Deskripsi</label>
              <textarea id="edit_deskripsi" name="deskripsi" rows="4"></textarea>
            </div>
          </div>

        </div>
      </form>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn-cancel" id="cancel_modal_edit_barang">Batal</button>
      <button type="button" class="btn-submit" id="update">Update</button>
    </div>

  </div>
</div>

<script>
  function previewEditBarang(event) {
    var files   = event.target.files;
    var preview = document.getElementById('edit_gambar_preview');
    preview.innerHTML = '';
    for (var i = 0; i < files.length; i++) {
      var img = document.createElement('img');
      img.src = URL.createObjectURL(files[i]);
      preview.appendChild(img);
    }
  }
</script>
