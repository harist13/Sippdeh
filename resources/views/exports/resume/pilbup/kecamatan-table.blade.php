@php
    $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
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
                    Kabupaten
                </th>
            @endif
            @if (!$isKecamatanColumnIgnored)
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                    Kecamatan
                </th>
            @endif

            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="100px">
                DPT
            </th>

            @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="200px">
                    Kotak Kosong
                </th>
            @endif
            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="200px">
                        {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                    </th>
                @endforeach
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
    <tbody style="background-color: #F5F5F5;">
        @foreach ($suara as $datum)
            <tr style="border-bottom: 1px solid;">
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->getThreeDigitsId() }}
                </td>

                @if (!$isKabupatenColumnIgnored)
                    <td style="border: 1px solid black;">
                        {{ $datum->kabupaten->nama }}
                    </td>
                @endif
                @if (!$isKecamatanColumnIgnored)
                    <td style="border: 1px solid black;">
                        {{ $datum->nama }}
                    </td>
                @endif
                
                <td style="text-align: center; border: 1px solid black;">
                    <span>{{ $datum->dpt }}</span>
                </td>

                @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                    <td style="text-align: center; border: 1px solid black;">
                        {{ $datum->kotak_kosong }}
                    </td>
                @endif
                @if (!$isCalonColumnIgnored)
                    @foreach ($paslon as $calon)
                        @php $suara = $datum->getCalonSuaraByCalonId($calon->id); @endphp
                        <td style="text-align: center; border: 1px solid black;">
                            {{ $suara ? $suara->total_suara : 0 }}
                        </td>
                    @endforeach
                @endif

                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->suara_sah }}
                </td>
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->suara_tidak_sah }}
                </td>
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->suara_masuk }}
                </td>
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->abstain }}
                </td>
                <td style="text-align: center; border: 1px solid black;">
                    @if ($datum->partisipasi <= 100 && $datum->partisipasi >= 80)
                        <span style="background-color: #68D391; display: block; color: white; padding: 4px 14px; border-radius: 4px;">
                            {{ $datum->partisipasi }}%
                        </span>
                    @elseif ($datum->partisipasi < 80 && $datum->partisipasi >= 60)
                        <span style="background-color: #F6E05E; display: block; color: white; padding: 4px 14px; border-radius: 4px;">
                            {{ $datum->partisipasi }}%
                        </span>
                    @else
                        <span style="background-color: #FC8181; display: block; color: white; padding: 4px 14px; border-radius: 4px;">
                            {{ $datum->partisipasi }}%
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>