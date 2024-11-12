@php
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);

    $isPilkadaTunggal = count($paslon) == 1;
@endphp

<table>
    <thead>
        <tr>
            <th style="min-width: 50px;">
                NO
            </th>
            
            @if (!$isKecamatanColumnIgnored)
                <th style="min-width: 100px;">
                    Kecamatan
                </th>
            @endif
            @if (!$isKelurahanColumnIgnored)
                <th style="min-width: 100px;">
                    Kelurahan
                </th>
            @endif
            <th style="min-width: 50px;">
                DPT
            </th>

            @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                <th width="50px">
                    Kotak Kosong
                </th>
            @endif

            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th width="100px">
                        {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                    </th>
                @endforeach
            @endif

            <th style="min-width: 50px;">
                Suara Sah
            </th>
            <th style="min-width: 50px;">
                Suara Tidak Sah
            </th>
            <th style="min-width: 50px;">
                Suara Masuk
            </th>
            <th style="min-width: 50px;">
                Abstain
            </th>
            <th style="min-width: 50px;">
                Partisipasi
            </th>
        </tr>
    </thead>

    <tbody>
        @forelse ($suara as $datum)
            <tr wire:key="{{ $datum->id }}">
                {{-- ID TPS --}}
                <td>
                    {{ $datum->getThreeDigitsId() }}
                </td>

                {{-- Kecamatan --}}
                @if (!$isKecamatanColumnIgnored)
                    <td>
                        {{ $datum->kecamatan->nama }}
                    </td>
                @endif

                {{-- Kelurahan --}}
                @if (!$isKelurahanColumnIgnored)
                    <td>
                        {{ $datum->nama }}
                    </td>
                @endif

                {{-- DPT --}}
                <td>
                    <span class="value">{{ $datum->dpt }}</span>
                </td>

                {{-- Kotak Kosong --}}
                @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                    <td>
                        {{ $datum->kotak_kosong }}
                    </td>
                @endif

                {{-- Calon-calon --}}
                @if (!$isCalonColumnIgnored)
                    @foreach ($paslon as $calon)
                        @php
                            $suara = $datum->getCalonSuaraByCalonId($calon->id);
                        @endphp
                        <td wire:key="{{ $datum->id }}{{ $calon->id }}">
                            {{ $suara ? $suara->total_suara : 0 }}
                        </td>
                    @endforeach
                @endif

                {{-- Suara Sah --}}
                <td>
                    {{ $datum->suara_sah }}
                </td>

                {{-- Suara Tidak Sah --}}
                <td>
                    {{ $datum->suara_tidak_sah }}
                </td>

                {{-- Suara Masuk --}}
                <td>
                    {{ $datum->suara_masuk }}
                </td>

                {{-- Abstain --}}
                <td>
                    {{ $datum->abstain }}
                </td>

                {{-- Partisipasi --}}
                <td>
                    @if ($datum->partisipasi <= 100 && $datum->partisipasi >= 80)
                        <span>
                            {{ $datum->partisipasi }}%
                        </span>
                    @endif

                    @if ($datum->partisipasi < 80 && $datum->partisipasi >= 60)
                        <span>
                            {{ $datum->partisipasi }}%
                        </span>
                    @endif

                    @if ($datum->partisipasi < 60)
                        <span>
                            {{ $datum->partisipasi }}%
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="15" style="text-align: center;">
                    Data tidak tersedia.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
