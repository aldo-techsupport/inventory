@extends('layouts.app')

@section('page-title', 'Satuan Barang')

@include('satuan-barang.create')
@include('satuan-barang.edit')

@section('content')
    <div class="section-header">
        <h1>Satuan Barang</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_satuan">
                <i class="fa fa-plus"></i> Satuan Barang
            </a>
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
                                    <th>Satuan Barang</th>
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
(function () {
    function openModal(id)  { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    ['cancel_modal_tambah_satuan', 'cancel_modal_tambah_satuan_footer'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', function () { closeModal('modal_tambah_satuan'); });
    });
    ['cancel_modal_edit_satuan', 'cancel_modal_edit_satuan_footer'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', function () { closeModal('modal_edit_satuan'); });
    });
    document.getElementById('modal_tambah_satuan').addEventListener('click', function (e) {
        if (e.target === this) closeModal('modal_tambah_satuan');
    });
    document.getElementById('modal_edit_satuan').addEventListener('click', function (e) {
        if (e.target === this) closeModal('modal_edit_satuan');
    });

    function reloadTable() {
        $.ajax({
            url: '/satuan-barang/get-data', type: 'GET', dataType: 'JSON',
            success: function (response) {
                var dt = $('#table_id').DataTable();
                dt.clear();
                var counter = 1;
                $.each(response.data, function (key, value) {
                    dt.row.add($(`
                        <tr id="index_${value.id}">
                            <td>${counter++}</td>
                            <td>${value.satuan_barang}</td>
                            <td>
                                <a href="javascript:void(0)" class="btn_edit_satuan btn btn-icon btn-warning btn-lg mb-2" data-id="${value.id}"><i class="far fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn_hapus_satuan btn btn-icon btn-danger btn-lg mb-2" data-id="${value.id}"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>`)).draw(false);
                });
            }
        });
    }

    $(document).ready(function () {
        $('#table_id').DataTable({ paging: true });
        reloadTable();
    });

    $('body').on('click', '#button_tambah_satuan', function () {
        $('#satuan').val('');
        $('#alert-satuan').removeClass('show').text('');
        openModal('modal_tambah_satuan');
    });

    $('body').on('click', '#store_satuan', function (e) {
        e.preventDefault();
        var satuan = $('#satuan').val();
        var token  = $("meta[name='csrf-token']").attr('content');
        $('#alert-satuan').removeClass('show').text('');

        var formData = new FormData();
        formData.append('satuan', satuan);
        formData.append('_token', token);

        $.ajax({
            url: '/satuan-barang', type: 'POST', cache: false,
            data: formData, contentType: false, processData: false,
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, showConfirmButton: true, timer: 3000 });
                reloadTable();
                $('#satuan').val('');
                closeModal('modal_tambah_satuan');
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.satuan && error.responseJSON.satuan[0]) {
                    $('#alert-satuan').addClass('show').html(error.responseJSON.satuan[0]);
                }
            }
        });
    });

    $('body').on('click', '.btn_edit_satuan', function () {
        var satuan_id = $(this).data('id');
        $.ajax({
            url: `/satuan-barang/${satuan_id}/edit`, type: 'GET', cache: false,
            success: function (response) {
                $('#satuan_id').val(response.data.id);
                $('#edit_satuan').val(response.data.satuan_barang);
                $('#alert-edit-satuan').removeClass('show').text('');
                openModal('modal_edit_satuan');
            }
        });
    });

    $('body').on('click', '#update_satuan', function (e) {
        e.preventDefault();
        var satuan_id = $('#satuan_id').val();
        var satuan    = $('#edit_satuan').val();
        var token     = $("meta[name='csrf-token']").attr('content');
        $('#alert-edit-satuan').removeClass('show').text('');

        var formData = new FormData();
        formData.append('satuan', satuan);
        formData.append('_token', token);
        formData.append('_method', 'PUT');

        $.ajax({
            url: `/satuan-barang/${satuan_id}`, type: 'POST', cache: false,
            data: formData, contentType: false, processData: false,
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, showConfirmButton: true, timer: 3000 });
                $(`#index_${response.data.id}`).find('td').eq(1).text(response.data.satuan_barang);
                closeModal('modal_edit_satuan');
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.satuan && error.responseJSON.satuan[0]) {
                    $('#alert-edit-satuan').addClass('show').html(error.responseJSON.satuan[0]);
                }
            }
        });
    });

    $('body').on('click', '.btn_hapus_satuan', function () {
        var satuan_id = $(this).data('id');
        var token     = $("meta[name='csrf-token']").attr('content');
        Swal.fire({
            title: 'Apakah Kamu Yakin?', text: 'ingin menghapus data ini!', icon: 'warning',
            showCancelButton: true, cancelButtonText: 'TIDAK', confirmButtonText: 'YA, HAPUS!'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/satuan-barang/${satuan_id}`, type: 'DELETE', cache: false,
                    data: { '_token': token },
                    success: function (response) {
                        Swal.fire({ icon: 'success', title: response.message, showConfirmButton: true, timer: 3000 });
                        reloadTable();
                    }
                });
            }
        });
    });
}());
</script>
@endpush
