@php
    $isProvinsiColumnIgnored = !in_array('PROVINSI', $includedColumns);
    $isKabupatenColumnIgnored = !in_array('KABUPATEN/KOTA', $includedColumns);
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);

    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);
    $isPilkadaTunggal = count($paslon) == 1;
@endphp

@php
    $totalDpt = $suara->sum(fn ($datum) => $datum->dpt ?? 0);
    $totalSuaraSah = $suara->sum(fn ($datum) => $datum->suara_sah ?? 0);
    $totalSuaraTidakSah = $suara->sum(fn ($datum) => $datum->suara_tidak_sah ?? 0);
    $totalSuaraMasuk = $suara->sum(fn ($datum) => $datum->suara_masuk ?? 0);
    $totalAbstain = $suara->sum(fn ($datum) => $datum->abstain ?? 0);
    
    try {
        $totalPartisipasi = ($totalSuaraMasuk / $totalDpt) * 100;
    } catch (DivisionByZeroError $error) {
        $totalPartisipasi = 0;
    }

    $totalsPerCalon = [];
    foreach ($paslon as $calon) {
        $totalsPerCalon[$calon->id] = $suara->sum(fn($datum) => $datum->getCalonSuaraByCalonId($calon->id)?->total_suara ?? 0);
    }

    $totalKotakKosong = $suara->sum(fn ($datum) => $datum->kotak_kosong ?? 0);
@endphp

<table class="voting-table">
    <thead>
        <tr>
            {{-- <th rowspan="2" style="border: 1px solid black; vertical-align: middle; text-align: center; width: 50px;">
                NO
            </th> --}}
			
            @if (!$isProvinsiColumnIgnored)
                <th rowspan="2" style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                    Provinsi
                </th>
            @endif

            @if (!$isKabupatenColumnIgnored)
                <th rowspan="2" style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                    Kabupaten/Kota
                </th>
            @endif
            
            @if (!$isKecamatanColumnIgnored)
                <th rowspan="2" style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                    Kecamatan
                </th>
            @endif

            @if (!$isKelurahanColumnIgnored)
                <th rowspan="2" style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                    Kelurahan
                </th>
            @endif
            
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                <span>DPT</span>
            </th>            

            @if (!$isCalonColumnIgnored)
                @if ($isPilkadaTunggal)
                    <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                        <span>Kotak Kosong</span>
                    </th>
                @endif

                @foreach ($paslon as $calon)
                    <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                        <span>{{ $calon->nama }}/<br>{{ $calon->nama_wakil }}</span>
                    </th>
                @endforeach
            @endif

            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                <span>Suara Sah</span>
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                <span>Suara Tidak Sah</span>
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                <span>Suara Masuk</span>
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                <span>Abstain</span>
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                <span>Partisipasi</span>
            </th>
        </tr>
        <tr>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center;">
                {{ number_format($totalDpt, 0, '', '') }}
            </th>
        
            @if (!$isCalonColumnIgnored)
                @if ($isPilkadaTunggal)
                    <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                        {{ number_format($totalKotakKosong, 0, '', '') }}
                    </th>
                @endif
                
                @foreach ($paslon as $calon)
                    <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                        {{ number_format($totalsPerCalon[$calon->id], 0, '', '') }}
                    </th>
                @endforeach
            @endif
        
            <th style="border: 1px solid black; vertical-align: middle; text-align: center;">
                {{ number_format($totalSuaraSah, 0, '', '') }}
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center;">
                {{ number_format($totalSuaraTidakSah, 0, '', '') }}
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center;">
                {{ number_format($totalSuaraMasuk, 0, '', '') }}
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center;">
                {{ number_format($totalAbstain, 0, '', '') }}
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center;">
                {{ number_format($totalPartisipasi, 1, '.', '') }}%
            </th>
        </tr>
    </thead>

    <tbody>
        @forelse ($suara as $datum)
            <tr>
                {{-- <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ $datum->getThreeDigitsId() }}
                </td> --}}

                @if (!$isProvinsiColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: left;">
                        {{ $datum->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}
                    </td>
                @endif

                @if (!$isKabupatenColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: left;">
                        {{ $datum->kecamatan?->kabupaten?->nama ?? '-' }}
                    </td>
                @endif

                @if (!$isKecamatanColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: left;">
                        {{ $datum->kecamatan?->nama ?? '-' }}
                    </td>
                @endif

                @if (!$isKelurahanColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: left;">
                        {{ $datum->nama }}
                    </td>
                @endif

                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ number_format($datum->dpt, 0, '', '') }}
                </td>

                @if (!$isCalonColumnIgnored)
                    @if ($isPilkadaTunggal)
                        <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                            {{ number_format($datum->kotak_kosong, 0, '', '') }}
                        </td>
                    @endif
                    
                    @foreach ($paslon as $calon)
                        @php
                            $suara = $datum->getCalonSuaraByCalonId($calon->id);
                        @endphp
                        <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                            {{ number_format($suara ? $suara->total_suara : 0, 0, '', '') }}
                        </td>
                    @endforeach
                @endif

                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ number_format($datum->suara_sah, 0, '', '') }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ number_format($datum->suara_tidak_sah, 0, '', '') }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ number_format($datum->suara_masuk, 0, '', '') }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    {{ number_format($datum->abstain, 0, '', '') }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center;">
                    @if ($datum->partisipasi >= 77.5)
                        <span class="bg-green-400 block text-white py-1 px-7 rounded text-xs">
                            {{ number_format($datum->partisipasi, 1, '.', '') }}%
                        </span>
                    @else
                        <span class="bg-red-400 block text-white py-1 px-7 rounded text-xs">
                            {{ number_format($datum->partisipasi, 1, '.', '') }}%
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="15" class="empty-message">
                    Data tidak tersedia.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>