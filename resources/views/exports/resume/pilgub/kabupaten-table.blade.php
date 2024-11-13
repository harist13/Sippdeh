@php
    $isProvinsiColumnIgnored = !in_array('PROVINSI', $includedColumns);
    $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);
    $isPilkadaTunggal = count($paslon) == 1;
@endphp

<table style="width: 100%; border-collapse: collapse;">
    <thead style="background-color: #3560A0; color: white;">
        <tr>
            <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="50px">
                NO
            </th>
            @if (!$isKabupatenColumnIgnored)
                <th style="text-align: center; vertical-align: center; font-weight: bold; border: 1px solid black;" width="200px">
                    Kabupaten
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
                    @if ($datum->partisipasi <= 100 && $datum->partisipasi >= 80)
                        <span style="background-color: #4CAF50; color: white; padding: 5px 10px; border-radius: 3px;">
                            {{ $datum->partisipasi }}%
                        </span>
                    @elseif ($datum->partisipasi < 80 && $datum->partisipasi >= 60)
                        <span style="background-color: #FFEB3B; color: white; padding: 5px 10px; border-radius: 3px;">
                            {{ $datum->partisipasi }}%
                        </span>
                    @else
                        <span style="background-color: #F44336; color: white; padding: 5px 10px; border-radius: 3px;">
                            {{ $datum->partisipasi }}%
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>