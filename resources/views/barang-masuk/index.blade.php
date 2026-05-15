@extends('layouts.app')

@section('page-title', 'Barang Masuk')

@include('barang-masuk.create')

@section('content')
    <div class="section-header">
        <h1>Barang Masuk</h1>
        <div class="ml-auto">
            @if($canAdd)
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barangMasuk">
                <i class="fa fa-plus"></i> Barang Masuk
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
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Masuk</th>
                                    <th>Supplier</th>
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

@push('scripts')
    <script>
    // Permission flag dari server
    var canAdd = {{ $canAdd ? 'true' : 'false' }};

    // ================= GENERATE KODE TRANSAKSI =================
    function generateKodeTransaksi() {
        var tanggal = new Date().toLocaleDateString('id-ID').split('/').reverse().join('-');
        var randomNumber = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        return 'TRX-IN-' + tanggal + '-' + randomNumber;
    }

    // ================= LOAD DATA TABLE =================
    function loadBarangMasuk() {
        let table = $('#table_id').DataTable();
        table.clear();

        $.ajax({
            url: "/barang-masuk/get-data",
            type: "GET",
            success: function (response) {
                let counter = 1;

                $.each(response.data, function (key, value) {
                    let row = `
                        <tr id="index_${value.id}">
                            <td>${counter++}</td>
                            <td>${value.kode_transaksi ?? '-'}</td>
                            <td>${value.tanggal_masuk ?? '-'}</td>
                            <td>${value.barang ? value.barang.nama_barang : '-'}</td>
                            <td>${value.jumlah_masuk ?? 0}</td>
                            <td>${value.supplier ? value.supplier.supplier : '-'}</td>
                            <td>
                                ${canAdd ? `<button class="btn btn-danger btn-sm delete-btn" data-id="${value.id}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>` : '-'}
                            </td>
                        </tr>
                    `;
                    table.row.add($(row)).draw(false);
                });
            },
            error: function (xhr) {
                console.error("Load error:", xhr.responseText);
            }
        });
    }

    $(document).ready(function () {

        // Init DataTable
        $('#table_id').DataTable({ paging: true });

        // Set tanggal hari ini
        var today = new Date();
        var formattedDate = today.getFullYear() + '-'
            + String(today.getMonth() + 1).padStart(2, '0') + '-'
            + String(today.getDate()).padStart(2, '0');
        $('#tanggal_masuk').val(formattedDate);

        // Set kode transaksi awal
        $('#kode_transaksi').val(generateKodeTransaksi());

        // Load data awal
        loadBarangMasuk();

        // ================= AUTOCOMPLETE BARANG =================
        $('#barang_id').on('change', function () {
            let barang_id = $(this).val();

            if (!barang_id) {
                $('#stok').val('');
                document.getElementById('satuan_text').textContent = '-';
                return;
            }

            $.ajax({
                url: '/barang-masuk/get-barang-detail',
                type: 'GET',
                data: { barang_id: barang_id },
                success: function (response) {
                    $('#stok').val(response.stok ?? 0);
                    document.getElementById('satuan_text').textContent = response.satuan ?? '-';
                },
                error: function (xhr) {
                    console.error("Detail error:", xhr.responseText);
                }
            });
        });

        // ================= SHOW MODAL TAMBAH =================
        $('body').on('click', '#button_tambah_barangMasuk', function () {
            $('#kode_transaksi').val(generateKodeTransaksi());
            document.getElementById('modal_tambah_barangMasuk').classList.add('active');
        });

        // Close modal
        document.getElementById('close_modal_barangMasuk').addEventListener('click', closeBarangMasukModal);
        document.getElementById('cancel_modal_barangMasuk').addEventListener('click', closeBarangMasukModal);
        document.getElementById('modal_tambah_barangMasuk').addEventListener('click', function(e) {
            if (e.target === this) closeBarangMasukModal();
        });

        function closeBarangMasukModal() {
            document.getElementById('modal_tambah_barangMasuk').classList.remove('active');
            $('#barang_id').val('').trigger('change');
            $('#supplier_id').val('').trigger('change');
            $('#jumlah_masuk').val('');
            $('#stok').val('');
            document.getElementById('satuan_text').textContent = '-';
            $('#kode_transaksi').val(generateKodeTransaksi());
        }

        // ================= STORE DATA =================
        $(document).off('click', '#store_barangMasuk').on('click', '#store_barangMasuk', function (e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('kode_transaksi', $('#kode_transaksi').val());
            formData.append('tanggal_masuk', $('#tanggal_masuk').val());
            formData.append('barang_id', $('#barang_id').val());
            formData.append('jumlah_masuk', $('#jumlah_masuk').val());
            formData.append('supplier_id', $('#supplier_id').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr("content"));

            $.ajax({
                url: '/barang-masuk',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('#store_barangMasuk').prop('disabled', true).text('Menyimpan...');
                    $('.alert').addClass('d-none');
                },

                success: function (res) {
                    Swal.fire({ icon: 'success', title: res.message });

                    closeBarangMasukModal();
                    $('#store_barangMasuk').prop('disabled', false).text('Tambah');

                    loadBarangMasuk();
                },

                error: function (xhr) {
                    $('#store_barangMasuk').prop('disabled', false).text('Tambah');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON;

                        if (errors.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: errors.message,
                                confirmButtonColor: '#4f46e5'
                            });
                            return;
                        }

                        let errorList = [];
                        $.each(errors, function(field, messages) {
                            if (Array.isArray(messages)) errorList.push(messages[0]);
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Periksa kembali inputan!',
                            html: '<ul style="text-align:left; padding-left:20px; margin:0;">'
                                + errorList.map(m => `<li>${m}</li>`).join('')
                                + '</ul>',
                            confirmButtonColor: '#4f46e5'
                        });

                    } else {
                        let msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan, coba lagi.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: msg,
                            confirmButtonColor: '#4f46e5'
                        });
                    }
                }
            });
        });

        // ================= HAPUS DATA =================
        $(document).on('click', '.delete-btn', function () {
            let id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "Data akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'YA, HAPUS!',
                cancelButtonText: 'BATAL'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/barang-masuk/${id}`,
                        type: "DELETE",
                        data: { _token: token },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadBarangMasuk();
                        }
                    });
                }
            });
        });

    });
    </script>
@endpush
@endsection
