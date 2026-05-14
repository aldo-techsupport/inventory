@extends('layouts.app')

@include('barang-keluar.create')

@section('content')
    <div class="section-header">
        <h1>Barang Keluar</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barangKeluar"><i class="fa fa-plus"></i>
                Barang Keluar</a>
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
                                    <th>Tanggal Keluar</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Keluar</th>
                                    <th>Customer</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Select2 Autocomplete -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.js-example-basic-single').select2();

                $('#nama_barang').on('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var nama_barang = selectedOption.text();

                    $.ajax({
                        url: 'api/barang-keluar',
                        type: 'GET',
                        data: {
                            nama_barang: nama_barang,
                        },
                        success: function(response) {
                            if (response && (response.stok || response.stok === 0)) {
                                $('#stok').val(response.stok);
                                $('#satuan_id').val(response.satuan ?? '');
                            } else if (response && response.stok === 0) {
                                $('#stok').val(0);
                                $('#satuan_id').val('');
                            }
                        },
                    });
                });
            }, 500);
        });
    </script>

    <!-- Datatable -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                paging: true
            });

            $.ajax({
                url: "/barang-keluar/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let customerName = value.customer ? value.customer.customer : '-';
                        let barangKeluar = `
                <tr class="barang-row" id="index_${value.id}">
                    <td>${counter++}</td>
                    <td>${value.kode_transaksi}</td>
                    <td>${value.tanggal_keluar}</td>
                    <td>${value.barang ? value.barang.nama_barang : '-'}</td>
                    <td>${value.jumlah_keluar}</td>
                    <td>${customerName}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" id="button_hapus_barangKeluar" data-id="${value.id}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            `;
                        $('#table_id').DataTable().row.add($(barangKeluar)).draw(false);
                    });
                }
            });
        });
    </script>

    <!-- Generate Kode Transaksi Otomatis -->
    <script>
        function generateKodeTransaksi() {
            var tanggal = new Date().toLocaleDateString('id-ID').split('/').reverse().join('-');
            var randomNumber = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            var kodeTransaksi = 'TRX-OUT-' + tanggal + '-' + randomNumber;

            $('#kode_transaksi').val(kodeTransaksi);
            return kodeTransaksi;
        }

        $(document).ready(function() {
            generateKodeTransaksi();
        });
    </script>

    <!-- Show Modal Tambah Jenis Barang -->
    <script>
        // ================= SHOW MODAL =================
$(document).on('click', '#button_tambah_barangKeluar', function () {
    $('#modal_tambah_barangKeluar').modal('show');
    $('#kode_transaksi').val(generateKodeTransaksi());
});


