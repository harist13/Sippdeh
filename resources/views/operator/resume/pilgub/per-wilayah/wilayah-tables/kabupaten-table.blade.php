@php
    $isProvinsiColumnIgnored = !in_array('PROVINSI', $includedColumns);
    $isKabupatenColumnIgnored = !in_array('KABUPATEN/KOTA', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);

    $isPilkadaTunggal = count($paslon) == 1;
@endphp

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-[#3560A0] text-white">
        <tr>
            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
                NO
            </th>
			
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKabupatenColumnIgnored ? 'hidden' : '' }}" style="width: 300px;">
                Kabupaten/Kota
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
    </thead>

    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
        @forelse ($suara as $datum)
            <tr wire:key="{{ $datum->id }}" class="border-b text-center select-none tps">
                {{-- ID TPS --}}
                <td class="py-3 px-4 border nomor">
                    {{ $datum->getThreeDigitsId() }}
                </td>

                {{-- Kabupaten --}}
                <td class="py-3 px-4 text-xs text-left border kabupaten {{ $isKabupatenColumnIgnored ? 'hidden' : '' }}">
                    {{ $datum->nama }}
                </td>

                {{-- DPT --}}
                <td class="py-3 px-4 text-xs border dpt">
                    <span class="value">{{ number_format($datum->dpt, 0, '', '.') }}</span>
                </td>

                {{-- Calon-calon --}}
                @if (!$isCalonColumnIgnored)
                    @foreach ($paslon as $calon)
                        @php
                            $suara = $datum->getCalonSuaraByCalonId($calon->id);
                        @endphp
                        <td wire:key="{{ $datum->id }}{{ $calon->id }}" class="py-3 px-4 text-xs border paslon">
                            {{ number_format($suara ? $suara->total_suara : 0, 0, '', '.') }}
                        </td>
                    @endforeach
                @endif

                {{-- Kotak Kosong --}}
                @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                    <td class="py-3 px-4 text-xs border kotak-kosong">
                        {{ number_format($datum->kotak_kosong, 0, '', '.') }}
                    </td>
                @endif

                {{-- Suara Sah --}}
                <td class="py-3 px-4 text-xs border suara-sah">
                    {{ number_format($datum->suara_sah, 0, '', '.') }}
                </td>

                {{-- Suara Tidak Sah --}}
                <td class="py-3 px-4 text-xs border suara-tidak-sah">
                    {{ number_format($datum->suara_tidak_sah, 0, '', '.') }}
                </td>

                {{-- Suara Masuk --}}
                <td class="py-3 px-4 text-xs border suara-masuk">
                    {{ number_format($datum->suara_masuk, 0, '', '.') }}
                </td>

                {{-- Abstain --}}
                <td class="py-3 px-4 text-xs border abstain">
                    {{ number_format($datum->abstain, 0, '', '.') }}
                </td>

                {{-- Partisipasi --}}
                <td class="py-3 px-4 text-xs border partisipasi">
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