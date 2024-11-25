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
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
                NO
            </th>
			
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isProvinsiColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
                Provinsi
            </th>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKabupatenColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
                Kabupaten/Kota
            </th>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
                Kecamatan
            </th>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
                Kelurahan
            </th>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isTPSColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
                TPS
            </th>

            <th wire:click="sortDpt" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none cursor-pointer" style="min-width: 50px;">
                <span>DPT</span>
                @if ($dptSort === null)
                    <i class="fas fa-sort ml-2"></i>
                @elseif ($dptSort === 'asc')
                    <i class="fas fa-sort-up ml-2"></i>
                @elseif ($dptSort === 'desc')
                    <i class="fas fa-sort-down ml-2"></i>
                @endif
            </th>            

            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th wire:key="{{ $calon->id }}" wire:click="sortPaslonById({{ $calon->id }})" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none cursor-pointer bg-blue-950" style="min-width: 100px;">
                        <span>{{ $calon->nama }}/<br>{{ $calon->nama_wakil }}</span>
                        @if ($paslonIdSort != $calon->id)
                            <i class="fas fa-sort ml-2"></i>
                        @elseif ($paslonSort === 'asc' && $paslonIdSort == $calon->id)
                            <i class="fas fa-sort-up ml-2"></i>
                        @elseif ($paslonSort === 'desc' && $paslonIdSort == $calon->id)
                            <i class="fas fa-sort-down ml-2"></i>
                        @endif
                    </th>
                @endforeach
            @endif

            @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                <th wire:click="sortKotakKosong" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none cursor-pointer {{ $isCalonColumnIgnored ? 'hidden' : '' }} bg-blue-950" style="min-width: 100px;">
                    <span>Kotak Kosong</span>
                    @if ($kotakKosongSort === null)
                        <i class="fas fa-sort ml-2"></i>
                    @elseif ($kotakKosongSort === 'asc')
                        <i class="fas fa-sort-up ml-2"></i>
                    @elseif ($kotakKosongSort === 'desc')
                        <i class="fas fa-sort-down ml-2"></i>
                    @endif
                </th>
            @endif

            <th wire:click="sortSuaraSah" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none cursor-pointer" style="min-width: 50px;">
                <span>Suara Sah</span>
                @if ($suaraSahSort === null)
                    <i class="fas fa-sort ml-2"></i>
                @elseif ($suaraSahSort === 'asc')
                    <i class="fas fa-sort-up ml-2"></i>
                @elseif ($suaraSahSort === 'desc')
                    <i class="fas fa-sort-down ml-2"></i>
                @endif
            </th>
            <th wire:click="sortSuaraTidakSah" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none cursor-pointer" style="min-width: 50px;">
                <span>Suara Tidak Sah</span>
                @if ($suaraTidakSahSort === null)
                    <i class="fas fa-sort ml-2"></i>
                @elseif ($suaraTidakSahSort === 'asc')
                    <i class="fas fa-sort-up ml-2"></i>
                @elseif ($suaraTidakSahSort === 'desc')
                    <i class="fas fa-sort-down ml-2"></i>
                @endif
            </th>
            <th wire:click="sortSuaraMasuk" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none cursor-pointer" style="min-width: 50px;">
                <span>Suara Masuk</span>
                @if ($suaraMasukSort === null)
                    <i class="fas fa-sort ml-2"></i>
                @elseif ($suaraMasukSort === 'asc')
                    <i class="fas fa-sort-up ml-2"></i>
                @elseif ($suaraMasukSort === 'desc')
                    <i class="fas fa-sort-down ml-2"></i>
                @endif
            </th>
            <th wire:click="sortAbstain" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none cursor-pointer" style="min-width: 50px;">
                <span>Abstain</span>
                @if ($abstainSort === null)
                    <i class="fas fa-sort ml-2"></i>
                @elseif ($abstainSort === 'asc')
                    <i class="fas fa-sort-up ml-2"></i>
                @elseif ($abstainSort === 'desc')
                    <i class="fas fa-sort-down ml-2"></i>
                @endif
            </th>
            <th wire:click="sortPartisipasi" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none cursor-pointer" style="min-width: 50px;">
                <span>Partisipasi</span>
                @if ($partisipasiSort === null)
                    <i class="fas fa-sort ml-2"></i>
                @elseif ($partisipasiSort === 'asc')
                    <i class="fas fa-sort-up ml-2"></i>
                @elseif ($partisipasiSort === 'desc')
                    <i class="fas fa-sort-down ml-2"></i>
                @endif
            </th>
        </tr>
        <tr>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none">
                {{ number_format($totalDpt, 0, '.', '.') }}
            </th>
        
            {{-- Calon Totals --}}
            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th wire:key="total-{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950">
                        {{ number_format($totalsPerCalon[$calon->id], 0, '.', '.') }}
                    </th>
                @endforeach
            @endif
        
            {{-- Kotak Kosong --}}
            @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950">
                    {{ $totalKotakKosong }}
                </th>
            @endif
        
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none">
                {{ number_format($totalSuaraSah, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none">
                {{ number_format($totalSuaraTidakSah, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none">
                {{ number_format($totalSuaraMasuk, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none">
                {{ number_format($totalAbstain, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none">
                {{ number_format($totalPartisipasi, 1, '.', '.') }}%
            </th>
        </tr>
    </thead>

    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
        @forelse ($tps as $datum)
            <tr wire:key="{{ $datum->id }}" class="border-b text-center select-none cursor-pointer tps" data-id="{{ $datum->id }}">
                {{-- ID TPS --}}
                <td class="py-3 px-4 border nomor" data-id="{{ $datum->id }}">
                    {{ $datum->getThreeDigitsId() }}
                </td>

                {{-- Provinsi --}}
                <td class="py-3 px-4 text-xs text-left border provinsi {{ $isProvinsiColumnIgnored ? 'hidden' : '' }}">
                    {{ $datum->tps?->kelurahan?->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}
                </td>

                {{-- Kabupaten --}}
                <td class="py-3 px-4 text-xs text-left border kabupaten {{ $isKabupatenColumnIgnored ? 'hidden' : '' }}">
                    {{ $datum->tps?->kelurahan?->kecamatan?->kabupaten?->nama ?? '-' }}
                </td>

                {{-- Kecamatan --}}
                <td class="py-3 px-4 text-xs text-left border kecamatan {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}">
                    {{ $datum->tps?->kelurahan?->kecamatan?->nama ?? '-' }}
                </td>

                {{-- Kelurahan --}}
                <td class="py-3 px-4 text-xs text-left border kelurahan {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}">
                    {{ $datum->tps?->kelurahan?->nama ?? '-' }}
                </td>

                {{-- Nama TPS --}}
                <td class="py-3 px-4 border text-xs text-left tps {{ $isTPSColumnIgnored ? 'hidden' : '' }}">
                    {{ $datum->nama }}
                </td>

                {{-- DPT --}}
                <td class="py-3 px-4 text-xs border dpt" data-value="{{ $datum->dpt }}">
                    <span class="value">{{ $datum->dpt }}</span>
                </td>

                {{-- Calon-calon --}}
                @foreach ($paslon as $calon)
                    @php
                        $suaraCalon = $datum->suaraCalonByCalonId($calon->id)->first();
                        $suara = $suaraCalon != null ? $suaraCalon->suara : 0;
                    @endphp
                    <td wire:key="{{ $datum->id }}{{ $calon->id }}" class="py-3 px-4 text-xs border paslon {{ $isCalonColumnIgnored ? 'hidden' : '' }}">
                        <span class="value">{{ $suara }}</span>
                    </td>
                @endforeach

                {{-- Kotak Kosong --}}
                <td class="py-3 px-4 text-xs border kotak-kosong {{ $isPilkadaTunggal && !$isCalonColumnIgnored ? '' : 'hidden' }}">
                    <span class="value">{{ $datum->kotak_kosong }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" value="{{ $datum->kotak_kosong }}" data-default-value="{{ $datum->kotak_kosong }}" autocomplete="off">
                </td>

                {{-- Suara Sah --}}
                <td class="py-3 px-4 text-xs border suara-sah">
                    {{ $datum->suara_sah }}
                </td>

                {{-- Suara Tidak Sah --}}
                <td class="py-3 px-4 text-xs border suara-tidak-sah">
                    <span class="value">{{ $datum->suara_tidak_sah }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" data-default-value="{{ $datum->suara_tidak_sah }}" data-value="{{ $datum->suara_tidak_sah }}">
                </td>

                {{-- Suara Masuk --}}
                <td class="py-3 px-4 text-xs border suara-masuk">
                    {{ $datum->suara_masuk }}
                </td>

                {{-- Abstain --}}
                <td class="py-3 px-4 text-xs border abstain">
                    {{ $datum->abstain }}
                </td>

                {{-- Partisipasi --}}
                <td class="py-3 px-4 text-xs border partisipasi {{ strtolower($datum->partisipasi) }}">
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