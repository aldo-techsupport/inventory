@extends('layouts.app')

@section('page-title', 'Data Barang')

@include('barang.create')
@include('barang.edit')
@include('barang.show')

@section('content')
    <div class="section-header">
        <h1>Data Barang</h1>
        <div class="ml-auto">
            @if($canAdd)
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barang">
                <i class="fa fa-plus"></i> Tambah Barang
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
                                    <th>Foto</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
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
var table;
var canAdd      = {{ $canAdd ? 'true' : 'false' }};
var currentSlide = 0;
var totalSlide   = 0;

// ===================== RENDER GAMBAR =====================
function renderGambar(row) {
    let images = [];
    if (row.gambar) {
        try {
            images = typeof row.gambar === 'string' ? JSON.parse(row.gambar) : row.gambar;
        } catch (e) { images = []; }
    }
    if (!images || images.length === 0) {
        return `<div style="width:80px;height:80px;background:#f1f1f1;border-radius:6px;
                    display:flex;align-items:center;justify-content:center;font-size:11px;color:#999;">
                    No Image</div>`;
    }
    return `<div style="position:relative;display:inline-block;">
                <img src="/storage/${images[0]}" width="80"
                     style="border-radius:6px;object-fit:cover;height:80px;">
                ${images.length > 1
                    ? `<span style="position:absolute;bottom:0;right:0;background:black;color:white;
                                    font-size:10px;padding:2px 5px;border-radius:5px;">+${images.length - 1}</span>`
                    : ''}
            </div>`;
}

// ===================== LOAD DATA =====================
function loadData() {
    $.ajax({
        url: '/barang/get-data',
        type: 'GET',
        dataType: 'JSON',
        success: function (response) {
            let counter = 1;
            table.clear();
            $.each(response.data, function (key, value) {
                let stok      = value.stok != null ? value.stok : 'Stok Kosong';
                let imageHtml = renderGambar(value);
                let barang = `
                    <tr class="barang-row" id="index_${value.id}">
                        <td>${counter++}</td>
                        <td>${imageHtml}</td>
                        <td>${value.kode_barang}</td>
                        <td>${value.nama_barang}</td>
                        <td>${stok}</td>
                        <td>
                            <a href="javascript:void(0)" data-id="${value.id}"
                               class="btn btn-icon btn-success btn-lg mb-2 button_detail_barang">
                               <i class="far fa-eye"></i></a>
                            ${canAdd ? `
                            <a href="javascript:void(0)" data-id="${value.id}"
                               class="btn btn-icon btn-warning btn-lg mb-2 button_edit_barang">
                               <i class="far fa-edit"></i></a>
                            <a href="javascript:void(0)" data-id="${value.id}"
                               class="btn btn-icon btn-danger btn-lg mb-2 button_hapus_barang">
                               <i class="fas fa-trash"></i></a>
                            ` : ''}
                        </td>
                    </tr>`;
                table.row.add($(barang)).draw(false);
            });
        }
    });
}

// ===================== HELPER: CETAK PDF =====================
function setCetakPdf(id) {
    document.getElementById('btnCetakPdf').href = `/barang/cetak-pdf/${id}`;
}

// ===================== HELPER: DESKRIPSI READ MORE =====================
function loadDeskripsi(text) {
    var desc = document.getElementById('detail_deskripsi');
    var btn  = document.getElementById('btn_readmore');
    if (!desc || !btn) return;
    desc.innerHTML = (text || '-').replace(/\n/g, '<br>');
    desc.classList.remove('open');
    btn.innerText = 'Read More';
    btn.style.display = 'none';
    requestAnimationFrame(function () {
        if (desc.scrollHeight > desc.clientHeight + 2) {
            btn.style.display = 'inline-block';
        }
    });
    btn.onclick = function () {
        var open = desc.classList.toggle('open');
        btn.innerText = open ? 'Read Less' : 'Read More';
    };
}

// ===================== SLIDER =====================
function renderSlider(images) {
    var html = '';
    if (images.length > 0) {
        html = images.map(function (img) {
            var path = img.replace(/^\/+/, '').replace(/^storage\//, '');
            return `<img src="/storage/${path}" class="slider-img" onerror="this.src='/no-image.png'">`;
        }).join('');
    } else {
        html = `<div style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#999;">
                    No Image</div>`;
    }
    document.getElementById('slider_images').innerHTML = html;
    currentSlide = 0;
    totalSlide   = document.querySelectorAll('#slider_images .slider-img').length;
    setTimeout(updateSlider, 100);
}

function updateSlider() {
    var viewer = document.querySelector('.media-viewer');
    if (!viewer) return;
    var width = viewer.offsetWidth;
    document.getElementById('slider_images').style.transform = `translateX(-${currentSlide * width}px)`;
}

function slideRight() {
    if (currentSlide < totalSlide - 1) { currentSlide++; updateSlider(); }
}

function slideLeft() {
    if (currentSlide > 0) { currentSlide--; updateSlider(); }
}

$(window).on('resize', updateSlider);

// ===================== INIT =====================
$(document).ready(function () {

    $.fn.dataTable.ext.errMode = 'none';
    table = $('#table_id').DataTable({ paging: true });
    loadData();

    // ---- MODAL TAMBAH ----
    $('body').on('click', '#button_tambah_barang', function () {
        document.getElementById('modal_tambah_barang').classList.add('active');
    });

    function closeTambahBarang() {
        document.getElementById('modal_tambah_barang').classList.remove('active');
        document.getElementById('form_tambah_barang').reset();
        document.getElementById('preview_tambah_barang').style.display = 'none';
    }

    document.getElementById('close_modal_tambah_barang').addEventListener('click', closeTambahBarang);
    document.getElementById('cancel_modal_tambah_barang').addEventListener('click', closeTambahBarang);
    document.getElementById('modal_tambah_barang').addEventListener('click', function (e) {
        if (e.target === this) closeTambahBarang();
    });

    // ---- MODAL EDIT ----
    function closeEditBarang() {
        document.getElementById('modal_edit_barang').classList.remove('active');
    }

    document.getElementById('close_modal_edit_barang').addEventListener('click', closeEditBarang);
    document.getElementById('cancel_modal_edit_barang').addEventListener('click', closeEditBarang);
    document.getElementById('modal_edit_barang').addEventListener('click', function (e) {
        if (e.target === this) closeEditBarang();
    });

    // ---- MODAL DETAIL ----
    function closeDetailBarang() {
        document.getElementById('modal_detail_barang').classList.remove('active');
        currentSlide = 0;
        document.getElementById('slider_images').style.transform = 'translateX(0)';
    }

    document.getElementById('close_modal_detail_barang').addEventListener('click', closeDetailBarang);
    document.getElementById('modal_detail_barang').addEventListener('click', function (e) {
        if (e.target === this) closeDetailBarang();
    });

    // ---- STORE BARANG ----
    $(document).off('click', '#store').on('click', '#store', function (e) {
        e.preventDefault();

        var files = document.getElementById('gambar').files;

        if (files.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Gambar wajib diisi' });
            return;
        }
        if (files.length > 20) {
            Swal.fire({ icon: 'warning', title: 'Maksimal 20 gambar' });
            return;
        }

        var formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            formData.append('gambar[' + i + ']', files[i]);
        }
        formData.append('nama_barang',  $('#nama_barang').val());
        formData.append('stok_minimum', $('#stok_minimum').val());
        formData.append('jenis_id',     $('#jenis_id').val());
        formData.append('satuan',       $('#satuan').val());
        formData.append('deskripsi',    $('#deskripsi').val());
        formData.append('_token',       $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: '/barang',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#store').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            },
            success: function (res) {
                Swal.fire({ icon: 'success', title: res.message });
                closeTambahBarang();
                $('#store').prop('disabled', false).text('Tambah');
                loadData();
            },
            error: function (xhr) {
                $('#store').prop('disabled', false).text('Tambah');
                if (xhr.status === 422) {
                    var errors      = xhr.responseJSON;
                    var gambarErrors = [];
                    var errorList   = [];
                    $.each(errors, function (field, messages) {
                        if (Array.isArray(messages)) {
                            errorList.push(messages[0]);
                            if (field.startsWith('gambar')) gambarErrors.push(messages[0]);
                        }
                    });
                    if (gambarErrors.length > 0) {
                        Swal.fire({ icon: 'error', title: 'Upload Gambar Gagal',
                            html: '<ul style="text-align:left;padding-left:20px;margin:0;">'
                                + gambarErrors.map(function (m) { return '<li>' + m + '</li>'; }).join('') + '</ul>',
                            confirmButtonColor: '#4f46e5' });
                        return;
                    }
                    Swal.fire({ icon: 'error', title: 'Periksa kembali inputan!',
                        html: '<ul style="text-align:left;padding-left:20px;margin:0;">'
                            + errorList.map(function (m) { return '<li>' + m + '</li>'; }).join('') + '</ul>',
                        confirmButtonColor: '#4f46e5' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal Menyimpan',
                        text: xhr.responseJSON?.message ?? 'Terjadi kesalahan, coba lagi.',
                        confirmButtonColor: '#4f46e5' });
                }
            }
        });
    });

    // ---- DETAIL BARANG ----
    $('body').on('click', '.button_detail_barang', function () {
        var barang_id = $(this).data('id');
        $.ajax({
            url: `/barang/${barang_id}`,
            type: 'GET',
            success: function (response) {
                var data = response?.data ?? response;
                setCetakPdf(data.id);
                $('#detail_nama_barang').text(data.nama_barang || '-');
                $('#detail_stok').text(data.stok ?? 0);
                $('#detail_stok_minimum').text(data.stok_minimum ?? 0);
                $('#detail_jenis').text(data.jenis?.jenis_barang ?? '-');
                $('#detail_satuan').text(data.satuan ?? '-');
                loadDeskripsi(data.deskripsi || '-');

                var images = [];
                if (Array.isArray(data.gambar)) {
                    images = data.gambar;
                } else if (typeof data.gambar === 'string') {
                    try { images = JSON.parse(data.gambar); } catch { images = [data.gambar]; }
                }
                renderSlider(images);
                document.getElementById('modal_detail_barang').classList.add('active');
            },
            error: function (xhr) {
                Swal.fire({ icon: 'error', title: 'ERROR', text: xhr.responseText.substring(0, 200) });
            }
        });
    });

    // ---- EDIT BARANG ----
    $('body').on('click', '.button_edit_barang', function () {
        var barang_id = $(this).data('id');
        $.ajax({
            url: `/barang/${barang_id}/edit`,
            type: 'GET',
            success: function (response) {
                var data = response.data;
                $('#barang_id').val(data.id);
                $('#edit_gambar').val(null);
                $('#edit_nama_barang').val(data.nama_barang);
                $('#edit_stok_minimum').val(data.stok_minimum);
                $('#edit_jenis_id').val(data.jenis_id);
                $('#edit_satuan').val(data.satuan);
                $('#edit_deskripsi').val(data.deskripsi);

                var images = [];
                if (data.gambar) {
                    if (Array.isArray(data.gambar)) {
                        images = data.gambar;
                    } else {
                        try { images = JSON.parse(data.gambar); } catch { images = []; }
                    }
                }
                var html = images.length
                    ? images.map(function (img) {
                        return `<img src="/storage/${img}" width="80" style="margin:5px;border-radius:6px;">`;
                      }).join('')
                    : '<span style="color:#999;">No Image</span>';
                document.getElementById('edit_gambar_preview').innerHTML = html;
                document.getElementById('modal_edit_barang').classList.add('active');
            },
            error: function (xhr) { console.error(xhr.responseText); }
        });
    });

    // ---- UPDATE BARANG ----
    $(document).off('click', '#update').on('click', '#update', function (e) {
        e.preventDefault();
        var barang_id = $('#barang_id').val();
        if (!barang_id) { alert('ID tidak ditemukan'); return; }

        var formData = new FormData();
        var files    = document.getElementById('edit_gambar').files;
        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                formData.append('gambar[' + i + ']', files[i]);
            }
        }
        formData.append('nama_barang',  $('#edit_nama_barang').val());
        formData.append('stok_minimum', $('#edit_stok_minimum').val());
        formData.append('deskripsi',    $('#edit_deskripsi').val());
        formData.append('jenis_id',     $('#edit_jenis_id').val());
        formData.append('satuan',       $('#edit_satuan').val());
        formData.append('_token',       $("meta[name='csrf-token']").attr('content'));
        formData.append('_method',      'PUT');

        $.ajax({
            url: `/barang/${barang_id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#update').prop('disabled', true).text('Menyimpan...');
            },
            success: function (response) {
                Swal.fire({ icon: 'success', title: response.message, timer: 2000 });
                document.getElementById('modal_edit_barang').classList.remove('active');
                $('#update').prop('disabled', false).text('Update');
                loadData();
            },
            error: function (xhr) {
                $('#update').prop('disabled', false).text('Update');
                if (xhr.status === 422) {
                    var errors      = xhr.responseJSON;
                    var gambarErrors = [];
                    var errorList   = [];
                    $.each(errors, function (field, messages) {
                        if (Array.isArray(messages)) {
                            errorList.push(messages[0]);
                            if (field.startsWith('gambar')) gambarErrors.push(messages[0]);
                        }
                    });
                    if (gambarErrors.length > 0) {
                        Swal.fire({ icon: 'error', title: 'Upload Gambar Gagal',
                            html: '<ul style="text-align:left;padding-left:20px;margin:0;">'
                                + gambarErrors.map(function (m) { return '<li>' + m + '</li>'; }).join('') + '</ul>',
                            confirmButtonColor: '#4f46e5' });
                        return;
                    }
                    Swal.fire({ icon: 'error', title: 'Periksa kembali inputan!',
                        html: '<ul style="text-align:left;padding-left:20px;margin:0;">'
                            + errorList.map(function (m) { return '<li>' + m + '</li>'; }).join('') + '</ul>',
                        confirmButtonColor: '#4f46e5' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal Update',
                        text: xhr.responseJSON?.message ?? 'Terjadi kesalahan, coba lagi.',
                        confirmButtonColor: '#4f46e5' });
                }
            }
        });
    });

    // ---- HAPUS BARANG ----
    $('body').on('click', '.button_hapus_barang', function () {
        var barang_id = $(this).data('id');
        var token     = $("meta[name='csrf-token']").attr('content');

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: 'Data akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, HAPUS!'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/barang/${barang_id}`,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': token },
                    success: function (response) {
                        Swal.fire({ icon: 'success', title: response.message, timer: 2000, showConfirmButton: false });
                        var dt  = $('#table_id').DataTable();
                        var row = $(`#index_${barang_id}`);
                        dt.row(row).remove().draw();
                    },
                    error: function () {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Data tidak bisa dihapus' });
                    }
                });
            }
        });
    });

}); // end document.ready
</script>
@endpush
