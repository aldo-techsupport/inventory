@extends('layouts.app')

@include('data-pengguna.create')
@include('data-pengguna.edit')

@section('content')

<style>
  .btn-tbl-edit,
  .btn-tbl-hapus {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border: none;
    border-radius: 7px;
    font-size: 13px;
    font-family: inherit;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.18s, transform 0.12s;
  }
  .btn-tbl-edit  { background: #fff3cd; color: #856404; }
  .btn-tbl-edit:hover  { background: #ffe69c; transform: translateY(-1px); }
  .btn-tbl-hapus { background: #ffe0e0; color: #b91c1c; }
  .btn-tbl-hapus:hover { background: #ffc9c9; transform: translateY(-1px); }
</style>

<div class="section-header">
  <h1>Data Pengguna</h1>
  <div class="ml-auto">
    <button type="button" class="btn btn-primary" id="button_tambah_pengguna">
      <i class="fa fa-plus"></i> Tambah Pengguna
    </button>
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
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
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

    // ===== HELPERS =====
    function openModal(id)  { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function clearErrors(ids) {
        ids.forEach(function (id) {
            var el = document.getElementById(id);
            if (el) { el.textContent = ''; el.classList.remove('show'); }
        });
    }

    function showError(id, msg) {
        var el = document.getElementById(id);
        if (el) { el.textContent = msg; el.classList.add('show'); }
    }

    // ===== CLOSE TAMBAH =====
    function closeTambah() {
        closeModal('modal_tambah_pengguna');
        document.getElementById('name').value     = '';
        document.getElementById('email').value    = '';
        document.getElementById('password').value = '';
        document.getElementById('role_id').value  = '';
        clearErrors(['alert-name','alert-email','alert-password','alert-role_id']);
    }

    // ===== CLOSE EDIT =====
    function closeEdit() {
        closeModal('modal_edit_pengguna');
        clearErrors(['alert-edit-name','alert-edit-email','alert-edit-password','alert-edit-role_id']);
    }

    // ===== BIND CLOSE BUTTONS — di dalam ready() agar DOM pasti ada =====
    document.getElementById('cancel_modal_tambah_pengguna').addEventListener('click', closeTambah);
    document.getElementById('cancel_modal_tambah_pengguna_footer').addEventListener('click', closeTambah);
    document.getElementById('cancel_modal_edit_pengguna').addEventListener('click', closeEdit);
    document.getElementById('cancel_modal_edit_pengguna_footer').addEventListener('click', closeEdit);

    document.getElementById('modal_tambah_pengguna').addEventListener('click', function (e) {
        if (e.target === this) closeTambah();
    });
    document.getElementById('modal_edit_pengguna').addEventListener('click', function (e) {
        if (e.target === this) closeEdit();
    });

    // ===== DATATABLE =====
    var table = $('#table_id').DataTable({ paging: true });

    function renderOpsi(id) {
        return '<div style="display:flex;gap:6px;flex-wrap:wrap;">'
            + '<button class="btn-tbl-edit btn_edit_pengguna" data-id="' + id + '"><i class="far fa-edit"></i> Edit</button>'
            + '<button class="btn-tbl-hapus btn_hapus_pengguna" data-id="' + id + '"><i class="fas fa-trash"></i> Hapus</button>'
            + '</div>';
    }

    function loadPengguna() {
        $.ajax({
            url: '/data-pengguna/get-data', type: 'GET', dataType: 'JSON',
            success: function (response) {
                table.clear();
                var counter = 1;
                $.each(response.data, function (key, value) {
                    table.row.add($('<tr id="index_' + value.id + '">'
                        + '<td>' + counter++ + '</td>'
                        + '<td>' + value.name + '</td>'
                        + '<td>' + value.email + '</td>'
                        + '<td>' + (value.role ? value.role.role : '-') + '</td>'
                        + '<td>' + renderOpsi(value.id) + '</td>'
                        + '</tr>')).draw(false);
                });
            },
            error: function (xhr) { console.error('Load error:', xhr.responseText); }
        });
    }

    loadPengguna();

    // ===== BUKA MODAL TAMBAH =====
    document.getElementById('button_tambah_pengguna').addEventListener('click', function () {
        closeTambah();
        openModal('modal_tambah_pengguna');
    });

    // ===== STORE =====
    $('body').on('click', '#store_pengguna', function (e) {
        e.preventDefault();
        clearErrors(['alert-name','alert-email','alert-password','alert-role_id']);

        var btn = document.getElementById('store_pengguna');
        btn.disabled = true; btn.textContent = 'Menyimpan...';

        var formData = new FormData();
        formData.append('name',     document.getElementById('name').value);
        formData.append('email',    document.getElementById('email').value);
        formData.append('password', document.getElementById('password').value);
        formData.append('role_id',  document.getElementById('role_id').value);
        formData.append('_token',   document.querySelector('meta[name="csrf-token"]').content);

        $.ajax({
            url: '/data-pengguna', type: 'POST',
            data: formData, processData: false, contentType: false,
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                closeTambah();
                btn.disabled = false; btn.textContent = 'Tambah';
                loadPengguna();
            },
            error: function (xhr) {
                btn.disabled = false; btn.textContent = 'Tambah';
                if (xhr.status === 422 && xhr.responseJSON) {
                    var e = xhr.responseJSON;
                    if (e.name)     showError('alert-name',     e.name[0]);
                    if (e.email)    showError('alert-email',    e.email[0]);
                    if (e.password) showError('alert-password', e.password[0]);
                    if (e.role_id)  showError('alert-role_id',  e.role_id[0]);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan server.' });
                }
            }
        });
    });

    // ===== BUKA MODAL EDIT =====
    $('body').on('click', '.btn_edit_pengguna', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/data-pengguna/' + id + '/edit', type: 'GET',
            success: function (response) {
                var d = response.data;
                document.getElementById('pengguna_id').value   = d.id;
                document.getElementById('edit_name').value     = d.name;
                document.getElementById('edit_email').value    = d.email;
                document.getElementById('edit_password').value = '';
                document.getElementById('edit_role_id').value  = d.role_id;
                clearErrors(['alert-edit-name','alert-edit-email','alert-edit-password','alert-edit-role_id']);
                openModal('modal_edit_pengguna');
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Gagal memuat data' });
            }
        });
    });

    // ===== UPDATE =====
    $('body').on('click', '#update_pengguna', function (e) {
        e.preventDefault();
        clearErrors(['alert-edit-name','alert-edit-email','alert-edit-password','alert-edit-role_id']);

        var btn = document.getElementById('update_pengguna');
        btn.disabled = true; btn.textContent = 'Menyimpan...';

        var id       = document.getElementById('pengguna_id').value;
        var password = document.getElementById('edit_password').value;

        var formData = new FormData();
        formData.append('name',    document.getElementById('edit_name').value);
        formData.append('email',   document.getElementById('edit_email').value);
        formData.append('role_id', document.getElementById('edit_role_id').value);
        formData.append('_token',  document.querySelector('meta[name="csrf-token"]').content);
        formData.append('_method', 'PUT');
        if (password !== '') formData.append('password', password);

        $.ajax({
            url: '/data-pengguna/' + id, type: 'POST',
            data: formData, processData: false, contentType: false,
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                closeEdit();
                btn.disabled = false; btn.textContent = 'Simpan';
                loadPengguna();
            },
            error: function (xhr) {
                btn.disabled = false; btn.textContent = 'Simpan';
                if (xhr.status === 422 && xhr.responseJSON) {
                    var e = xhr.responseJSON;
                    if (e.name)     showError('alert-edit-name',     e.name[0]);
                    if (e.email)    showError('alert-edit-email',    e.email[0]);
                    if (e.password) showError('alert-edit-password', e.password[0]);
                    if (e.role_id)  showError('alert-edit-role_id',  e.role_id[0]);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan server.' });
                }
            }
        });
    });

    // ===== HAPUS =====
    $('body').on('click', '.btn_hapus_pengguna', function () {
        var id    = $(this).data('id');
        var token = document.querySelector('meta[name="csrf-token"]').content;
        Swal.fire({
            title: 'Apakah Kamu Yakin?', text: 'Data pengguna akan dihapus permanen!',
            icon: 'warning', showCancelButton: true,
            cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!', confirmButtonColor: '#e53e3e'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/data-pengguna/' + id, type: 'DELETE',
                    data: { _token: token },
                    success: function (response) {
                        Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                        loadPengguna();
                    },
                    error: function () {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Data tidak bisa dihapus.' });
                    }
                });
            }
        });
    });

});
</script>
@endpush
