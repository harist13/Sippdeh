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

            .footnote {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            font-size: 8pt;
            color: #666;
            padding: 10px 0;
            border-top: 0.5px solid #ccc;
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
            width: 100%;
        }
        .logo-container {
            position: absolute;
            left: 30px;
            top: 10px;
        }
        .logo {
            width: 150px;
            height: auto;
        }
        .header-text {
            text-align: center;
            margin: 0 auto;
            padding: 10px 0;
        }
        .header-text h2 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            padding: 5px 0;
            text-transform: uppercase;
        }
        .header-text h3 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            padding: 5px 0;
            text-transform: uppercase;
        }
        .header-text p {
            font-size: 10pt;
            margin: 0;
            padding: 2px 0;
            line-height: 1.3;
        }
        .website-email {
            margin-top: 5px;
        }
        .website-email a {
            color: black;
            text-decoration: none;
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
    @php
        $kabupatenName = session('operator_kabupaten_name');
        $jenisWilayah = session('operator_jenis_wilayah');
        $kabupatenId = session('operator_kabupaten_id');
    @endphp

    @if(str_contains(strtolower($kabupatenName), 'bontang'))
    <div class="letterhead">
        <div class="logo-container">
            <img src="{{ public_path('storage/' . $logo) }}" class="logo">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH KOTA BONTANG</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Jl. Bessai Berinta No.82, Tanjung Laut, Kec. Bontang Sel.</p>
            <p>Kota Bontang, Kalimantan Timur 75325</p>
            <p class="website-email">Telp: (0548) 21307 Email: kesbangpol@bontangkota.go.id</p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'kutai kartanegara'))
    <div class="letterhead">
        <div class="logo-container">
            <img src="{{ public_path('storage/' . $logo) }}" class="logo">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN KUTAI KARTANEGARA</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Komplek Perkantoran Gedung Bukit Pelangi</p>
            <p>Jl. Wolter Monginsidi Tenggarong 75513</p>
            <p class="website-email">Telp. (0541) 661004 Fax. (0541) 662608</p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'kutai barat'))
    <div class="letterhead">
        <div class="logo-container">
            <img src="{{ public_path('storage/' . $logo) }}" class="logo">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN KUTAI BARAT</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Jl. Sendawar Raya Barong Tongkok</p>
            <p>Kutai Barat, Kalimantan Timur 75776</p>
            <p class="website-email">Telp/Fax. (0545) 4041415</p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'samarinda'))
    <div class="letterhead">
        <div class="logo-container">
            <img src="{{ public_path('storage/' . $logo) }}" class="logo">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH KOTA SAMARINDA</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Alamat Jalan Balaikota No. 27 Telepon (0541) 733033 Fax (0541) 741429</p>
            <p>Samarinda (Kalimantan Timur) Kode Pos 75121</p>
            <p class="website-email">
                <a href="http://kesbangpol.samarindakota.go.id/">http://kesbangpol.samarindakota.go.id/</a>
                Email: kesbangpol.samarindakota@gmail.com
            </p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'kutai timur'))
    <div class="letterhead">
        <div class="logo-container">
            <img src="{{ public_path('storage/' . $logo) }}" class="logo">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN KUTAI TIMUR</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Jl. Soekarno-Hatta, Sangatta Utara</p>
            <p>Kutai Timur, Kalimantan Timur 75683</p>
            <p class="website-email">Telp. (0549) 21006 Fax. (0549) 21002</p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'paser'))
    <div class="letterhead">
        <div class="logo-container">
            <img src="{{ public_path('storage/' . $logo) }}" class="logo">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN PASER</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Kompleks Perkantoran Terpadu</p>
            <p>Jl. Kesuma Bangsa Km.05, Tanah Grogot 76211</p>
            <p class="website-email">Telp. (0543) 21002 Fax. (0543) 21002</p>
        </div>
    </div>

    @else
    <div class="letterhead">
        <div class="logo-container">
            <img src="{{ public_path('storage/' . $logo) }}" class="logo">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH {{ $jenisWilayah === 'kota' ? 'KOTA' : 'KABUPATEN' }} {{ strtoupper($kabupatenName) }}</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>{{ $kabupatenName }}, Kalimantan Timur</p>
        </div>
    </div>
    @endif

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

      <div class="footnote">
        <p>Dokumen ini telah dicetak melalui Aplikasi SIPPPDEH Prov.Kaltim oleh {{ session('operator_name') }}<br>
        {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>