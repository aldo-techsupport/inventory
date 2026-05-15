@extends('layouts.app')

@include('supplier.create')
@include('supplier.edit')

@section('content')
    <div class="section-header">
        <h1>Data Supplier</h1>
        <div class="ml-auto">
            @if($canAdd)
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_supplier">
                <i class="fa fa-plus"></i> Supplier
            </a>
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
                                    <th>Nama Perusahaan</th>
                                    <th>Alamat</th>
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

    var canAdd = {{ $canAdd ? 'true' : 'false' }};
    var table  = $('#table_id').DataTable({ paging: true });

    // ================= LOAD DATA =================
    function loadSupplier() {
        table.clear();
        $.ajax({
            url: '/supplier/get-data',
            type: 'GET',
            dataType: 'JSON',
            success: function (response) {
                let counter = 1;
                $.each(response.data, function (key, value) {
                    let opsi = canAdd
                        ? `<a href="javascript:void(0)" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 button_edit_supplier"><i class="far fa-edit"></i></a>
                           <a href="javascript:void(0)" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2 button_hapus_supplier"><i class="fas fa-trash"></i></a>`
                        : '-';
                    let row = `
                        <tr id="index_${value.id}">
                            <td>${counter++}</td>
                            <td>${value.supplier}</td>
                            <td>${value.alamat}</td>
                            <td>${value.deskripsi ?? '-'}</td>
                            <td>${opsi}</td>
                        </tr>`;
                    table.row.add($(row)).draw(false);
                });
            }
        });
    }

    loadSupplier();

    // ================= OPEN MODAL TAMBAH =================
    $('body').on('click', '#button_tambah_supplier', function () {
        document.getElementById('modal_tambah_supplier').classList.add('active');
    });

    function closeTambahSupplier() {
        document.getElementById('modal_tambah_supplier').classList.remove('active');
        document.getElementById('supplier').value    = '';
        document.getElementById('alamat').value      = '';
        document.getElementById('deskripsi').value   = '';
        document.getElementById('alert-supplier').classList.remove('show');
        document.getElementById('alert-alamat').classList.remove('show');
    }

    document.getElementById('close_modal_tambah_supplier').addEventListener('click', closeTambahSupplier);
    document.getElementById('cancel_modal_tambah_supplier').addEventListener('click', closeTambahSupplier);
    document.getElementById('modal_tambah_supplier').addEventListener('click', function (e) {
        if (e.target === this) closeTambahSupplier();
    });

    // ================= STORE =================
    $('body').on('click', '#store_supplier', function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('supplier', document.getElementById('supplier').value);
        formData.append('alamat',   document.getElementById('alamat').value);
        formData.append('deskripsi',document.getElementById('deskripsi').value);
        formData.append('_token',   $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: '/supplier',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            beforeSend: function () {
                $('#store_supplier').prop('disabled', true).text('Menyimpan...');
                document.getElementById('alert-supplier').classList.remove('show');
                document.getElementById('alert-alamat').classList.remove('show');
            },

            success: function (res) {
                Swal.fire({ icon: 'success', title: res.message });
                closeTambahSupplier();
                $('#store_supplier').prop('disabled', false).text('Tambah');
                loadSupplier();
            },

            error: function (xhr) {
                $('#store_supplier').prop('disabled', false).text('Tambah');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON;
                    if (errors.supplier) {
                        let el = document.getElementById('alert-supplier');
                        el.textContent = errors.supplier[0];
                        el.classList.add('show');
                    }
                    if (errors.alamat) {
                        let el = document.getElementById('alert-alamat');
                        el.textContent = errors.alamat[0];
                        el.classList.add('show');
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Server Error', text: xhr.responseText.substring(0, 200) });
                }
            }
        });
    });

    // ================= OPEN MODAL EDIT =================
    $('body').on('click', '.button_edit_supplier', function () {
        let supplier_id = $(this).data('id');

        $.ajax({
            url: `/supplier/${supplier_id}/edit`,
            type: 'GET',
            success: function (response) {
                let data = response.data;
                document.getElementById('supplier_id').value    = data.id;
                document.getElementById('edit_supplier').value  = data.supplier;
                document.getElementById('edit_alamat').value    = data.alamat;
                document.getElementById('edit_deskripsi').value = data.deskripsi ?? '';
                document.getElementById('alert-edit-supplier').classList.remove('show');
                document.getElementById('alert-edit-alamat').classList.remove('show');
                document.getElementById('modal_edit_supplier').classList.add('active');
            },
            error: function (xhr) {
                console.error('ERROR EDIT:', xhr.responseText);
            }
        });
    });

    function closeEditSupplier() {
        document.getElementById('modal_edit_supplier').classList.remove('active');
    }

    document.getElementById('close_modal_edit_supplier').addEventListener('click', closeEditSupplier);
    document.getElementById('cancel_modal_edit_supplier').addEventListener('click', closeEditSupplier);
    document.getElementById('modal_edit_supplier').addEventListener('click', function (e) {
        if (e.target === this) closeEditSupplier();
    });

    // ================= UPDATE =================
    $('body').on('click', '#update_supplier', function (e) {
        e.preventDefault();

        let supplier_id = document.getElementById('supplier_id').value;
        if (!supplier_id) { alert('ID tidak ditemukan'); return; }

        let formData = new FormData();
        formData.append('supplier',  document.getElementById('edit_supplier').value);
        formData.append('alamat',    document.getElementById('edit_alamat').value);
        formData.append('deskripsi', document.getElementById('edit_deskripsi').value);
        formData.append('_token',    $('meta[name="csrf-token"]').attr('content'));
        formData.append('_method',   'PUT');

        $.ajax({
            url: `/supplier/${supplier_id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            beforeSend: function () {
                $('#update_supplier').prop('disabled', true).text('Menyimpan...');
                document.getElementById('alert-edit-supplier').classList.remove('show');
                document.getElementById('alert-edit-alamat').classList.remove('show');
            },

            success: function (res) {
                Swal.fire({ icon: 'success', title: res.message });
                closeEditSupplier();
                $('#update_supplier').prop('disabled', false).text('Update');
                loadSupplier();
            },

            error: function (xhr) {
                $('#update_supplier').prop('disabled', false).text('Update');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON;
                    if (errors.supplier) {
                        let el = document.getElementById('alert-edit-supplier');
                        el.textContent = errors.supplier[0];
                        el.classList.add('show');
                    }
                    if (errors.alamat) {
                        let el = document.getElementById('alert-edit-alamat');
                        el.textContent = errors.alamat[0];
                        el.classList.add('show');
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Server Error', text: xhr.responseText.substring(0, 200) });
                }
            }
        });
    });

    // ================= HAPUS =================
    $('body').on('click', '.button_hapus_supplier', function () {
        let supplier_id = $(this).data('id');
        let token       = $("meta[name='csrf-token']").attr('content');

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: 'Data akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, HAPUS!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/supplier/${supplier_id}`,
                    type: 'DELETE',
                    data: { _token: token },
                    success: function (response) {
                        Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                        loadSupplier();
                    }
                });
            }
        });
    });

});
</script>
@endpush
