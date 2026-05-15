@extends('layouts.app')

@section('page-title', 'Ubah Password')

@section('content')

<div class="section-header">
    <h1>Ubah Password</h1>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="/ubah-password" method="POST" id="ubahPassword">
                @method('put')
                @csrf

                <div class="mb-3">
                    <label for="current_password" class="form-label">Masukkan Password Lama</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-current_password"></div>
                </div>

                <div class="mb-3">
                    <label for="passwordNew" class="form-label">Masukkan Password Baru</label>
                    <input type="password" class="form-control" id="passwordNew" name="passwordNew" required>
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-passwordNew"></div>
                </div>

                <div class="mb-3">
                    <label for="konfirmasiPassword" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="konfirmasiPassword" name="konfirmasiPassword" required>
                    <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-konfirmasiPassword"></div>
                </div>

                <div class="d-flex justify-content-end mb-3">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function(){
    $('#ubahPassword').submit(function(e){
        e.preventDefault();

        let current_password    = $('#current_password').val();            
        let passwordNew         = $('#passwordNew').val();            
        let konfirmasiPassword  = $('#konfirmasiPassword').val();
        let token               = $("meta[name='csrf-token']").attr("content");

        let formData = new FormData();
        formData.append('current_password', current_password);
        formData.append('passwordNew', passwordNew);
        formData.append('konfirmasiPassword', konfirmasiPassword);
        formData.append('_token', token);

        $.ajax({
            url: '/ubah-password',
            type: "POST",
            cache: false,
            data: formData,
            contentType: false,
            processData: false,

            success:function(response){
                $('#current_password').val('');
                $('#passwordNew').val('');
                $('#konfirmasiPassword').val('');

                Swal.fire({
                    type: 'success',
                    icon: 'success',
                    title: `${response.message}`,
                    showConfirmButton: true,
                    timer: 3000
                });
            },
            error:function(error){
                if (error.responseJSON && error.responseJSON.current_password) {
                    $('#alert-current_password').removeClass('d-none');
                    $('#alert-current_password').addClass('d-block');

                    $('#alert-current_password').text(error.responseJSON.current_password);
                }

                if (error.responseJSON && error.responseJSON.passwordNew) {
                    $('#alert-passwordNew').removeClass('d-none');
                    $('#alert-passwordNew').addClass('d-block');

                    $('#alert-passwordNew').text(error.responseJSON.passwordNew);
                }

                if (error.responseJSON && error.responseJSON.konfirmasiPassword) {
                    $('#alert-konfirmasiPassword').removeClass('d-none');
                    $('#alert-konfirmasiPassword').addClass('d-block');

                    $('#alert-konfirmasiPassword').text(error.responseJSON.konfirmasiPassword);
                }
            }
        });
    });
});
</script>

@endpush