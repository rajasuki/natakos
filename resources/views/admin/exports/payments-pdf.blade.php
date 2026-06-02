<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Data Pembayaran</title>
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
        .status-unpaid { color: #B22222; font-weight: 600; }
        .status-pending_verification { color: #B8860B; font-weight: 600; }
        .status-paid { color: #4A7C59; font-weight: 600; }
        .status-rejected { color: #999; font-weight: 600; }
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
        <h1>Laporan Data Pembayaran</h1>
        <p>IchiKOS</p>
        <p>Tanggal: {{ now()->isoFormat('D MMMM Y') }}</p>
        @if(request()->hasAny(['q', 'status', 'deadline_status']))
            <p>
                Filter:
                @if(request()->filled('q'))
                    Pencarian: "{{ request('q') }}"
                @endif
                @if(request()->filled('status'))
                    Status: {{ $statusLabels[request('status')] ?? request('status') }}
                @endif
                @if(request()->filled('deadline_status'))
                    Deadline: {{ $deadlineStatusLabels[request('deadline_status')] ?? request('deadline_status') }}
                @endif
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Penghuni</th>
                <th>Kamar</th>
                <th>Nominal</th>
                <th>Periode Mulai</th>
                <th>Periode Akhir</th>
                <th>Tenggat Bayar</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Alasan Penolakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $payment->tenant?->user?->name }}</td>
                    <td>{{ $payment->tenant?->room?->name }}</td>
                    <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ $payment->period_start?->isoFormat('D MMMM Y') }}</td>
                    <td>{{ $payment->period_end?->isoFormat('D MMMM Y') }}</td>
                    <td>{{ $payment->due_date?->isoFormat('D MMMM Y') }}</td>
                    <td class="status-{{ $payment->status }}">{{ $statusLabels[$payment->status] ?? $payment->status }}</td>
                    <td>{{ $payment->notes ?? '-' }}</td>
                    <td>{{ $payment->rejection_reason ?? '-' }}</td>
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
