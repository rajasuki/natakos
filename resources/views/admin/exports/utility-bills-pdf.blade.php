<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan Utilitas</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1C2B22; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #4A7C59; }
        .header h1 { font-size: 18px; margin: 0 0 5px; color: #3D6A4A; }
        .header p { margin: 2px 0; color: #5B7060; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #4A7C59; color: #fff; padding: 8px 6px; text-align: left; font-size: 10px; font-weight: 600; }
        td { padding: 6px; border-bottom: 1px solid #DCE7DD; font-size: 10px; }
        tr:nth-child(even) td { background-color: #F5FAF5; }
        .status-unpaid { color: #B22222; font-weight: 600; }
        .status-paid { color: #4A7C59; font-weight: 600; }
        .footer { position: fixed; bottom: 10px; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #DCE7DD; padding-top: 5px; }
        .footer .pagenum:before { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Tagihan Utilitas</h1>
        <p>IchiKOS</p>
        <p>Tanggal: {{ now()->isoFormat('D MMMM Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Penghuni</th>
                <th>Kamar</th>
                <th>Jenis</th>
                <th>Periode</th>
                <th>Jumlah</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bills as $bill)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bill->tenant?->user?->name }}</td>
                    <td>{{ $bill->tenant?->room?->name }}</td>
                    <td>{{ $typeLabels[$bill->type] ?? $bill->type }}</td>
                    <td>{{ $bill->period }}</td>
                    <td>Rp{{ number_format($bill->amount, 0, ',', '.') }}</td>
                    <td>{{ $bill->due_date?->isoFormat('D MMMM Y') }}</td>
                    <td class="status-{{ $bill->status }}">{{ $statusLabels[$bill->status] ?? $bill->status }}</td>
                    <td>{{ $bill->notes ?? '-' }}</td>
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
