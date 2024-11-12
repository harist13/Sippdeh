@php
    $isProvinsiColumnIgnored = !in_array('PROVINSI', $includedColumns);
    $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);
    $isPilkadaTunggal = count($paslon) == 1;
@endphp

<table style="width: 100%; border-collapse: collapse;">
    <thead style="background-color: #3560A0; color: white;">
        <tr>
            <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white; min-width: 50px;">
                NO
            </th>
            @if (!$isKabupatenColumnIgnored)
                <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white; min-width: 100px;">
                    Kabupaten
                </th>
            @endif
            <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white; min-width: 50px;">
                DPT
            </th>

            @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white;" width="50px">
                    Kotak Kosong
                </th>
            @endif

            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white;" width="100px">
                        {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                    </th>
                @endforeach
            @endif

            <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white; min-width: 50px;">
                Suara Sah
            </th>
            <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white; min-width: 50px;">
                Suara Tidak Sah
            </th>
            <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white; min-width: 50px;">
                Suara Masuk
            </th>
            <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white; min-width: 50px;">
                Abstain
            </th>
            <th style="padding: 12px; text-align: center; font-weight: bold; font-size: 12px; border: 1px solid white; min-width: 50px;">
                Partisipasi
            </th>
        </tr>
    </thead>

    <tbody style="background-color: #F5F5F5;">
        @forelse ($suara as $datum)
            <tr>
                <td style="padding: 10px; border: 1px solid black;">
                    {{ $datum->getThreeDigitsId() }}
                </td>

                @if (!$isKabupatenColumnIgnored)
                    <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
                        {{ $datum->nama }}
                    </td>
                @endif

                <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
                    <span>{{ $datum->dpt }}</span>
                </td>

                @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                    <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
                        {{ $datum->kotak_kosong }}
                    </td>
                @endif

                @if (!$isCalonColumnIgnored)
                    @foreach ($paslon as $calon)
                        @php
                            $suara = $datum->getCalonSuaraByCalonId($calon->id);
                        @endphp
                        <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
                            {{ $suara ? $suara->total_suara : 0 }}
                        </td>
                    @endforeach
                @endif

                <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
                    {{ $datum->suara_sah }}
                </td>
                <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
                    {{ $datum->suara_tidak_sah }}
                </td>
                <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
                    {{ $datum->suara_masuk }}
                </td>
                <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
                    {{ $datum->abstain }}
                </td>
                <td style="padding: 10px; font-size: 12px; border: 1px solid black;">
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
        @empty
            <tr>
                <td colspan="15" style="padding: 20px; text-align: center; color: gray;">
                    Data tidak tersedia.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>