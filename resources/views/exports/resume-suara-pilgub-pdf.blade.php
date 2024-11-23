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
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 100px;
            margin: 10px auto;
            display: block;
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
    </style>
</head>
<body>
    <div class="header">
        @if($logo && file_exists(public_path('storage/' . $logo)))
            <img src="{{ public_path('storage/' . $logo) }}" class="logo">
        @endif
        <h3>HASIL REKAPITULASI SUARA</h3>
        <h4>PEMILIHAN GUBERNUR DAN WAKIL GUBERNUR</h4>
        <h4>{{ strtoupper($kabupaten->nama) }}</h4>
    </div>

    <table autosize="1">
        <thead>
            <tr>
                <th width="5%" class="text-center">NO</th>
                @if(in_array('KABUPATEN/KOTA', $includedColumns))
                    <th width="20%">KABUPATEN/KOTA</th>
                @endif
                <th width="10%" class="text-center">DPT</th>
                @foreach($paslon as $calon)
                    <th class="text-center">{{ $calon->nama }}<br>{{ $calon->nama_wakil }}</th>
                @endforeach
                <th width="10%" class="text-center">SUARA SAH</th>
                <th width="10%" class="text-center">SUARA TIDAK SAH</th>
                <th width="10%" class="text-center">PARTISIPASI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    @if(in_array('KABUPATEN/KOTA', $includedColumns))
                        <td>{{ $item->nama }}</td>
                    @endif
                    <td class="text-right">{{ number_format($item->dpt, 0, ',', '.') }}</td>
                    @foreach($paslon as $calon)
                        @php
                            $suara = $item->getCalonSuaraByCalonId($calon->id);
                            $totalSuara = $suara ? $suara->total_suara : 0;
                        @endphp
                        <td class="text-right">
                            {{ number_format($totalSuara, 0, ',', '.') }}
                        </td>
                    @endforeach
                    <td class="text-right">{{ number_format($item->suara_sah, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->suara_tidak_sah, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item->partisipasi, 1, ',', '.') }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 5 + count($paslon) }}" class="text-center">Data tidak tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>