<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Resume Suara Pemilihan Walikota</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path("fonts/DejaVuSans.ttf") }}') format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .letterhead {
            position: relative;
            margin-bottom: 30px;
            border-bottom: 2px solid black;
            padding-bottom: 20px;
        }
        .logo-container {
            position: absolute;
            left: 0;
            top: 0;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .header-text {
            margin-left: 90px;
            text-align: center;
        }
        .header-text h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            padding: 5px 0;
            text-transform: uppercase;
        }
        .header-text h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
            padding: 5px 0;
            text-transform: uppercase;
        }
        .header-text p {
            font-size: 9pt;
            margin: 0;
            padding: 2px 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bg-green { background-color: #4ade80; }
        .bg-yellow { background-color: #facc15; }
        .bg-red { background-color: #f87171; }
        .text-white { color: white; }
        .bg-blue-950 { background-color: #172554; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9pt;
        }
        th, td {
            border: 0.5px solid black;
            padding: 5px;
        }
        th {
            background-color: #3560A0;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="letterhead">
        <div class="logo-container">
            @if($logo && file_exists(public_path('storage/' . $logo)))
                <img src="{{ public_path('storage/' . $logo) }}" class="logo">
            @endif
        </div>
        <div class="header-text">
            <h2>PEMERINTAH {{ strtoupper($kabupaten->nama ?? '') }}</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>{{ $kabupaten->alamat ?? '' }}</p>
            <p>Telepon: {{ $kabupaten->telepon ?? '-' }} - Email: {{ $kabupaten->email ?? '-' }}</p>
        </div>
    </div>

    @php
        $totalDpt = $data->sum('dpt');
        $totalSuaraSah = $data->sum('suara_sah');
        $totalSuaraTidakSah = $data->sum('suara_tidak_sah');
        $totalSuaraMasuk = $data->sum('suara_masuk');
        $totalAbstain = $data->sum('abstain');
        $avgPartisipasi = $data->avg('partisipasi');

        $totalsPerCalon = [];
        foreach ($paslon as $calon) {
            if ($isTpsView) {
                $totalsPerCalon[$calon->id] = $data->sum(fn($item) => $item->suaraCalonByCalonId($calon->id)?->first()?->suara ?? 0);
            } else {
                $totalsPerCalon[$calon->id] = $data->sum(fn($item) => $item->getCalonSuaraByCalonId($calon->id)?->total_suara ?? 0);
            }
        }

        $totalKotakKosong = $data->sum('kotak_kosong');
    @endphp

    <table autosize="1">
        <thead>
            <tr>
                <th rowspan="2" width="5%">NO</th>
                
                @if(in_array('KABUPATEN/KOTA', $includedColumns))
                    <th rowspan="2" width="15%">KABUPATEN/KOTA</th>
                @endif
                
                @if(in_array('KECAMATAN', $includedColumns))
                    <th rowspan="2" width="15%">KECAMATAN</th>
                @endif
                
                @if(in_array('KELURAHAN', $includedColumns))
                    <th rowspan="2" width="15%">KELURAHAN</th>
                @endif
                
                @if(in_array('TPS', $includedColumns))
                    <th rowspan="2" width="15%">TPS</th>
                @endif
                
                <th rowspan="1" width="10%">DPT</th>

                @if(in_array('CALON', $includedColumns))
                    @foreach($paslon as $calon)
                        <th class="bg-blue-950">
                            {{ $calon->nama }}<br>{{ $calon->nama_wakil }}
                        </th>
                    @endforeach
                    @if($isPilkadaTunggal)
                        <th class="bg-blue-950">KOTAK KOSONG</th>
                    @endif
                @endif

                <th width="10%">SUARA SAH</th>
                <th width="10%">SUARA TIDAK SAH</th>
                <th width="10%">SUARA MASUK</th>
                <th width="10%">ABSTAIN</th>
                <th width="10%">PARTISIPASI</th>
            </tr>
            <tr>
                <th class="text-center">
                    {{ number_format($totalDpt, 0, ',', '.') }}
                </th>
                
                @if(in_array('CALON', $includedColumns))
                    @foreach($paslon as $calon)
                        <th class="text-center bg-blue-950">
                            {{ number_format($totalsPerCalon[$calon->id], 0, ',', '.') }}
                        </th>
                    @endforeach
                    @if($isPilkadaTunggal)
                        <th class="text-center bg-blue-950">
                            {{ number_format($totalKotakKosong, 0, ',', '.') }}
                        </th>
                    @endif
                @endif

                <th class="text-center">{{ number_format($totalSuaraSah, 0, ',', '.') }}</th>
                <th class="text-center">{{ number_format($totalSuaraTidakSah, 0, ',', '.') }}</th>
                <th class="text-center">{{ number_format($totalSuaraMasuk, 0, ',', '.') }}</th>
                <th class="text-center">{{ number_format($totalAbstain, 0, ',', '.') }}</th>
                <th class="text-center">{{ number_format($avgPartisipasi, 1, ',', '.') }}%</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    @if(in_array('KABUPATEN/KOTA', $includedColumns))
                        <td class="text-left">
                            @if($isTpsView)
                                {{ $item->tps?->kelurahan?->kecamatan?->kabupaten?->nama ?? '-' }}
                            @elseif($isKelurahanView)
                                {{ $item->kecamatan?->kabupaten?->nama ?? '-' }}
                            @else
                                {{ $item->kabupaten?->nama ?? '-' }}
                            @endif
                        </td>
                    @endif
                    
                    @if(in_array('KECAMATAN', $includedColumns))
                        <td class="text-left">
                            @if($isTpsView)
                                {{ $item->tps?->kelurahan?->kecamatan?->nama ?? '-' }}
                            @elseif($isKelurahanView)
                                {{ $item->kecamatan?->nama ?? '-' }}
                            @else
                                {{ $item->nama }}
                            @endif
                        </td>
                    @endif
                    
                    @if(in_array('KELURAHAN', $includedColumns))
                        <td class="text-left">
                            @if($isTpsView)
                                {{ $item->tps?->kelurahan?->nama ?? '-' }}
                            @else
                                {{ $item->nama }}
                            @endif
                        </td>
                    @endif
                    
                    @if(in_array('TPS', $includedColumns))
                        <td class="text-left">{{ $item->nama }}</td>
                    @endif

                    <td class="text-right">{{ number_format($item->dpt, 0, ',', '.') }}</td>

                    @if(in_array('CALON', $includedColumns))
                        @foreach($paslon as $calon)
                            <td class="text-right">
                                @if($isTpsView)
                                    {{ number_format($item->suaraCalonByCalonId($calon->id)?->first()?->suara ?? 0, 0, ',', '.') }}
                                @else
                                    {{ number_format($item->getCalonSuaraByCalonId($calon->id)?->total_suara ?? 0, 0, ',', '.') }}
                                @endif
                            </td>
                        @endforeach
                        @if($isPilkadaTunggal)
                            <td class="text-right">
                                {{ number_format($item->kotak_kosong, 0, ',', '.') }}
                            </td>
                        @endif
                    @endif

                    <td class="text-right">{{ number_format($item->suara_sah, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->suara_tidak_sah, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->suara_masuk, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->abstain, 0, ',', '.') }}</td>
                    <td class="text-center 
                        @if($item->partisipasi >= 80)
                            bg-green
                        @elseif($item->partisipasi >= 60)
                            bg-yellow
                        @else
                            bg-red
                        @endif">
                        {{ number_format($item->partisipasi, 1, ',', '.') }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 7 + count($paslon) + 
                        (in_array('KABUPATEN/KOTA', $includedColumns) ? 1 : 0) + 
                        (in_array('KECAMATAN', $includedColumns) ? 1 : 0) + 
                        (in_array('KELURAHAN', $includedColumns) ? 1 : 0) +
                        (in_array('TPS', $includedColumns) ? 1 : 0) }}" 
                        class="text-center">
                        Data tidak tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <p style="text-align: right;">
            Samarinda, {{ now()->isoFormat('D MMMM Y') }}<br>
            Kepala Badan Kesatuan Bangsa dan Politik<br>
            {{ $kabupaten->nama ?? '' }}<br><br><br><br>
            <u>{{ $kabupaten->kepala_dinas ?? '.........................' }}</u><br>
            NIP. {{ $kabupaten->nip_kepala_dinas ?? '.........................' }}
        </p>
    </div>
</body>
</html>