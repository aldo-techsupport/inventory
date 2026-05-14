@extends('layouts.app')

@include('hak-akses.create')
{{-- @include('data-pengguna.edit') --}}

@section('content')

<style>
.activity-cell {
    max-width: 200px;
    word-break: break-word;
    font-size: 0.82rem;
    line-height: 1.5;
}
.activity-desc {
    max-width: 180px;
    word-break: break-word;
    font-size: 0.82rem;
}
@media (max-width: 768px) {
    .activity-cell, .activity-desc {
        max-width: 120px;
        font-size: 0.78rem;
    }
}
</style>

<div class="section-header">
    <h1>Aktivitas User</h1>
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
                                <th>User</th>
                                <th>Before</th>
                                <th>After</th>
                                <th>Description</th>
                                <th>Log At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($log->causer !== null)
                                        {{ $log->causer->name }}
                                    @endif
                                </td>
                                <td class="activity-cell">
                                    @if (isset($log->changes['old']))
                                        @foreach ($log->changes['old'] as $key => $itemChange)
                                            <span class="text-muted">{{ $key }}:</span> {{ $itemChange }}<br>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="activity-cell">
                                    @if (isset($log->changes['attributes']))
                                        @foreach ($log->changes['attributes'] as $key => $itemChange)
                                            <span class="text-muted">{{ $key }}:</span> {{ $itemChange }}<br>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="activity-desc">{{ $log->description }}</td>
                                <td style="white-space: nowrap;">{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                            @endforeach                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Datatables Jquery -->
<script>
    $(document).ready(function(){
        $('#table_id').DataTable({
            paging: true,
            autoWidth: false,
            columnDefs: [
                { width: '5%', targets: 0 },
                { width: '10%', targets: 1 },
                { width: '20%', targets: 2 },
                { width: '20%', targets: 3 },
                { width: '25%', targets: 4 },
                { width: '15%', targets: 5 }
            ]
        });
    })
</script>


@endsection