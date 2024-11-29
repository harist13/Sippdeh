<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Resume Suara Pemilihan Bupati Per TPS</title>
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
        .header-text .contact {
            font-size: 9pt;
            margin: 0;
            padding: 2px 0;
        }
        .header-text a {
            color: #000;
            text-decoration: none;
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
        }
        th, td {
            border: 0.5px solid black;
            padding: 5px;
            font-size: 9pt;
        }
        th {
            background-color: #3560A0;
            color: white;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bg-green { background-color: #4ade80; }
        .bg-yellow { background-color: #facc15; }
        .bg-red { background-color: #f87171; }
        .text-white { color: white; }
        .bg-blue-950 { background-color: #172554; }
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
            <h2>PEMERINTAH PROVINSI KALIMANTAN TIMUR</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Jalan Jenderal Sudirman Nomor 1, Samarinda, Kalimantan Timur 75121 Telepon (0541) 733333; Faksimile (0541) 733453</p>
            <p class="contact">Pos-el: <a href="mailto:kesbangpolkaltim@gmail.com">kesbangpolkaltim@gmail.com</a>; Laman <a href="http://kesbangpol.kaltimprov.go.id">http://kesbangpol.kaltimprov.go.id</a></p>
        </div>
    </div>

    <div class="date-info">
        <p>Samarinda, {{ now()->isoFormat('D MMMM Y') }}</p>
    </div>

    <div class="document-info">
        <p>Nomor : 200.1.5/1461/Kesbangpol.</p>
        <p>Sifat : Penting</p>
        <p>Lampiran : 1 (satu) berkas</p>
        <p>Hal : Rekapitulasi perolehan suara Pemilihan Gubernur (Pilgub) tahun 2024</p>
    </div>

     <table autosize="1">
            <thead>
                <tr>
                    <th rowspan="2">NO</th>
                    @if(in_array('KABUPATEN/KOTA', $includedColumns))
                        <th rowspan="2">KABUPATEN/KOTA</th>
                    @endif
                    @if(in_array('KECAMATAN', $includedColumns))
                        <th rowspan="2">KECAMATAN</th>
                    @endif
                    @if(in_array('KELURAHAN', $includedColumns))
                        <th rowspan="2">KELURAHAN</th>
                    @endif
                    @if(in_array('TPS', $includedColumns))
                        <th rowspan="2">TPS</th>
                    @endif
                    <th>DPT</th>
                    
                    @if(in_array('CALON', $includedColumns))
                        @foreach($paslon as $calon)
                            <th class="bg-blue-950">{{ $calon->nama }}<br>{{ $calon->nama_wakil }}</th>
                        @endforeach
                        @if($isPilkadaTunggal)
                            <th class="bg-blue-950">KOTAK KOSONG</th>
                        @endif
                    @endif
                    
                    <th>SUARA SAH</th>
                    <th>SUARA TIDAK SAH</th>
                    <th>SUARA MASUK</th>
                    <th>ABSTAIN</th>
                    <th>PARTISIPASI</th>
                </tr>
                <tr>
                    <th class="text-center">{{ number_format($data->sum('dpt'), 0, ',', '.') }}</th>
                    
                    @if(in_array('CALON', $includedColumns))
                        @if($isPilkadaTunggal)
                            <th class="text-center bg-blue-950">
                                {{ number_format($data->sum('kotak_kosong'), 0, ',', '.') }}
                            </th>
                        @endif
                        @foreach($paslon as $calon)
                            <th class="text-center bg-blue-950">
                                {{ number_format($data->sum(function($item) use ($calon) {
                                    return $item->suaraCalonByCalonId($calon->id)?->first()?->suara ?? 0;
                                }), 0, ',', '.') }}
                            </th>
                        @endforeach
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
                        @if(in_array('KABUPATEN/KOTA', $includedColumns))
                            <td class="text-left">{{ $item->tps?->kelurahan?->kecamatan?->kabupaten?->nama ?? '-' }}</td>
                        @endif
                        @if(in_array('KECAMATAN', $includedColumns))
                            <td class="text-left">{{ $item->tps?->kelurahan?->kecamatan?->nama ?? '-' }}</td>
                        @endif
                        @if(in_array('KELURAHAN', $includedColumns))
                            <td class="text-left">{{ $item->tps?->kelurahan?->nama ?? '-' }}</td>
                        @endif
                        @if(in_array('TPS', $includedColumns))
                            <td class="text-left">{{ $item->nama }}</td>
                        @endif
                        <td class="text-right">{{ number_format($item->dpt, 0, ',', '.') }}</td>
                        
                        @if(in_array('CALON', $includedColumns))
                            @foreach($paslon as $calon)
                                @php
                                    $suaraCalon = $item->suaraCalonByCalonId($calon->id)->first();
                                    $suara = $suaraCalon != null ? $suaraCalon->suara : 0;
                                @endphp
                                <td class="text-right">
                                    {{ number_format($suara, 0, ',', '.') }}
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
                        <td class="text-center {{ $item->partisipasi >= 77.5 ? 'bg-green' : 'bg-red' }}">
                            {{ number_format($item->partisipasi, 1, ',', '.') }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 8 + count($paslon) + (in_array('KABUPATEN/KOTA', $includedColumns) ? 1 : 0) + (in_array('KECAMATAN', $includedColumns) ? 1 : 0) + (in_array('KELURAHAN', $includedColumns) ? 1 : 0) + (in_array('TPS', $includedColumns) ? 1 : 0) }}" class="text-center">
                            Data tidak tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>