// ================= STORE DATA =================
$(document).off('click', '#store_barangKeluar').on('click', '#store_barangKeluar', function (e) {

    e.preventDefault();

    let formData = new FormData();

    formData.append('kode_transaksi', $('#kode_transaksi').val());
    formData.append('tanggal_keluar', $('#tanggal_keluar').val());
    formData.append('nama_barang', $('#nama_barang').val());
    formData.append('jumlah_keluar', $('#jumlah_keluar').val());
    formData.append('customer_id', $('#customer_id').val());
    formData.append('_token', $('meta[name="csrf-token"]').attr("content"));

    $.ajax({
        url: '/barang-keluar',
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        beforeSend: function () {
            $('#store_barangKeluar')
                .prop('disabled', true)
                .text('Menyimpan...');
            $('.alert').addClass('d-none');
        },

        success: function (response) {

            console.log("SUCCESS:", response);

            Swal.fire({
                icon: 'success',
                title: response.message
            });

            $('#modal_tambah_barangKeluar').modal('hide');

            $('#store_barangKeluar')
                .prop('disabled', false)
                .text('Tambah');

            // reset form
            $('#kode_transaksi').val('');
            $('#nama_barang').val('');
            $('#jumlah_keluar').val('');
            $('#stok').val('');

            // 🔥 reload data clean
            loadBarangKeluar();

        },

        error: function (xhr) {

            console.log("ERROR:", xhr.responseText);

            $('#store_barangKeluar')
                .prop('disabled', false)
                .text('Tambah');

            if (xhr.status === 422) {

                let errors = xhr.responseJSON;

                // Cek apakah ada pesan tunggal (misal: stok tidak cukup)
                if (errors.message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errors.message,
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }

                // Kumpulkan semua error validasi
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


// ================= LOAD DATA =================
function loadBarangKeluar() {

    let table = $('#table_id').DataTable();
    table.clear();

    $.ajax({
        url: "/barang-keluar/get-data",
        type: "GET",

        success: function (response) {

            let counter = 1;

            $.each(response.data, function (key, value) {

                let customerName = value.customer ? value.customer.customer : '-';

                let row = `
                    <tr id="index_${value.id}">
                        <td>${counter++}</td>
                        <td>${value.kode_transaksi}</td>
                        <td>${value.tanggal_keluar}</td>
                        <td>${value.barang ? value.barang.nama_barang : '-'}</td>
                        <td>${value.jumlah_keluar}</td>
                        <td>${customerName}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" id="button_hapus_barangKeluar" data-id="${value.id}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;

                table.row.add($(row)).draw(false);

            });

        }
    });
}
    </script>


    <!-- Hapus Data Barang -->
    <script>
        $('body').on('click', '#button_hapus_barangKeluar', function() {
            let barangKeluar_id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "ingin menghapus data ini !",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/barang-keluar/${barangKeluar_id}`,
                        type: "DELETE",
                        cache: false,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            Swal.fire({
                                type: 'success',
                                icon: 'success',
                                title: `${response.message}`,
                                showConfirmButton: true,
                                timer: 3000
                            });
                            $(`#index_${barangKeluar_id}`).remove();

                            $.ajax({
                                url: "/barang-keluar/get-data",
                                type: "GET",
                                dataType: 'JSON',
                                success: function(response) {
                                    let counter = 1;
                                    $('#table_id').DataTable().clear();
                                    $.each(response.data, function(key, value) {
                                        let customerName = value.customer ? value.customer.customer : '-';
                                        let barangKeluar = `
                                        <tr class="barang-row" id="index_${value.id}">
                                            <td>${counter++}</td>
                                            <td>${value.kode_transaksi}</td>
                                            <td>${value.tanggal_keluar}</td>
                                            <td>${value.barang ? value.barang.nama_barang : '-'}</td>
                                            <td>${value.jumlah_keluar}</td>
                                            <td>${customerName}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" id="button_hapus_barangKeluar" data-id="${value.id}">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    `;
                                        $('#table_id').DataTable().row.add(
                                            $(barangKeluar)).draw(false);
                                    });
                                }
                            });
                        }
                    })
                }
            });
        });
    </script>

    <!-- Create Tanggal -->
    <script>
        // Mendapatkan tanggal hari ini
        var today = new Date();

        // Mendapatkan nilai tahun, bulan, dan tanggal
        var year = today.getFullYear();
        var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Ditambahkan +1 karena indeks bulan dimulai dari 0
        var day = today.getDate().toString().padStart(2, '0');

        // Menggabungkan nilai tahun, bulan, dan tanggal menjadi format "YYYY-MM-DD"
        var formattedDate = year + '-' + month + '-' + day;

        // Mengisi nilai input field dengan tanggal hari ini
        document.getElementById('tanggal_keluar').value = formattedDate;
    </script>

    <!-- Customer Baru Inline -->
    <script>
        $(document).on('change', '#customer_id', function () {
            if ($(this).val() === 'other') {
                $('#new_customer_nama').val('');
                $('#new_customer_alamat').val('');
                $('#new_customer_deskripsi').val('');
                $('#alert-new_customer_nama').addClass('d-none').text('');
                $('#alert-new_customer_alamat').addClass('d-none').text('');
                $('#form_customer_baru').slideDown(200);
            } else {
                $('#form_customer_baru').slideUp(200);
            }
        });

        $(document).on('click', '#btn_batal_customer_baru', function () {
            $('#customer_id').val('');
            $('#form_customer_baru').slideUp(200);
        });

        $(document).on('click', '#btn_simpan_customer_baru', function () {
            let nama      = $('#new_customer_nama').val().trim();
            let alamat    = $('#new_customer_alamat').val().trim();
            let deskripsi = $('#new_customer_deskripsi').val().trim();

            $('#alert-new_customer_nama').addClass('d-none').text('');
            $('#alert-new_customer_alamat').addClass('d-none').text('');

            $.ajax({
                url: '/customer',
                type: 'POST',
                data: {
                    customer:  nama,
                    alamat:    alamat,
                    deskripsi: deskripsi,
                    _token:    $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#btn_simpan_customer_baru').prop('disabled', true).text('Menyimpan...');
                },
                success: function (response) {
                    $('#btn_simpan_customer_baru').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Customer');

                    $('<option>', {
                        value: response.data.id,
                        text:  response.data.customer
                    }).insertBefore('#customer_id option[value="other"]');

                    $('#customer_id').val(response.data.id);
                    $('#form_customer_baru').slideUp(200);

                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    $('#btn_simpan_customer_baru').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Customer');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON;
                        if (errors.customer)
                            $('#alert-new_customer_nama').removeClass('d-none').text(errors.customer[0]);
                        if (errors.alamat)
                            $('#alert-new_customer_alamat').removeClass('d-none').text(errors.alamat[0]);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal menyimpan customer' });
                    }
                }
            });
        });
    </script>
@endsection
