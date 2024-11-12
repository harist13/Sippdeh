@php
    $isProvinsiColumnIgnored = !in_array('PROVINSI', $includedColumns);
    $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);

    $isPilkadaTunggal = count($paslon) == 1;
@endphp

<table style="width: 100%; border-collapse: collapse;">
    <thead style="background-color: #3560A0; color: white;">
        <tr>
            <th style="padding: 10px; text-align: center; font-weight: bold; font-size: small; border: 1px solid white; min-width: 50px;">
                NO
            </th>
            @if (!$isProvinsiColumnIgnored)
                <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 100px;">
                    Provinsi
                </th>
            @endif
            @if (!$isKabupatenColumnIgnored)
                <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 100px;">
                    Kabupaten
                </th>
            @endif
            @if (!$isKecamatanColumnIgnored)
                <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 100px;">
                    Kecamatan
                </th>
            @endif
            <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 50px;">
                DPT
            </th>
            @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white;" width="50px">
                    Kotak Kosong
                </th>
            @endif
            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white;" width="100px">
                        {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                    </th>
                @endforeach
            @endif
            <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 50px;">
                Suara Sah
            </th>
            <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 50px;">
                Suara Tidak Sah
            </th>
            <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 50px;">
                Suara Masuk
            </th>
            <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 50px;">
                Abstain
            </th>
            <th style="padding: 10px; text-align: center; font-weight: bold; font-size: x-small; border: 1px solid white; min-width: 50px;">
                Partisipasi
            </th>
        </tr>
    </thead>
    <tbody style="background-color: #F5F5F5;">
        @forelse ($suara as $datum)
            <tr style="border-bottom: 1px solid;">
                <td style="padding: 8px; text-align: center; border: 1px solid;">
                    {{ $datum->getThreeDigitsId() }}
                </td>
                @if (!$isProvinsiColumnIgnored)
                    <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                        {{ $datum->kabupaten->provinsi->nama }}
                    </td>
                @endif
                @if (!$isKabupatenColumnIgnored)
                    <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                        {{ $datum->kabupaten->nama }}
                    </td>
                @endif
                @if (!$isKecamatanColumnIgnored)
                    <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                        {{ $datum->nama }}
                    </td>
                @endif
                <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                    <span>{{ $datum->dpt }}</span>
                </td>
                @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                    <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                        {{ $datum->kotak_kosong }}
                    </td>
                @endif
                @if (!$isCalonColumnIgnored)
                    @foreach ($paslon as $calon)
                        @php $suara = $datum->getCalonSuaraByCalonId($calon->id); @endphp
                        <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                            {{ $suara ? $suara->total_suara : 0 }}
                        </td>
                    @endforeach
                @endif
                <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                    {{ $datum->suara_sah }}
                </td>
                <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                    {{ $datum->suara_tidak_sah }}
                </td>
                <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                    {{ $datum->suara_masuk }}
                </td>
                <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                    {{ $datum->abstain }}
                </td>
                <td style="padding: 8px; font-size: x-small; border: 1px solid;">
                    @if ($datum->partisipasi <= 100 && $datum->partisipasi >= 80)
                        <span style="background-color: #68D391; display: block; color: white; padding: 4px 14px; border-radius: 4px; font-size: x-small;">
                            {{ $datum->partisipasi }}%
                        </span>
                    @elseif ($datum->partisipasi < 80 && $datum->partisipasi >= 60)
                        <span style="background-color: #F6E05E; display: block; color: white; padding: 4px 14px; border-radius: 4px; font-size: x-small;">
                            {{ $datum->partisipasi }}%
                        </span>
                    @else
                        <span style="background-color: #FC8181; display: block; color: white; padding: 4px 14px; border-radius: 4px; font-size: x-small;">
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