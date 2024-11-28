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
    $kabupaten = App\Models\Kabupaten::find(session('operator_kabupaten_id'));
    $logo = $kabupaten ? $kabupaten->logo : null;
@endphp

 @if(str_contains(strtolower($kabupatenName), 'mahakam ulu'))
    <div class="letterhead">
        @if($logo && file_exists(public_path('storage/' . $logo)))
            <div class="logo-container">
                <img src="{{ public_path('storage/' . $logo) }}" class="logo">
            </div>
        @endif
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN MAHAKAM ULU</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIKK</h3>
            <p>Jalan Poros Kampung Ujoh Bilang – Long Bagun, Kec. Long Bagun.</p>
            <p>UJOH  BILANG</p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'kutai kartanegara'))
    <div class="letterhead">
        @if($logo && file_exists(public_path('storage/' . $logo)))
            <div class="logo-container">
                <img src="{{ public_path('storage/' . $logo) }}" class="logo">
            </div>
        @endif
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN KUTAI KARTANEGARA</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>jalan Drs. awang sabran, bc. Hk. Nomor G.3 Telp (0541) 661518 fax (0541) 662602</p>
            <p class="website-email">Website kesbangpol.kukarkab.go.id Email: kesbangpol@kukarkab.go.id kode pos 75514</p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'penajam paser utara'))
    <div class="letterhead">
        @if($logo && file_exists(public_path('storage/' . $logo)))
            <div class="logo-container">
                <img src="{{ public_path('storage/' . $logo) }}" class="logo">
            </div>
        @endif
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN PENAJAM PASER UTARA</h2>
            <h3>SEKRETARIAT DAERAH</h3>
            <p>Jalan Propinsi Km. 09 Nipah-Nipah. Telp. (0542) 7211400 Fax. (0542) 7211515</p>
            <p class="website-email"> PENAJAM 76141</p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'kutai barat'))
    <div class="letterhead">
        @if($logo && file_exists(public_path('storage/' . $logo)))
            <div class="logo-container">
                <img src="{{ public_path('storage/' . $logo) }}" class="logo">
            </div>
        @endif
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN KUTAI BARAT</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Alamat : Pusat Perkantoran Pemerintah Kabupaten Kutai Barat</p>
            <p>SENDAWAR</p>
        </div>
    </div>

    @elseif(str_contains(strtolower($kabupatenName), 'samarinda'))
    <div class="letterhead">
        @if($logo && file_exists(public_path('storage/' . $logo)))
            <div class="logo-container">
                <img src="{{ public_path('storage/' . $logo) }}" class="logo">
            </div>
        @endif
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
        @if($logo && file_exists(public_path('storage/' . $logo)))
            <div class="logo-container">
                <img src="{{ public_path('storage/' . $logo) }}" class="logo">
            </div>
        @endif
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
        @if($logo && file_exists(public_path('storage/' . $logo)))
            <div class="logo-container">
                <img src="{{ public_path('storage/' . $logo) }}" class="logo">
            </div>
        @endif
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN PASER</h2>
            <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
            <p>Jl. Kesuma Bangsa Km.05, Komplek Perkantoran Pemkab. paser</p>
            <p>Gedung F Lantai 2, paser, kalimantan timur 76215</p>
            <p class="website-email">Laman kesbangpol.paserkab.go.id, Pos-el kesbangpol_paser@yahoo.com</p>
        </div>
    </div>
@endif

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
                    @if($isPilkadaTunggal)
                        <th class="text-center bg-blue-950 text-white">
                            {{ number_format($data->sum('kotak_kosong'), 0, ',', '.') }}
                        </th>
                    @endif
                    @foreach($paslon as $calon)
                        <th class="text-center bg-blue-950 text-white">
                            @if($isTpsView)
                                {{ number_format($data->sum(fn($item) => $item->suaraCalonByCalonId($calon->id)?->first()?->suara ?? 0), 0, ',', '.') }}
                            @else
                                {{ number_format($data->sum(fn($item) => $item->getCalonSuaraByCalonId($calon->id)?->total_suara ?? 0), 0, ',', '.') }}
                            @endif
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

      <div class="footnote">
        <p>Dokumen ini telah dicetak melalui Aplikasi SIPPPDEH Prov.Kaltim oleh {{ session('operator_name') }}<br>
        {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>