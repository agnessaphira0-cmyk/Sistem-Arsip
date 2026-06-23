<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arsip Dokumen - PT Bank Sumut</title>
    <style>
        body { 
            font-family: 'Helvetica', Arial, sans-serif; 
            color: #333; 
            font-size: 11px; 
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 3px double #004B87; 
            padding-bottom: 12px; 
        }
        .header h2 { 
            margin: 0; 
            color: #004B87; 
            font-size: 16px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h3 {
            margin: 3px 0 0 0;
            color: #1e293b;
            font-size: 13px;
        }
        .header p { 
            margin: 6px 0 0 0; 
            color: #64748b; 
            font-size: 10px; 
        }
        .header .filter-info {
            margin-top: 4px;
            font-size: 10px;
            color: #475569;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #cbd5e1; 
            padding: 7px 9px; 
            text-align: left; 
            vertical-align: middle;
        }
        th { 
            background-color: #f1f5f9; 
            color: #002d54; 
            font-weight: bold; 
            font-size: 10px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) { 
            background-color: #f8fafc; 
        }
        .text-center {
            text-align: center;
        }
        .badge { 
            padding: 3px 6px; 
            border-radius: 4px; 
            font-size: 9px; 
            font-weight: bold; 
            display: inline-block;
        }
        .digital { 
            background-color: #e0f2fe; 
            color: #0369a1; 
        }
        .fisik { 
            background-color: #f1f5f9; 
            color: #475569; 
        }
        .status-aktif {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-akan_kadaluarsa {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-kadaluarsa {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer { 
            position: fixed; 
            bottom: -10px; 
            width: 100%; 
            text-align: right; 
            font-size: 9px; 
            color: #94a3b8; 
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>PT BANK SUMUT</h2>
        <h3>LAPORAN DATA MANAJEMEN ARSIP DOKUMEN</h3>
        <p>Dicetak otomatis melalui Sistem Informasi Arsip pada: {{ date('d/m/Y H:i') }} WIB</p>
        @if(request('box_id') || request('tahun_retensi') || request('status_retensi'))
        <p class="filter-info">
            Filter aktif:
            @if(request('box_id'))
                Box: {{ optional(\App\Models\Box::find(request('box_id')))->kode_box ?? '-' }}
            @endif
            @if(request('tahun_retensi'))
                | Tahun Retensi: {{ request('tahun_retensi') }}
            @endif
            @if(request('status_retensi'))
                | Status: {{ str_replace('_', ' ', ucfirst(request('status_retensi'))) }}
            @endif
        </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="15%">No Surat</th>
                <th width="22%">Nama Dokumen</th>
                <th width="13%">Kategori</th>
                <th width="11%">Wadah Box</th>
                <th width="9%">Lokasi Rak</th>
                <th width="11%">Jenis Berkas</th>
                <th width="16%">Status Retensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dokumens as $i => $dok)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td style="font-weight: 500; color: #004B87;">{{ $dok->no_surat ?? '-' }}</td>
                <td style="font-weight: 500;">{{ $dok->nama_dokumen }}</td>
                <td>{{ $dok->kategori }}</td>
                <td>{{ $dok->box->kode_box ?? '-' }}</td>
                <td>{{ $dok->box->rak->kode_rak ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge {{ $dok->jenis == 'digital' ? 'digital' : 'fisik' }}">
                        {{ strtoupper($dok->jenis) }}
                    </span>
                </td>
                <td class="text-center">
                    @php $status = $dok->retensi->status ?? null; @endphp
                    @if($status)
                        <span class="badge status-{{ $status }}">
                            {{ strtoupper(str_replace('_', ' ', $status)) }}
                        </span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistem Informasi Kearsipan PT Bank Sumut — Halaman 1
    </div>

</body>
</html>