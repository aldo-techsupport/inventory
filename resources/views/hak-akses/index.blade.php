@extends('layouts.app')

@section('page-title', 'Hak Akses')

@include('hak-akses.create')
@include('hak-akses.edit')

@section('content')

<style>
  .btn-tbl-akses,
  .btn-tbl-edit,
  .btn-tbl-hapus {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border: none;
    border-radius: 7px;
    font-size: 13px;
    font-family: inherit;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.18s, transform 0.12s;
  }
  .btn-tbl-akses { background: #dbeafe; color: #1d4ed8; }
  .btn-tbl-akses:hover { background: #bfdbfe; transform: translateY(-1px); }
  .btn-tbl-edit  { background: #fff3cd; color: #856404; }
  .btn-tbl-edit:hover  { background: #ffe69c; transform: translateY(-1px); }
  .btn-tbl-hapus { background: #ffe0e0; color: #b91c1c; }
  .btn-tbl-hapus:hover { background: #ffc9c9; transform: translateY(-1px); }
</style>

<div class="section-header">
    <h1>Hak Akses</h1>
    <div class="ml-auto">
        <button type="button" class="btn btn-primary" id="button_tambah_role">
            <i class="fa fa-plus"></i> Tambah Role
        </button>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa fa-lock mr-2"></i> {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Role</th>
                                <th>Deskripsi</th>
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

    function clearError(id) {
        var el = document.getElementById(id);
        if (el) { el.textContent = ''; el.classList.remove('show'); }
    }
    function showError(id, msg) {
        var el = document.getElementById(id);
        if (el) { el.textContent = msg; el.classList.add('show'); }
    }

    // ===== CLOSE TAMBAH =====
    function closeTambah() {
        closeModal('modal_tambah_role');
        document.getElementById('role').value      = '';
        document.getElementById('deskripsi').value = '';
        clearError('alert-role'); clearError('alert-deskripsi');
    }

    // ===== CLOSE EDIT =====
    function closeEdit() {
        closeModal('modal_edit_role');
        clearError('alert-edit-role'); clearError('alert-edit-deskripsi');
    }

    // ===== BIND CLOSE BUTTONS — di dalam ready() agar DOM pasti ada =====
    document.getElementById('cancel_modal_tambah_role').addEventListener('click', closeTambah);
    document.getElementById('cancel_modal_tambah_role_footer').addEventListener('click', closeTambah);
    document.getElementById('cancel_modal_edit_role').addEventListener('click', closeEdit);
    document.getElementById('cancel_modal_edit_role_footer').addEventListener('click', closeEdit);

    document.getElementById('modal_tambah_role').addEventListener('click', function (e) {
        if (e.target === this) closeTambah();
    });
    document.getElementById('modal_edit_role').addEventListener('click', function (e) {
        if (e.target === this) closeEdit();
    });

    // ===== DATATABLE =====
    var table = $('#table_id').DataTable({ paging: true });

    function renderOpsi(id, isLocked) {
        if (isLocked) {
            return '<span style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;'
                + 'background:#f1f5f9;color:#94a3b8;border-radius:20px;font-size:12px;">'
                + '<i class="fas fa-lock"></i> Terkunci</span>';
        }
        return '<div style="display:flex;gap:6px;flex-wrap:wrap;">'
            + '<a href="/hak-akses/' + id + '/permissions" class="btn-tbl-akses" title="Atur Akses Menu">'
            + '<i class="fas fa-user-shield"></i> Akses</a>'
            + '<button class="btn-tbl-edit btn_edit_role" data-id="' + id + '">'
            + '<i class="far fa-edit"></i> Edit</button>'
            + '<button class="btn-tbl-hapus btn_hapus_role" data-id="' + id + '">'
            + '<i class="fas fa-trash"></i> Hapus</button>'
            + '</div>';
    }

    function reloadTable() {
        $.ajax({
            url: '/hak-akses/get-data', type: 'GET', dataType: 'JSON',
            success: function (response) {
                table.clear();
                var counter = 1;
                $.each(response.data, function (key, value) {
                    var isLocked = counter === 1;
                    var rowStyle = isLocked ? 'background:#f8f9fa;color:#aaa;' : '';
                    table.row.add($('<tr class="role-row" id="index_' + value.id + '" style="' + rowStyle + '">'
                        + '<td>' + counter + '</td>'
                        + '<td>' + value.role + '</td>'
                        + '<td>' + (value.deskripsi || '-') + '</td>'
                        + '<td>' + renderOpsi(value.id, isLocked) + '</td>'
                        + '</tr>')).draw(false);
                    counter++;
                });
            }
        });
    }

    reloadTable();

    // ===== BUKA MODAL TAMBAH =====
    document.getElementById('button_tambah_role').addEventListener('click', function () {
        closeTambah();
        openModal('modal_tambah_role');
    });

    // ===== STORE =====
    $('body').on('click', '#store_role', function (e) {
        e.preventDefault();
        clearError('alert-role'); clearError('alert-deskripsi');

        var btn = document.getElementById('store_role');
        btn.disabled = true; btn.textContent = 'Menyimpan...';

        var formData = new FormData();
        formData.append('role',      document.getElementById('role').value);
        formData.append('deskripsi', document.getElementById('deskripsi').value);
        formData.append('_token',    document.querySelector('meta[name="csrf-token"]').content);

        $.ajax({
            url: '/hak-akses', type: 'POST',
            data: formData, processData: false, contentType: false,
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                closeTambah();
                btn.disabled = false; btn.textContent = 'Tambah';
                reloadTable();
            },
            error: function (xhr) {
                btn.disabled = false; btn.textContent = 'Tambah';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.role)      showError('alert-role',      xhr.responseJSON.role[0]);
                    if (xhr.responseJSON.deskripsi) showError('alert-deskripsi', xhr.responseJSON.deskripsi[0]);
                }
            }
        });
    });

    // ===== BUKA MODAL EDIT =====
    $('body').on('click', '.btn_edit_role', function () {
        var role_id = $(this).data('id');
        $.ajax({
            url: '/hak-akses/' + role_id + '/edit', type: 'GET',
            success: function (response) {
                document.getElementById('role_id').value       = response.data.id;
                document.getElementById('edit_role').value     = response.data.role;
                document.getElementById('edit_deskripsi').value = response.data.deskripsi || '';
                clearError('alert-edit-role'); clearError('alert-edit-deskripsi');
                openModal('modal_edit_role');
            }
        });
    });

    // ===== UPDATE =====
    $('body').on('click', '#update_role', function (e) {
        e.preventDefault();
        clearError('alert-edit-role'); clearError('alert-edit-deskripsi');

        var btn = document.getElementById('update_role');
        btn.disabled = true; btn.textContent = 'Menyimpan...';

        var role_id = document.getElementById('role_id').value;
        var formData = new FormData();
        formData.append('role',      document.getElementById('edit_role').value);
        formData.append('deskripsi', document.getElementById('edit_deskripsi').value);
        formData.append('_token',    document.querySelector('meta[name="csrf-token"]').content);
        formData.append('_method',   'PUT');

        $.ajax({
            url: '/hak-akses/' + role_id, type: 'POST',
            data: formData, processData: false, contentType: false,
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                closeEdit();
                btn.disabled = false; btn.textContent = 'Simpan';
                reloadTable();
            },
            error: function (xhr) {
                btn.disabled = false; btn.textContent = 'Simpan';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.role)      showError('alert-edit-role',      xhr.responseJSON.role[0]);
                    if (xhr.responseJSON.deskripsi) showError('alert-edit-deskripsi', xhr.responseJSON.deskripsi[0]);
                }
            }
        });
    });

    // ===== HAPUS =====
    $('body').on('click', '.btn_hapus_role', function () {
        var role_id = $(this).data('id');
        var token   = document.querySelector('meta[name="csrf-token"]').content;
        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: 'Menghapus role ini juga akan menghapus semua pengaturan akses menu-nya!',
            icon: 'warning', showCancelButton: true,
            cancelButtonText: 'TIDAK', confirmButtonText: 'YA, HAPUS!'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/hak-akses/' + role_id, type: 'DELETE',
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
