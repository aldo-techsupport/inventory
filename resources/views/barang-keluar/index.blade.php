@extends('layouts.app')

@include('barang-keluar.create')

@section('content')
    <div class="section-header">
        <h1>Barang Keluar</h1>
        <div class="ml-auto">
            @if($canAdd)
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barangKeluar">
                <i class="fa fa-plus"></i> Barang Keluar
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
                                    <th>Tanggal Keluar</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Keluar</th>
                                    <th>Customer</th>
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

    var canAdd = {{ $canAdd ? 'true' : 'false' }};
    var table;

    // ================= HELPERS =================
    function openModal(id)  { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function generateKodeTransaksi() {
        var d = new Date();
        var tgl = d.getFullYear() + '-'
            + String(d.getMonth() + 1).padStart(2, '0') + '-'
            + String(d.getDate()).padStart(2, '0');
        var rnd = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        return 'TRX-OUT-' + tgl + '-' + rnd;
    }

    function setTodayDate() {
        var d = new Date();
        var tgl = d.getFullYear() + '-'
            + String(d.getMonth() + 1).padStart(2, '0') + '-'
            + String(d.getDate()).padStart(2, '0');
        document.getElementById('tanggal_keluar').value = tgl;
    }

    // ================= LOAD DATA TABLE =================
    function loadBarangKeluar() {
        table.clear();
        $.ajax({
            url: '/barang-keluar/get-data',
            type: 'GET',
            dataType: 'JSON',
            success: function (response) {
                var counter = 1;
                $.each(response.data, function (key, value) {
                    var customerName = value.customer ? value.customer.customer : '-';
                    var opsi = canAdd
                        ? `<button class="btn btn-danger btn-sm btn_hapus_barangKeluar" data-id="${value.id}">
                               <i class="fas fa-trash"></i> Hapus
                           </button>`
                        : '-';
                    table.row.add($(`
                        <tr id="index_${value.id}">
                            <td>${counter++}</td>
                            <td>${value.kode_transaksi}</td>
                            <td>${value.tanggal_keluar}</td>
                            <td>${value.barang ? value.barang.nama_barang : '-'}</td>
                            <td>${value.jumlah_keluar}</td>
                            <td>${customerName}</td>
                            <td>${opsi}</td>
                        </tr>`)).draw(false);
                });
            },
            error: function (xhr) {
                console.error('Load error:', xhr.responseText);
            }
        });
    }

    // ================= RESET MODAL =================
    function resetModal() {
        document.getElementById('kode_transaksi').value  = generateKodeTransaksi();
        document.getElementById('nama_barang').value     = '';
        document.getElementById('jumlah_keluar').value   = '';
        document.getElementById('stok').value            = '';
        document.getElementById('satuan_id').value       = '';
        document.getElementById('customer_id').value     = '';
        document.getElementById('form_customer_baru').style.display = 'none';
        document.getElementById('new_customer_nama').value     = '';
        document.getElementById('new_customer_alamat').value   = '';
        document.getElementById('new_customer_deskripsi').value = '';

        // Reset Select2 jika ada
        if ($.fn.select2 && $('#nama_barang').data('select2')) {
            $('#nama_barang').val('').trigger('change');
        }

        // Clear error states
        ['alert-tanggal_keluar','alert-kode_transaksi','alert-nama_barang',
         'alert-jumlah_keluar','alert-customer_id',
         'alert-new_customer_nama','alert-new_customer_alamat'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) { el.textContent = ''; el.classList.remove('show'); }
        });

        setTodayDate();
    }

    function closeTambahBarangKeluar() {
        closeModal('modal_tambah_barangKeluar');
        resetModal();
    }

    // ================= INIT =================
    $(document).ready(function () {

        table = $('#table_id').DataTable({ paging: true });
        loadBarangKeluar();
        setTodayDate();
        document.getElementById('kode_transaksi').value = generateKodeTransaksi();

        // Init Select2
        setTimeout(function () {
            if ($.fn.select2) {
                $('.js-example-basic-single').select2();
            }
        }, 300);

        // ---- OPEN MODAL ----
        $('body').on('click', '#button_tambah_barangKeluar', function () {
            resetModal();
            openModal('modal_tambah_barangKeluar');
        });

        // ---- CLOSE MODAL ----
        document.getElementById('cancel_modal_tambah_barangKeluar').addEventListener('click', closeTambahBarangKeluar);
        document.getElementById('cancel_modal_tambah_barangKeluar_footer').addEventListener('click', closeTambahBarangKeluar);
        document.getElementById('modal_tambah_barangKeluar').addEventListener('click', function (e) {
            if (e.target === this) closeTambahBarangKeluar();
        });

        // ---- SELECT BARANG → STOK & SATUAN ----
        $('body').on('change', '#nama_barang', function () {
            var nama_barang = $(this).find('option:selected').text();
            if (!nama_barang || $(this).val() === '') {
                document.getElementById('stok').value    = '';
                document.getElementById('satuan_id').value = '';
                return;
            }
            $.ajax({
                url: 'api/barang-keluar',
                type: 'GET',
                data: { nama_barang: nama_barang },
                success: function (response) {
                    document.getElementById('stok').value    = response.stok ?? '';
                    document.getElementById('satuan_id').value = response.satuan ?? '';
                }
            });
        });

        // ---- CUSTOMER BARU INLINE ----
        $('body').on('change', '#customer_id', function () {
            var formBaru = document.getElementById('form_customer_baru');
            if ($(this).val() === 'other') {
                document.getElementById('new_customer_nama').value     = '';
                document.getElementById('new_customer_alamat').value   = '';
                document.getElementById('new_customer_deskripsi').value = '';
                ['alert-new_customer_nama','alert-new_customer_alamat'].forEach(function (id) {
                    var el = document.getElementById(id);
                    if (el) { el.textContent = ''; el.classList.remove('show'); }
                });
                formBaru.style.display = 'block';
            } else {
                formBaru.style.display = 'none';
            }
        });

        $('body').on('click', '#btn_batal_customer_baru', function () {
            document.getElementById('customer_id').value = '';
            document.getElementById('form_customer_baru').style.display = 'none';
        });

        $('body').on('click', '#btn_simpan_customer_baru', function () {
            var nama      = document.getElementById('new_customer_nama').value.trim();
            var alamat    = document.getElementById('new_customer_alamat').value.trim();
            var deskripsi = document.getElementById('new_customer_deskripsi').value.trim();

            ['alert-new_customer_nama','alert-new_customer_alamat'].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) { el.textContent = ''; el.classList.remove('show'); }
            });

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
                    $('#btn_simpan_customer_baru').prop('disabled', false)
                        .html('<i class="fas fa-save"></i> Simpan Customer');

                    $('<option>', { value: response.data.id, text: response.data.customer })
                        .insertBefore('#customer_id option[value="other"]');

                    document.getElementById('customer_id').value = response.data.id;
                    document.getElementById('form_customer_baru').style.display = 'none';

                    Swal.fire({ icon: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                },
                error: function (xhr) {
                    $('#btn_simpan_customer_baru').prop('disabled', false)
                        .html('<i class="fas fa-save"></i> Simpan Customer');

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON;
                        if (errors.customer) {
                            var el = document.getElementById('alert-new_customer_nama');
                            el.textContent = errors.customer[0]; el.classList.add('show');
                        }
                        if (errors.alamat) {
                            var el2 = document.getElementById('alert-new_customer_alamat');
                            el2.textContent = errors.alamat[0]; el2.classList.add('show');
                        }
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal menyimpan customer' });
                    }
                }
            });
        });

        // ---- STORE BARANG KELUAR ----
        $(document).off('click', '#store_barangKeluar').on('click', '#store_barangKeluar', function (e) {
            e.preventDefault();

            var formData = new FormData();
            formData.append('kode_transaksi', document.getElementById('kode_transaksi').value);
            formData.append('tanggal_keluar', document.getElementById('tanggal_keluar').value);
            formData.append('nama_barang',    document.getElementById('nama_barang').value);
            formData.append('jumlah_keluar',  document.getElementById('jumlah_keluar').value);
            formData.append('customer_id',    document.getElementById('customer_id').value);
            formData.append('_token',         $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '/barang-keluar',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#store_barangKeluar').prop('disabled', true).text('Menyimpan...');
                    ['alert-tanggal_keluar','alert-kode_transaksi','alert-nama_barang',
                     'alert-jumlah_keluar','alert-customer_id'].forEach(function (id) {
                        var el = document.getElementById(id);
                        if (el) { el.textContent = ''; el.classList.remove('show'); }
                    });
                },
                success: function (response) {
                    Swal.fire({ icon: 'success', title: response.message });
                    closeTambahBarangKeluar();
                    $('#store_barangKeluar').prop('disabled', false).text('Tambah');
                    loadBarangKeluar();
                },
                error: function (xhr) {
                    $('#store_barangKeluar').prop('disabled', false).text('Tambah');

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON;

                        if (errors.message) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: errors.message, confirmButtonColor: '#4f46e5' });
                            return;
                        }

                        // Tampilkan error per field
                        var fieldMap = {
                            tanggal_keluar: 'alert-tanggal_keluar',
                            kode_transaksi: 'alert-kode_transaksi',
                            nama_barang:    'alert-nama_barang',
                            jumlah_keluar:  'alert-jumlah_keluar',
                            customer_id:    'alert-customer_id'
                        };
                        var hasFieldError = false;
                        $.each(fieldMap, function (field, alertId) {
                            if (errors[field]) {
                                var el = document.getElementById(alertId);
                                if (el) { el.textContent = errors[field][0]; el.classList.add('show'); }
                                hasFieldError = true;
                            }
                        });

                        if (!hasFieldError) {
                            var errorList = [];
                            $.each(errors, function (field, messages) {
                                if (Array.isArray(messages)) errorList.push(messages[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Periksa kembali inputan!',
                                html: '<ul style="text-align:left;padding-left:20px;margin:0;">'
                                    + errorList.map(function (m) { return '<li>' + m + '</li>'; }).join('')
                                    + '</ul>',
                                confirmButtonColor: '#4f46e5'
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: xhr.responseJSON?.message ?? 'Terjadi kesalahan, coba lagi.',
                            confirmButtonColor: '#4f46e5'
                        });
                    }
                }
            });
        });

        // ---- HAPUS BARANG KELUAR ----
        $('body').on('click', '.btn_hapus_barangKeluar', function () {
            var id    = $(this).data('id');
            var token = $("meta[name='csrf-token']").attr('content');

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: 'Data akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/barang-keluar/' + id,
                        type: 'DELETE',
                        data: { _token: token },
                        success: function (response) {
                            Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                            loadBarangKeluar();
                        },
                        error: function () {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Data tidak bisa dihapus' });
                        }
                    });
                }
            });
        });

    }); // end document.ready

}());
</script>
@endpush
