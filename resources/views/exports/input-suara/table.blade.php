@php
    $isProvinsiColumnIgnored = !in_array('PROVINSI', $includedColumns);
    $isKabupatenColumnIgnored = !in_array('KABUPATEN/KOTA', $includedColumns);
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    $isTPSColumnIgnored = !in_array('TPS', $includedColumns);

    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);
    $isPilkadaTunggal = count($paslon) == 1;
@endphp

@php
    $totalDpt = $tps->sum(fn ($datum) => $datum->dpt ?? 0);
    $totalSuaraSah = $tps->sum(fn ($datum) => $datum->suara_sah ?? 0);
    $totalSuaraTidakSah = $tps->sum(fn ($datum) => $datum->suara_tidak_sah ?? 0);
    $totalSuaraMasuk = $tps->sum(fn ($datum) => $datum->suara_masuk ?? 0);
    $totalAbstain = $tps->sum(fn ($datum) => $datum->abstain ?? 0);
    $totalPartisipasi = $tps->avg(fn ($datum) => $datum->partisipasi ?? 0);

    $totalsPerCalon = [];
    foreach ($paslon as $calon) {
        $totalsPerCalon[$calon->id] = $tps->sum(fn($datum) => $datum->suaraCalonByCalonId($calon->id)?->first()?->suara ?? 0);
    }

    $totalKotakKosong = $tps->sum(fn ($datum) => $datum->kotak_kosong ?? 0);
@endphp

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-[#3560A0] text-white">
        <tr>
            <th rowspan="2" style="border: 1px solid black; vertical-align: middle; text-align: center; width: 50px;">
                NO
            </th>
            
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

            @if (!$isTPSColumnIgnored)
                <th rowspan="2" style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                    TPS
                </th>
            @endif

            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                <span>DPT</span>
            </th>            

            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                        <span>{{ $calon->nama }}/<br>{{ $calon->nama_wakil }}</span>
                    </th>
                @endforeach

                @if ($isPilkadaTunggal)
                    <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                        <span>Kotak Kosong</span>
                    </th>
                @endif
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
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                {{ number_format($totalDpt, 0, '.', '.') }}
            </th>
        
            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                        {{ number_format($totalsPerCalon[$calon->id], 0, '.', '.') }}
                    </th>
                @endforeach

                @if ($isPilkadaTunggal)
                    <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                        {{ $totalKotakKosong }}
                    </th>
                @endif
            @endif
        
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                {{ number_format($totalSuaraSah, 0, '.', '.') }}
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                {{ number_format($totalSuaraTidakSah, 0, '.', '.') }}
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                {{ number_format($totalSuaraMasuk, 0, '.', '.') }}
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                {{ number_format($totalAbstain, 0, '.', '.') }}
            </th>
            <th style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                {{ number_format($totalPartisipasi, 1, '.', '.') }}%
            </th>
        </tr>
    </thead>

    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
        @forelse ($tps as $datum)
            <tr class="border-b text-center select-none tps">
                <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 50px;">
                    {{ $datum->getThreeDigitsId() }}
                </td>

                @if (!$isProvinsiColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                        {{ $datum->tps?->kelurahan?->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}
                    </td>
                @endif

                @if (!$isKabupatenColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                        {{ $datum->tps?->kelurahan?->kecamatan?->kabupaten?->nama ?? '-' }}
                    </td>
                @endif

                @if (!$isKecamatanColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                        {{ $datum->tps?->kelurahan?->kecamatan?->nama ?? '-' }}
                    </td>
                @endif

                @if (!$isKelurahanColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                        {{ $datum->tps?->kelurahan?->nama ?? '-' }}
                    </td>
                @endif

                @if (!$isTPSColumnIgnored)
                    <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 300px;">
                        {{ $datum->nama }}
                    </td>
                @endif

                <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                    <span class="value">{{ $datum->dpt }}</span>
                </td>

                @if (!$isCalonColumnIgnored)
                    @foreach ($paslon as $calon)
                        @php
                            $suaraCalon = $datum->suaraCalonByCalonId($calon->id)->first();
                            $suara = $suaraCalon != null ? $suaraCalon->suara : 0;
                        @endphp
                        <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                            {{ $suara }}
                        </td>
                    @endforeach

                    @if ($isPilkadaTunggal)
                        <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                            {{ $datum->kotak_kosong }}
                        </td>
                    @endif
                @endif

                <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                    {{ $datum->suara_sah }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                    {{ $datum->suara_tidak_sah }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                    {{ $datum->suara_masuk }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                    {{ $datum->abstain }}
                </td>

                <td style="border: 1px solid black; vertical-align: middle; text-align: center; width: 150px;">
                    @if ($datum->partisipasi >= 80)
                        <span class="bg-green-400 block text-white py-1 px-7 rounded text-xs">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @endif

                    @if ($datum->partisipasi < 80 && $datum->partisipasi >= 60)
                        <span class="bg-yellow-400 block text-white py-1 px-7 rounded text-xs">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @endif

                    @if ($datum->partisipasi < 60)
                        <span class="bg-red-400 block text-white py-1 px-7 rounded text-xs">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="15" class="py-5 px-2 text-center text-gray-500">
                    Data tidak tersedia.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>