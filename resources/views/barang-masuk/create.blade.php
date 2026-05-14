<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="modal fade" role="dialog" id="modal_tambah_barangMasuk">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Tambah Barang Masuk</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <form enctype="multipart/form-data">
        <div class="modal-body">

          <div class="row">

            <!-- LEFT -->
            <div class="col-12 col-md-6">

              <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="text" class="form-control" name="tanggal_masuk" id="tanggal_masuk">
                <div class="alert alert-danger mt-2 d-none" id="alert-tanggal_masuk"></div>
              </div>

              <div class="form-group">
                <label>Kode Transaksi</label>
                <input type="text" class="form-control" name="kode_transaksi" id="kode_transaksi" readonly>
                <div class="alert alert-danger mt-2 d-none" id="alert-kode_transaksi"></div>
              </div>

              <div class="form-group">
                <label>Stok Saat Ini</label>
                <input type="number" id="stok" class="form-control" disabled>
                <div class="alert alert-danger mt-2 d-none" id="alert-stok"></div>
              </div>

            </div>

            <!-- RIGHT -->
           <div class="col-12 col-md-6">

  <!-- PILIH BARANG -->
  <div class="form-group">
    <label>Pilih Barang</label>
    <select class="form-control select2 w-100" name="barang_id" id="barang_id">
      <option value="">Pilih Barang</option>
      @foreach ($barangs as $barang)
        <option value="{{ $barang->id }}">
          {{ $barang->nama_barang }}
        </option>
      @endforeach
    </select>
    <div class="alert alert-danger mt-2 d-none" id="alert-nama_barang"></div>
  </div>

  <!-- SUPPLIER -->
  <div class="form-group">
    <label>Supplier</label>
    <select class="form-control select2 w-100" name="supplier_id" id="supplier_id">
      @foreach ($suppliers as $supplier)
        <option value="{{ $supplier->id }}">
          {{ $supplier->supplier }}
        </option>
      @endforeach
    </select>
    <div class="alert alert-danger mt-2 d-none" id="alert-supplier_id"></div>
  </div>

  <!-- JUMLAH MASUK -->
  <div class="form-group">
    <label>Jumlah Masuk</label>
    <div class="input-group">

      <input type="number"
             class="form-control"
             name="jumlah_masuk"
             id="jumlah_masuk"
             min="0">

      <div class="input-group-append">
        <span class="input-group-text bg-light" id="satuan_text">-</span>
      </div>

    </div>

    <div class="alert alert-danger mt-2 d-none" id="alert-jumlah_masuk"></div>
  </div>

</div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button type="button" class="btn btn-primary" id="store_barangMasuk">Tambah</button>
        </div>

      </form>

    </div>
  </div>
</div>

<style>
  .select2-container {
    width: 100% !important;
  }

  /* === GLOBAL FORM CONSISTENCY (scoped to modal) === */
  .modal .form-control {
    border: 1px solid #dcdcdc;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.5;
    padding: 8px 12px;
    box-shadow: none;
  }

  .modal .form-control:focus {
    border-color: #cfcfcf;
    box-shadow: none;
  }

  /* === SELECT2 STYLING === */
  .modal .select2-container--default .select2-selection--single {
    border: 1px solid #dcdcdc;
    border-radius: 6px;
    height: 38px;
    display: flex;
    align-items: center;
    padding: 0 8px;
  }

  .modal .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #cfcfcf;
    box-shadow: none;
  }

  .select2-selection__arrow {
    height: 100% !important;
  }

  /* === INPUT GROUP (JUMLAH MASUK) === */
  .modal .input-group-text {
    border: 1px solid #dcdcdc;
    border-left: none;
    background: #f8f9fa;
    font-size: 14px;
    font-weight: 400;
  }

  /* === LABEL === */
  .modal label {
    font-weight: 500;
    margin-bottom: 6px;
  }
</style>


