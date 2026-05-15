@extends('layouts.app')

@section('page-title', 'Jenis Barang')

@include('jenis-barang.create')
@include('jenis-barang.edit')

@section('content')
    <div class="section-header">
        <h1>Data Jenis Barang</h1>
        <div class="ml-auto">
            @if($canAdd)
            <button type="button" class="btn btn-primary" id="button_tambah_jenis_barang">
                <i class="fa fa-plus"></i> Jenis Barang
            </button>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_id" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Barang</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    var canAdd = {{ $canAdd ? 'true' : 'false' }};

    // ===== HELPERS =====
    function openModal(id)  { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    // ===== CLOSE TAMBAH =====
    function closeTambah() {
        closeModal('modal_tambah_jenis_barang');
        document.getElementById('jenis_barang').value = '';
        var el = document.getElementById('alert-jenis_barang');
        if (el) { el.textContent = ''; el.classList.remove('show'); }
    }

    // ===== CLOSE EDIT =====
    function closeEdit() {
        closeModal('modal_edit_jenis_barang');
        var el = document.getElementById('alert-edit-jenis_barang');
        if (el) { el.textContent = ''; el.classList.remove('show'); }
    }

    // ===== BIND CLOSE BUTTONS =====
    document.getElementById('cancel_modal_tambah_jenis_barang').addEventListener('click', closeTambah);
    document.getElementById('cancel_modal_tambah_jenis_barang_footer').addEventListener('click', closeTambah);
    document.getElementById('cancel_modal_edit_jenis_barang').addEventListener('click', closeEdit);
    document.getElementById('cancel_modal_edit_jenis_barang_footer').addEventListener('click', closeEdit);

    document.getElementById('modal_tambah_jenis_barang').addEventListener('click', function (e) {
        if (e.target === this) closeTambah();
    });
    document.getElementById('modal_edit_jenis_barang').addEventListener('click', function (e) {
        if (e.target === this) closeEdit();
    });

    // ===== DATATABLE =====
    var table = $('#table_id').DataTable({ paging: true });

    function renderOpsi(id) {
        if (!canAdd) return '-';
        return '<div style="display:flex;gap:6px;flex-wrap:wrap;">'
            + '<button class="btn-tbl-edit btn_edit_jenis" data-id="' + id + '"><i class="far fa-edit"></i> Edit</button>'
            + '<button class="btn-tbl-hapus btn_hapus_jenis" data-id="' + id + '"><i class="fas fa-trash"></i> Hapus</button>'
            + '</div>';
    }

    function reloadTable() {
        $.ajax({
            url: '/jenis-barang/get-data', type: 'GET', dataType: 'JSON',
            success: function (response) {
                table.clear();
                var counter = 1;
                $.each(response.data, function (key, value) {
                    table.row.add($('<tr id="index_' + value.id + '">'
                        + '<td>' + counter++ + '</td>'
                        + '<td>' + value.jenis_barang + '</td>'
                        + '<td>' + renderOpsi(value.id) + '</td>'
                        + '</tr>')).draw(false);
                });
            }
        });
    }

    reloadTable();

    // ===== BUKA MODAL TAMBAH =====
    document.getElementById('button_tambah_jenis_barang') &&
    document.getElementById('button_tambah_jenis_barang').addEventListener('click', function () {
        closeTambah();
        openModal('modal_tambah_jenis_barang');
    });

    // ===== STORE =====
    $('body').on('click', '#store_jenis_barang', function (e) {
        e.preventDefault();
        var val   = document.getElementById('jenis_barang').value.trim();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var errEl = document.getElementById('alert-jenis_barang');
        errEl.textContent = ''; errEl.classList.remove('show');

        if (!val) {
            Swal.fire({ icon: 'warning', title: 'Jenis barang tidak boleh kosong' });
            return;
        }

        $.ajax({
            url: '/jenis-barang', type: 'POST',
            headers: { 'X-CSRF-TOKEN': token },
            data: { jenis_barang: val },
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                closeTambah();
                reloadTable();
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.jenis_barang) {
                    errEl.textContent = xhr.responseJSON.jenis_barang[0];
                    errEl.classList.add('show');
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal menambahkan data' });
                }
            }
        });
    });

    // ===== BUKA MODAL EDIT =====
    $('body').on('click', '.btn_edit_jenis', function () {
        var jenis_id = $(this).data('id');
        $.ajax({
            url: '/jenis-barang/' + jenis_id + '/edit', type: 'GET',
            success: function (response) {
                document.getElementById('jenis_id').value          = response.data.id;
                document.getElementById('edit_jenis_barang').value = response.data.jenis_barang;
                var el = document.getElementById('alert-edit-jenis_barang');
                if (el) { el.textContent = ''; el.classList.remove('show'); }
                openModal('modal_edit_jenis_barang');
            }
        });
    });

    // ===== UPDATE =====
    $('body').on('click', '#update_jenis_barang', function (e) {
        e.preventDefault();
        var jenis_id = document.getElementById('jenis_id').value;
        var val      = document.getElementById('edit_jenis_barang').value;
        var token    = document.querySelector('meta[name="csrf-token"]').content;
        var errEl    = document.getElementById('alert-edit-jenis_barang');
        errEl.textContent = ''; errEl.classList.remove('show');

        var formData = new FormData();
        formData.append('jenis_barang', val);
        formData.append('_token', token);
        formData.append('_method', 'PUT');

        $.ajax({
            url: '/jenis-barang/' + jenis_id, type: 'POST',
            data: formData, contentType: false, processData: false,
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                $('#index_' + response.data.id).find('td').eq(1).text(response.data.jenis_barang);
                closeEdit();
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.jenis_barang) {
                    errEl.textContent = xhr.responseJSON.jenis_barang[0];
                    errEl.classList.add('show');
                }
            }
        });
    });

    // ===== HAPUS =====
    $('body').on('click', '.btn_hapus_jenis', function () {
        var jenis_id = $(this).data('id');
        var token    = document.querySelector('meta[name="csrf-token"]').content;
        Swal.fire({
            title: 'Apakah Kamu Yakin?', text: 'Data akan dihapus!', icon: 'warning',
            showCancelButton: true, cancelButtonText: 'TIDAK', confirmButtonText: 'YA, HAPUS!'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/jenis-barang/' + jenis_id, type: 'DELETE',
                    data: { _token: token },
                    success: function (response) {
                        Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                        reloadTable();
                    }
                });
            }
        });
    });

});
</script>
@endpush
