<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Resume Suara Pemilihan Gubernur</title>
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
        .header-text {
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
        .document-info {
            text-align: left;
            margin: 20px 0;
        }
        .document-info p {
            margin: 3px 0;
        }
        .date-info {
            text-align: right;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9pt;
        }
        th, td {
            border: 0.5px solid black;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #3560A0;
            color: white;
            font-weight: bold;
            vertical-align: middle;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-white { color: white; }
        
        .bg-blue-950 { background-color: #172554; }
        .bg-green { background-color: #4ade80; }
        .bg-yellow { background-color: #facc15; }
        .bg-red { background-color: #f87171; }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10pt;
        }

        .filter-info {
            margin: 10px 0;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <div class="letterhead">
        <div class="header-text">
            <h2>PEMERINTAH PROVINSI KALIMANTAN TIMUR</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Jalan Jenderal Sudirman No.1 Samarinda 75119</p>
        </div>
    </div>

    <div class="filter-info">
        @if($keyword)
            <p>Filter Pencarian: {{ $keyword }}</p>
        @endif
        
        @if(count($partisipasi) < 3)
            <p>Filter Partisipasi: {{ implode(', ', $partisipasi) }}</p>
        @endif
    </div>

    <table autosize="1">
        <thead>
            <tr>
                <th rowspan="2" width="5%">NO</th>
                
                @if(!$isProvinsiColumnIgnored)
                    <th rowspan="2" width="12%">PROVINSI</th>
                @endif
                
                @if(!$isKabupatenColumnIgnored)
                    <th rowspan="2" width="12%">KABUPATEN/KOTA</th>
                @endif
                
                @if(!$isKecamatanColumnIgnored)
                    <th rowspan="2" width="12%">KECAMATAN</th>
                @endif
                
                @if(!$isKelurahanColumnIgnored)
                    <th rowspan="2" width="12%">KELURAHAN</th>
                @endif
                
                @if($isTpsView && !$isTPSColumnIgnored)
                    <th rowspan="2" width="12%">TPS</th>
                @endif
                
                <th rowspan="1" width="8%">DPT</th>
                
                @if(!$isCalonColumnIgnored)
                    @foreach($paslon as $calon)
                        <th class="bg-blue-950 text-white">{{ $calon->nama }}<br>{{ $calon->nama_wakil }}</th>
                    @endforeach
                    @if($isPilkadaTunggal)
                        <th class="bg-blue-950 text-white">KOTAK KOSONG</th>
                    @endif
                @endif

                <th width="8%">SUARA SAH</th>
                <th width="8%">SUARA TIDAK SAH</th>
                <th width="8%">SUARA MASUK</th>
                <th width="8%">ABSTAIN</th>
                <th width="8%">PARTISIPASI</th>
            </tr>
            <tr>
                <th class="text-center">{{ number_format($data->sum('dpt'), 0, ',', '.') }}</th>
                
                @if(!$isCalonColumnIgnored)
                    @foreach($paslon as $calon)
                        <th class="text-center bg-blue-950 text-white">
                            @if($isTpsView)
                                {{ number_format($data->sum(fn($item) => $item->suaraCalonByCalonId($calon->id)?->first()?->suara ?? 0), 0, ',', '.') }}
                            @else
                                {{ number_format($data->sum(fn($item) => $item->getCalonSuaraByCalonId($calon->id)?->total_suara ?? 0), 0, ',', '.') }}
                            @endif
                        </th>
                    @endforeach
                    @if($isPilkadaTunggal)
                        <th class="text-center bg-blue-950 text-white">
                            {{ number_format($data->sum('kotak_kosong'), 0, ',', '.') }}
                        </th>
                    @endif
                @endif

                <th class="text-center">{{ number_format($data->sum('suara_sah'), 0, ',', '.') }}</th>
                <th class="text-center">{{ number_format($data->sum('suara_tidak_sah'), 0, ',', '.') }}</th>
                <th class="text-center">{{ number_format($data->sum('suara_masuk'), 0, ',', '.') }}</th>
                <th class="text-center">{{ number_format($data->sum('abstain'), 0, ',', '.') }}</th>
                <th class="text-center">{{ number_format($data->avg('partisipasi'), 1, ',', '.') }}%</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    @if(!$isProvinsiColumnIgnored)
                        <td class="text-left">
                            @if($isTpsView)
                                {{ $item->tps?->kelurahan?->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}
                            @elseif($isKelurahanView)
                                {{ $item->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}
                            @elseif($isKecamatanView)
                                {{ $item->kabupaten?->provinsi?->nama ?? '-' }}
                            @else
                                {{ $item->provinsi?->nama ?? '-' }}
                            @endif
                        </td>
                    @endif
                    
                    @if(!$isKabupatenColumnIgnored)
                        <td class="text-left">
                            @if($isTpsView)
                                {{ $item->tps?->kelurahan?->kecamatan?->kabupaten?->nama ?? '-' }}
                            @elseif($isKelurahanView)
                                {{ $item->kecamatan?->kabupaten?->nama ?? '-' }}
                            @elseif($isKecamatanView)
                                {{ $item->kabupaten?->nama ?? '-' }}
                            @else
                                {{ $item->nama }}
                            @endif
                        </td>
                    @endif
                    
                    @if(!$isKecamatanColumnIgnored)
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
                    
                    @if(!$isKelurahanColumnIgnored)
                        <td class="text-left">
                            @if($isTpsView)
                                {{ $item->tps?->kelurahan?->nama ?? '-' }}
                            @else
                                {{ $item->nama }}
                            @endif
                        </td>
                    @endif
                    
                    @if($isTpsView && !$isTPSColumnIgnored)
                        <td class="text-left">{{ $item->nama }}</td>
                    @endif

                    <td class="text-right">{{ number_format($item->dpt, 0, ',', '.') }}</td>

                    @if(!$isCalonColumnIgnored)
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
                            <td class="text-right">{{ number_format($item->kotak_kosong, 0, ',', '.') }}</td>
                        @endif
                    @endif

                    <td class="text-right">{{ number_format($item->suara_sah, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->suara_tidak_sah, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->suara_masuk, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->abstain, 0, ',', '.') }}</td>
                    <td class="text-center {{ $item->partisipasi >= 80 ? 'bg-green' : ($item->partisipasi >= 60 ? 'bg-yellow' : 'bg-red') }}">
                        {{ number_format($item->partisipasi, 1, ',', '.') }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 7 + count($paslon) + 
                        (!$isProvinsiColumnIgnored ? 1 : 0) +
                        (!$isKabupatenColumnIgnored ? 1 : 0) + 
                        (!$isKecamatanColumnIgnored ? 1 : 0) + 
                        (!$isKelurahanColumnIgnored ? 1 : 0) +
                        ($isTpsView && !$isTPSColumnIgnored ? 1 : 0) }}" 
                        class="text-center">
                        Data tidak tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Samarinda, {{ now()->isoFormat('D MMMM Y') }}<br>
        Kepala Badan Kesbangpol<br>
        Provinsi Kalimantan Timur<br><br><br><br>
        <u>...................................</u><br>
        NIP. ...........................</p>
    </div>
</body>
</html>