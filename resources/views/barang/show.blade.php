{{-- ===================== MODAL DETAIL BARANG (Native CSS/JS) ===================== --}}

<style>
  /* ===== OVERLAY ===== */
  #modal_detail_barang {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  #modal_detail_barang.active { display: flex; }

  /* ===== DIALOG ===== */
  #modal_detail_barang .modal-box {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 1000px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
    animation: detailIn 0.22s ease;
    overflow: hidden;
  }
  @keyframes detailIn {
    from { transform: translateY(-14px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  /* ===== HEADER ===== */
  #modal_detail_barang .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
  }
  #modal_detail_barang .modal-header h4 {
    margin: 0;
    font-size: 17px;
    font-weight: 600;
    color: #1f1f33;
  }
  #modal_detail_barang .btn-close-modal {
    background: none;
    border: none;
    font-size: 24px;
    line-height: 1;
    color: #999;
    cursor: pointer;
    padding: 0 4px;
  }
  #modal_detail_barang .btn-close-modal:hover { color: #333; }

  /* ===== BODY ===== */
  #modal_detail_barang .modal-body {
    padding: 20px 22px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
  }

  /* ===== LAYOUT ===== */
  #modal_detail_barang .layout-wrap {
    display: flex;
    gap: 32px;
  }
  @media (max-width: 768px) {
    #modal_detail_barang .layout-wrap { flex-direction: column; gap: 20px; }
  }

  /* ===== LEFT PANEL ===== */
  #modal_detail_barang .left-panel { flex: 1.2; }

  #modal_detail_barang .media-viewer {
    width: 100%;
    height: 280px;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    background: #f3f4f6;
  }
  @media (max-width: 768px) {
    #modal_detail_barang .media-viewer { height: 220px; }
  }

  #modal_detail_barang .slider-track {
    display: flex;
    height: 100%;
    transition: transform 0.35s ease;
  }
  #modal_detail_barang .slider-img {
    flex: 0 0 100%;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* Nav buttons */
  #modal_detail_barang .nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.35);
    color: #fff;
    border: none;
    padding: 6px 13px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 20px;
    line-height: 1;
    opacity: 0;
    transition: opacity 0.2s;
  }
  #modal_detail_barang .media-viewer:hover .nav-btn { opacity: 1; }
  #modal_detail_barang .nav-btn.prev { left: 10px; }
  #modal_detail_barang .nav-btn.next { right: 10px; }

  /* Thumbnails */
  #modal_detail_barang .slider-thumbs {
    display: flex;
    gap: 8px;
    margin-top: 10px;
    overflow-x: auto;
    padding-bottom: 4px;
  }
  #modal_detail_barang .slider-thumbs img {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 6px;
    opacity: 0.55;
    cursor: pointer;
    border: 2px solid transparent;
    transition: 0.2s;
    flex-shrink: 0;
  }
  #modal_detail_barang .slider-thumbs img.active {
    opacity: 1;
    border-color: #4f46e5;
  }

  /* ===== RIGHT PANEL ===== */
  #modal_detail_barang .right-panel { flex: 1; }

  #modal_detail_barang .title-main {
    font-size: 20px;
    font-weight: 600;
    color: #1f1f33;
    margin: 0 0 16px;
  }

  #modal_detail_barang .info-list div {
    display: flex;
    justify-content: space-between;
    padding: 9px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
    color: #444;
  }
  #modal_detail_barang .info-list div span:first-child { color: #888; }

  /* Deskripsi */
  #modal_detail_barang .desc-label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
    margin: 16px 0 8px;
  }
  #modal_detail_barang .desc-text {
    font-size: 14px;
    line-height: 1.7;
    color: #444;
    max-height: 72px;
    overflow: hidden;
    transition: max-height 0.3s ease;
  }
  #modal_detail_barang .desc-text.open { max-height: 600px; overflow-y: auto; }
  #modal_detail_barang .btn-readmore {
    margin-top: 8px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #6b7280;
    color: #fff;
    cursor: pointer;
    transition: background 0.2s;
  }
  #modal_detail_barang .btn-readmore:hover { background: #374151; }

  /* Cetak PDF */
  #modal_detail_barang .btn-cetak {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #ef4444;
    color: #fff;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    margin-bottom: 14px;
    transition: background 0.2s;
  }
  #modal_detail_barang .btn-cetak:hover { background: #dc2626; color: #fff; }
</style>

<div id="modal_detail_barang" role="dialog" aria-modal="true" aria-labelledby="modal_detail_barang_title">
  <div class="modal-box">

    <div class="modal-header">
      <h4 id="modal_detail_barang_title">Detail Barang</h4>
      <button type="button" class="btn-close-modal" id="close_modal_detail_barang" aria-label="Tutup">&times;</button>
    </div>

    <div class="modal-body">
      <div class="layout-wrap">

        {{-- LEFT --}}
        <div class="left-panel">
          <div class="media-viewer">
            <div id="slider_images" class="slider-track"></div>
            <button class="nav-btn prev" onclick="slideLeft()">&#8249;</button>
            <button class="nav-btn next" onclick="slideRight()">&#8250;</button>
          </div>
          <div id="slider_thumbs" class="slider-thumbs"></div>
        </div>

        {{-- RIGHT --}}
        <div class="right-panel">
          <a href="#" id="btnCetakPdf" class="btn-cetak">
            <i class="fas fa-file-pdf"></i> Cetak PDF
          </a>

          <h3 id="detail_nama_barang" class="title-main">-</h3>

          <div class="info-list">
            <div><span><i class="fas fa-tags"></i> Jenis</span><span id="detail_jenis">-</span></div>
            <div><span><i class="fas fa-balance-scale"></i> Satuan</span><span id="detail_satuan">-</span></div>
            <div><span><i class="fas fa-boxes"></i> Stok</span><span id="detail_stok">-</span></div>
            <div><span><i class="fas fa-exclamation-triangle"></i> Min Stok</span><span id="detail_stok_minimum">-</span></div>
          </div>

          <div class="desc-label"><i class="fas fa-align-left"></i> Deskripsi</div>
          <div id="detail_deskripsi" class="desc-text">-</div>
          <button id="btn_readmore" class="btn-readmore" style="display:none;">Read More</button>
        </div>

      </div>
    </div>

  </div>
</div>
