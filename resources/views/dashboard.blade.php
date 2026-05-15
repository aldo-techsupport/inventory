@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

{{-- ===== SUMMARY CARDS ===== --}}
<div class="row g-3 mb-4">

  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="small-box text-bg-primary">
      <div class="inner">
        <h3>{{ $barang }}</h3>
        <p>Semua Barang</p>
      </div>
      <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375z"/>
        <path clip-rule="evenodd" fill-rule="evenodd" d="M3.087 9l.54 9.176A3 3 0 006.62 21h10.757a3 3 0 002.995-2.824L20.913 9H3.087zm6.163 3.75A.75.75 0 0110 12h4a.75.75 0 010 1.5h-4a.75.75 0 01-.75-.75z"/>
      </svg>
      <a href="/barang" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
        Lihat Detail <i class="bi bi-link-45deg"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="small-box text-bg-success">
      <div class="inner">
        <h3>{{ $barangMasuk }}</h3>
        <p>Barang Masuk</p>
      </div>
      <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path clip-rule="evenodd" fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v11.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.22V3a.75.75 0 01.75-.75zm-9 13.5a.75.75 0 01.75.75v2.25a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5V16.5a.75.75 0 011.5 0v2.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V16.5a.75.75 0 01.75-.75z"/>
      </svg>
      <a href="/barang-masuk" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
        Lihat Detail <i class="bi bi-link-45deg"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="small-box text-bg-warning">
      <div class="inner">
        <h3>{{ $barangKeluar }}</h3>
        <p>Barang Keluar</p>
      </div>
      <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path clip-rule="evenodd" fill-rule="evenodd" d="M11.47 2.47a.75.75 0 011.06 0l4.5 4.5a.75.75 0 01-1.06 1.06l-3.22-3.22V15a.75.75 0 01-1.5 0V4.81L8.03 8.03a.75.75 0 01-1.06-1.06l4.5-4.5zM3 15.75a.75.75 0 01.75.75v2.25a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5V16.5a.75.75 0 011.5 0v2.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V16.5A.75.75 0 013 15.75z"/>
      </svg>
      <a href="/barang-keluar" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
        Lihat Detail <i class="bi bi-link-45deg"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="small-box text-bg-danger">
      <div class="inner">
        <h3>{{ $user }}</h3>
        <p>Pengguna</p>
      </div>
      <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"/>
      </svg>
      <a href="/data-pengguna" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
        Lihat Detail <i class="bi bi-link-45deg"></i>
      </a>
    </div>
  </div>

</div>
{{-- ===== END SUMMARY CARDS ===== --}}

{{-- ===== CHART ===== --}}
<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-bar-chart-line me-2"></i>Grafik Barang Masuk &amp; Barang Keluar</h3>
      </div>
      <div class="card-body">
        <canvas id="summaryChart" style="height:320px;max-height:320px;"></canvas>
      </div>
    </div>
  </div>
</div>
{{-- ===== END CHART ===== --}}

{{-- ===== STOK MINIMUM ===== --}}
<div class="row g-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Stok Mencapai Batas Minimum</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:50px">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($barangMinimum as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td><span class="badge text-bg-warning">{{ $item->stok }}</span></td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-3">Tidak ada barang dengan stok minimum</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- ===== END STOK MINIMUM ===== --}}

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('summaryChart').getContext('2d');

  const gradientBlue = ctx.createLinearGradient(0, 0, 0, 320);
  gradientBlue.addColorStop(0, 'rgba(13, 110, 253, 0.75)');
  gradientBlue.addColorStop(1, 'rgba(13, 110, 253, 0.1)');

  const gradientGreen = ctx.createLinearGradient(0, 0, 0, 320);
  gradientGreen.addColorStop(0, 'rgba(25, 135, 84, 0.75)');
  gradientGreen.addColorStop(1, 'rgba(25, 135, 84, 0.1)');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        @foreach($barangMasukData as $data)
          '{{ date("M Y", strtotime($data->date)) }}',
        @endforeach
      ],
      datasets: [
        {
          label: 'Barang Masuk',
          data: [
            @foreach($barangMasukData as $data)
              {{ $data->total }},
            @endforeach
          ],
          backgroundColor: gradientBlue,
          borderRadius: 6,
          borderSkipped: false,
        },
        {
          label: 'Barang Keluar',
          data: [
            @foreach($barangKeluarData as $data)
              {{ $data->total }},
            @endforeach
          ],
          backgroundColor: gradientGreen,
          borderRadius: 6,
          borderSkipped: false,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 14, font: { size: 13 } }
        },
        tooltip: {
          backgroundColor: 'rgba(20,20,20,0.9)',
          titleColor: '#fff',
          bodyColor: '#fff',
          padding: 10,
          cornerRadius: 8
        }
      },
      scales: {
        x: {
          grid: { color: 'rgba(0,0,0,0.05)' },
          ticks: { font: { size: 12 } }
        },
        y: {
          grid: { color: 'rgba(0,0,0,0.05)' },
          ticks: { stepSize: 1, precision: 0 },
          beginAtZero: true
        }
      }
    }
  });
});
</script>
@endpush
