<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Data Penghuni</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1C2B22;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4A7C59;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px;
            color: #3D6A4A;
        }
        .header p {
            margin: 2px 0;
            color: #5B7060;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #4A7C59;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #DCE7DD;
            font-size: 10px;
        }
        tr:nth-child(even) td {
            background-color: #F5FAF5;
        }
        .status-active { color: #4A7C59; font-weight: 600; }
        .status-inactive { color: #B8860B; font-weight: 600; }
        .status-moved_out { color: #B22222; font-weight: 600; }
        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #DCE7DD;
            padding-top: 5px;
        }
        .footer .pagenum:before { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Penghuni</h1>
        <p>IchiKOS</p>
        <p>Tanggal: {{ now()->isoFormat('D MMMM Y') }}</p>
        @if(request()->hasAny(['q', 'status', 'room_id']))
            <p>
                Filter:
                @if(request()->filled('q'))
                    Pencarian: "{{ request('q') }}"
                @endif
                @if(request()->filled('status'))
                    Status: {{ $statusLabels[request('status')] ?? request('status') }}
                @endif
                @if(request()->filled('room_id'))
                    Kamar ID: {{ request('room_id') }}
                @endif
            </p>
        @endif
        <p>{{ $history ? 'Riwayat Penghuni' : 'Penghuni Aktif' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penghuni</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th>Kamar</th>
                <th>Tgl Masuk</th>
                <th>Tgl Keluar</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tenants as $tenant)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tenant->user?->name }}</td>
                    <td>{{ $tenant->user?->email }}</td>
                    <td>{{ $tenant->user?->phone }}</td>
                    <td>{{ $tenant->room?->name }}</td>
                    <td>{{ $tenant->start_date?->isoFormat('D MMMM Y') }}</td>
                    <td>{{ $tenant->end_date?->isoFormat('D MMMM Y') ?? '-' }}</td>
                    <td class="status-{{ $tenant->status }}">{{ $statusLabels[$tenant->status] ?? $tenant->status }}</td>
                    <td>{{ $tenant->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <span>IchiKOS — Dicetak pada {{ now()->isoFormat('D MMMM Y HH:mm') }}</span>
        &nbsp;|&nbsp; Halaman <span class="pagenum"></span>
    </div>
</body>
</html>
