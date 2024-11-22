@php
    $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    $isTPSColumnIgnored = !in_array('TPS', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);

    $isPilkadaTunggal = count($paslon) == 1;
@endphp

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-[#3560A0] text-white">
        <tr>
            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
                NO
            </th>
			
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKabupatenColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
                Kabupaten/Kota
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
                Kecamatan
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
                Kelurahan
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isTPSColumnIgnored ? 'hidden' : '' }}" style="width: 120px;">
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
                <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isCalonColumnIgnored ? 'hidden' : '' }} bg-blue-950" style="min-width: 100px;">
                    Kotak Kosong
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
    </thead>

    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
        @forelse ($tps as $datum)
            <tr wire:key="{{ $datum->id }}" class="border-b text-center select-none cursor-pointer tps" data-id="{{ $datum->id }}">
                {{-- ID TPS --}}
                <td class="py-3 px-4 border nomor" data-id="{{ $datum->id }}">
                    {{ $datum->getThreeDigitsId() }}
                </td>

                {{-- Kabupaten --}}
                <td class="py-3 px-4 text-xs text-left border kecamatan {{ $isKabupatenColumnIgnored ? 'hidden' : '' }}" data-kabupaten-id="{{ $datum->tps?->kelurahan?->kecamatan?->kabupaten?->id ?? '-' }}">
                    {{ $datum->tps?->kelurahan?->kecamatan?->kabupaten?->nama ?? '-' }}
                </td>

                {{-- Kecamatan --}}
                <td class="py-3 px-4 text-xs text-left border kecamatan {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}" data-kecamatan-id="{{ $datum->tps?->kelurahan?->kecamatan?->id ?? '-' }}">
                    {{ $datum->tps?->kelurahan?->kecamatan?->nama ?? '-' }}
                </td>

                {{-- Kelurahan --}}
                <td class="py-3 px-4 text-xs text-left border kelurahan {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}" data-kelurahan-id="{{ $datum->tps?->kelurahan?->id ?? '-' }}">
                    {{ $datum->tps?->kelurahan?->nama ?? '-' }}
                </td>

                {{-- Nama TPS --}}
                <td class="py-3 px-4 border text-xs text-left tps {{ $isTPSColumnIgnored ? 'hidden' : '' }}">{{ $datum->nama }}</td>

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
                    <td wire:key="{{ $datum->id }}{{ $calon->id }}" class="py-3 px-4 text-xs border paslon {{ $isCalonColumnIgnored ? 'hidden' : '' }}" data-id="{{ $calon->id }}" data-suara="{{ $suara }}">
                        <span class="value">{{ $suara }}</span>
                        <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" value="{{ $suara }}" data-default-value="{{ $suara }}" autocomplete="off">
                    </td>
                @endforeach

                {{-- Kotak Kosong --}}
                <td class="py-3 px-4 text-xs border kotak-kosong {{ $isCalonColumnIgnored ? 'hidden' : '' }}" data-value="{{ $datum->kotak_kosong }}" {{ !$isPilkadaTunggal ? 'hidden' : '' }}>
                    <span class="value">{{ $datum->kotak_kosong }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" value="{{ $datum->kotak_kosong }}" data-default-value="{{ $datum->kotak_kosong }}" autocomplete="off">
                </td>

                {{-- Suara Sah --}}
                <td class="py-3 px-4 text-xs border suara-sah" data-value="{{ $datum->suara_sah }}">
                    {{ $datum->suara_sah }}
                </td>

                {{-- Suara Tidak Sah --}}
                <td class="py-3 px-4 text-xs border suara-tidak-sah" data-value="{{ $datum->suara_tidak_sah }}">
                    <span class="value">{{ $datum->suara_tidak_sah }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" data-default-value="{{ $datum->suara_tidak_sah }}" data-value="{{ $datum->suara_tidak_sah }}">
                </td>

                {{-- Suara Masuk --}}
                <td class="py-3 px-4 text-xs border suara-masuk" data-value="{{ $datum->suara_masuk }}">
                    {{ $datum->suara_masuk }}
                </td>

                {{-- Abstain --}}
                <td class="py-3 px-4 text-xs border abstain" data-value="{{ $datum->abstain }}">
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