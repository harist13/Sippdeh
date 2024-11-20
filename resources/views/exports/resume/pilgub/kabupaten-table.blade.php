@php
    $isKabupatenColumnIgnored = !in_array('KABUPATEN/KOTA', $includedColumns);
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
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="200px">
                    Kabupaten/Kota
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

    <tbody>
        @foreach ($suara as $datum)
            <tr>
                <td style="text-align: center; border: 1px solid black;">
                    {{ $datum->getThreeDigitsId() }}
                </td>

                @if (!$isKabupatenColumnIgnored)
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
                        @php
                            $suara = $datum->getCalonSuaraByCalonId($calon->id);
                        @endphp
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
                    @if ($datum->partisipasi >= 80)
                        <span style="background-color: #4CAF50; color: white; padding: 5px 10px; border-radius: 3px;">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @elseif ($datum->partisipasi < 80 && $datum->partisipasi >= 60)
                        <span style="background-color: #FFEB3B; color: white; padding: 5px 10px; border-radius: 3px;">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @else
                        <span style="background-color: #F44336; color: white; padding: 5px 10px; border-radius: 3px;">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>