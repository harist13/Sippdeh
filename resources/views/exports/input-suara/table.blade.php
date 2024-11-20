@php
    $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    $isTPSColumnIgnored = !in_array('TPS', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);

    $isPilkadaTunggal = count($paslon) == 1;
@endphp

<table>
    <thead>
        <tr>
            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="50px">
                NO
            </th>

            @if (!$isKabupatenColumnIgnored)
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                    Kabupaten/Kota
                </th>
            @endif
            @if (!$isKecamatanColumnIgnored)
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                    Kecamatan
                </th>
            @endif
            @if (!$isKelurahanColumnIgnored)
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                    Kelurahan
                </th>
            @endif

            @if (!$isTPSColumnIgnored)
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                    TPS
                </th>
            @endif

            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                DPT
            </th>

            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="200px">
                        {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                    </th>
                @endforeach
            @endif

            @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="200px">
                    Kotak Kosong
                </th>
            @endif

            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                Suara Sah
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                Suara Tidak Sah
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                Suara Masuk
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                Abstain
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                Partisipasi
            </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($tps as $datum)
            <tr style="border-bottom: 1px solid;">
                {{-- ID TPS --}}
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->getThreeDigitsId() }}
                </td>

                {{-- Kabupaten --}}
                @if (!$isKabupatenColumnIgnored)
                    <td style="border: 1px solid black;">
                        {{ $datum->tps?->kelurahan?->kecamatan?->kabupaten?->nama ?? '-' }}
                    </td>
                @endif

                {{-- Kecamatan --}}
                @if (!$isKecamatanColumnIgnored)
                    <td style="border: 1px solid black;">
                        {{ $datum->tps?->kelurahan?->kecamatan?->nama ?? '-' }}
                    </td>
                @endif

                {{-- Kelurahan --}}
                @if (!$isKelurahanColumnIgnored)
                    <td style="border: 1px solid black;">
                        {{ $datum->tps?->kelurahan?->nama ?? '-' }}
                    </td>
                @endif

                {{-- TPS --}}
                @if (!$isTPSColumnIgnored)
                    <td style="border: 1px solid black;">
                        {{ $datum->nama }}
                    </td>
                @endif

                {{-- DPT --}}
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->dpt }}
                </td>

                {{-- Calon-calon --}}
                @if (!$isCalonColumnIgnored)
                    @foreach ($paslon as $calon)
                        @php
                            $suaraCalon = $datum->suaraCalonByCalonId($calon->id)->first();
                            $suara = $suaraCalon != null ? $suaraCalon->suara : 0;
                        @endphp
                        <td style="text-align: center; border: 1px solid black;">
                            {{ $suara }}
                        </td>
                    @endforeach
                @endif

                {{-- Kotak Kosong --}}
                @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                    <td style="text-align: center; border: 1px solid black;">
                        {{ $datum->kotak_kosong }}
                    </td>
                @endif

                {{-- Suara Sah --}}
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->suara_sah }}
                </td>

                {{-- Suara Tidak Sah --}}
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->suara_tidak_sah }}
                </td>

                {{-- Suara Masuk --}}
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->suara_masuk }}
                </td>

                {{-- Abstain --}}
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->abstain }}
                </td>

                {{-- Partisipasi --}}
                <td style="text-align: center; border: 1px solid black;">
                    @if ($datum->partisipasi >= 80)
                        <span>
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @endif

                    @if ($datum->partisipasi < 80 && $datum->partisipasi >= 60)
                        <span>
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @endif

                    @if ($datum->partisipasi < 60)
                        <span>
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
