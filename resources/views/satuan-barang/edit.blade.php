<style>
#modal_edit_satuan {
  display: none; position: fixed; inset: 0; z-index: 1050;
  background: rgba(0,0,0,0.45); align-items: center; justify-content: center;
  padding: 16px; box-sizing: border-box;
}
#modal_edit_satuan.active { display: flex; }
#modal_edit_satuan .modal-box {
  background: #fff; border-radius: 14px; width: 100%; max-width: 480px;
  max-height: 90vh; display: flex; flex-direction: column;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2); animation: mIn 0.22s ease; overflow: hidden;
}
#modal_edit_satuan .mb-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #eee; flex-shrink:0; }
#modal_edit_satuan .mb-header h5 { margin:0; font-size:16px; font-weight:600; color:#1f1f33; }
#modal_edit_satuan .mb-close { background:none; border:none; font-size:22px; line-height:1; color:#888; cursor:pointer; padding:0 4px; }
#modal_edit_satuan .mb-close:hover { color:#333; }
#modal_edit_satuan .mb-body { padding:20px; overflow-y:auto; -webkit-overflow-scrolling:touch; flex:1; }
#modal_edit_satuan .mb-group { margin-bottom:16px; }
#modal_edit_satuan .mb-group label { display:block; font-size:13px; font-weight:500; color:#444; margin-bottom:6px; }
#modal_edit_satuan .mb-group input { width:100%; padding:10px 12px; font-size:15px; font-family:inherit; border:1px solid #ddd; border-radius:8px; outline:none; box-sizing:border-box; transition:border-color 0.2s; -webkit-appearance:none; appearance:none; background:#fff; color:#333; }
#modal_edit_satuan .mb-group input:focus { border-color:#6e7eff; box-shadow:0 0 0 3px rgba(110,126,255,0.15); }
#modal_edit_satuan .mb-error { display:none; margin-top:6px; padding:7px 12px; background:#fff0f0; border:1px solid #f5c6cb; border-radius:6px; color:#842029; font-size:13px; }
#modal_edit_satuan .mb-error.show { display:block; }
#modal_edit_satuan .mb-footer { display:flex; justify-content:flex-end; gap:10px; padding:14px 20px; border-top:1px solid #eee; flex-shrink:0; }
#modal_edit_satuan .mb-btn-cancel { padding:9px 20px; border:1px solid #ddd; border-radius:8px; background:#f5f5f5; color:#555; font-size:14px; cursor:pointer; font-family:inherit; }
#modal_edit_satuan .mb-btn-cancel:hover { background:#eaeaea; }
#modal_edit_satuan .mb-btn-submit { padding:9px 22px; border:none; border-radius:8px; background:#4f46e5; color:#fff; font-size:14px; font-weight:600; cursor:pointer; font-family:inherit; transition:background 0.2s; }
#modal_edit_satuan .mb-btn-submit:hover { background:#4338ca; }
#modal_edit_satuan .mb-btn-submit:disabled { background:#a5b4fc; cursor:not-allowed; }
</style>

<div id="modal_edit_satuan">
  <div class="modal-box">
    <div class="mb-header">
      <h5>Edit Satuan Barang</h5>
      <button class="mb-close" id="cancel_modal_edit_satuan" type="button">&times;</button>
    </div>
    <div class="mb-body">
      <input type="hidden" id="satuan_id">
      <div class="mb-group">
        <label for="edit_satuan">Nama Satuan Barang</label>
        <input type="text" id="edit_satuan" name="satuan" placeholder="Masukkan nama satuan barang">
        <div class="mb-error" id="alert-edit-satuan"></div>
      </div>
    </div>
    <div class="mb-footer">
      <button type="button" class="mb-btn-cancel" id="cancel_modal_edit_satuan_footer">Keluar</button>
      <button type="button" class="mb-btn-submit" id="update_satuan">Simpan</button>
    </div>
  </div>
</div>
