<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; margin: 0; padding: 20px; }
        h1 { color: #0f766e; font-size: 20px; margin-bottom: 4px; }
        .meta { color: #64748b; font-size: 11px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #0f766e; color: white; padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .badge { padding: 2px 8px; border-radius: 99px; font-weight: bold; font-size: 10px; }
        .normal  { background: #dcfce7; color: #166534; }
        .ringan  { background: #fef9c3; color: #854d0e; }
        .sedang  { background: #ffedd5; color: #9a3412; }
        .tinggi  { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 10px; color: #94a3b8; font-size: 10px; }
        .summary { display: flex; gap: 20px; margin: 16px 0; }
        .stat { background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 8px; padding: 10px 16px; }
        .stat-val { font-size: 22px; font-weight: bold; color: #0f766e; }
        .stat-lbl { font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <h1>🧠 Laporan Monitoring Kesehatan Mental Pegawai</h1>
    <div class="meta">
        Periode: {{ \Carbon\Carbon::parse($request->date_from)->format('d M Y') }} —
        {{ \Carbon\Carbon::parse($request->date_to)->format('d M Y') }}
        @if($request->filled('unit')) | Unit: {{ $request->unit }} @endif
        | Digenerate: {{ now()->format('d M Y H:i') }}
    </div>

    {{-- Summary Stats --}}
    @php
        $byRisk = $data->groupBy('risk_level');
    @endphp
    <table style="width:auto; margin-bottom:20px;">
        <tr>
            <td style="padding:8px 16px; background:#f0fdfa; border:1px solid #99f6e4; border-radius:8px; text-align:center;">
                <div style="font-size:22px; font-weight:bold; color:#0f766e;">{{ $data->count() }}</div>
                <div style="font-size:10px; color:#64748b;">Total Skrining</div>
            </td>
            <td style="width:12px;"></td>
            @foreach(['normal'=>'#166534','ringan'=>'#854d0e','sedang'=>'#9a3412','tinggi'=>'#991b1b'] as $level => $color)
            <td style="padding:8px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; text-align:center;">
                <div style="font-size:18px; font-weight:bold; color:{{ $color }};">{{ $byRisk[$level]?->count() ?? 0 }}</div>
                <div style="font-size:10px; color:#64748b;">{{ ucfirst($level) }}</div>
            </td>
            <td style="width:8px;"></td>
            @endforeach
        </tr>
    </table>

    {{-- Data Table --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Unit</th>
                <th>Kuesioner</th>
                <th>Skor</th>
                <th>Risiko</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $s)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $s->user->name ?? '-' }}</td>
                <td>{{ $s->user->nip ?? '-' }}</td>
                <td>{{ $s->user->unit ?? '-' }}</td>
                <td>{{ $s->questionnaire->name ?? '-' }}</td>
                <td style="font-weight:bold;">{{ $s->total_score }}</td>
                <td><span class="badge {{ $s->risk_level }}">{{ ucfirst($s->risk_level) }}</span></td>
                <td>{{ $s->completed_at?->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        WellnessApp — Monitoring & Tatalaksana Kesehatan Mental Pegawai | Confidential
    </div>
</body>
</html>
