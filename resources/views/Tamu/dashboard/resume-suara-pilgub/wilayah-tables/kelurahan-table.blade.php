@php
    $isProvinsiColumnIgnored = !in_array('PROVINSI', $includedColumns);
    $isKabupatenColumnIgnored = !in_array('KABUPATEN/KOTA', $includedColumns);
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);

    $isPilkadaTunggal = count($paslon) == 1;
@endphp

@php
    $totalDpt = $suara->sum(fn ($datum) => $datum->dpt ?? 0);
    $totalSuaraMasuk = $suara->sum(fn ($datum) => $datum->suara_masuk ?? 0);
    
    try {
        $totalPartisipasi = ($totalSuaraMasuk / $totalDpt) * 100;
    } catch (DivisionByZeroError $error) {
        $totalPartisipasi = 0;
    }

    $totalsPerCalon = [];
    foreach ($paslon as $calon) {
        $totalsPerCalon[$calon->id] = $suara->sum(fn($datum) => $datum->getCalonSuaraByCalonId($calon->id)?->total_suara ?? 0);
    }

    $totalKotakKosong = $suara->sum(fn ($datum) => $datum->kotak_kosong ?? 0);
@endphp

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-[#3560A0] text-white">
        <tr>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
                NO
            </th>
			
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isProvinsiColumnIgnored ? 'hidden' : '' }}" style="width: 150px;">
                Provinsi
            </th>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKabupatenColumnIgnored ? 'hidden' : '' }}" style="width: 150px;">
                Kabupaten/Kota
            </th>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}" style="width: 150px;">
                Kecamatan
            </th>
            <th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}" style="width: 150px;">
                Kelurahan
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

            {{-- Kotak Kosong --}}
            @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950">
                    {{ number_format($totalKotakKosong, 0, '.', '.') }}
                </th>
            @endif
        
            {{-- Calon Totals --}}
            @if (!$isCalonColumnIgnored)
                @foreach ($paslon as $calon)
                    <th wire:key="total-{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950">
                        {{ number_format($totalsPerCalon[$calon->id], 0, '.', '.') }}
                    </th>
                @endforeach
            @endif
            
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none">
                {{ number_format($totalSuaraMasuk, 0, '.', '.') }}
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none">
                {{ number_format($totalPartisipasi, 1, '.', '.') }}%
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
                    {{ $datum->kecamatan->kabupaten->nama }}
                </td>

                {{-- Kecamatan --}}
                <td class="py-3 px-4 text-xs text-left border kecamatan {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}">
                    {{ $datum->kecamatan?->nama ?? '-' }}
                </td>

                {{-- Kelurahan --}}
                <td class="py-3 px-4 text-xs text-left border kelurahan {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}">
                    {{ $datum->nama }}
                </td>

                {{-- DPT --}}
                <td class="py-3 px-4 text-xs border dpt">
                    {{ number_format($datum->dpt, 0, '', '.') }}
                </td>

                {{-- Kotak Kosong --}}
                @if ($isPilkadaTunggal && !$isCalonColumnIgnored)
                    <td class="py-3 px-4 text-xs border kotak-kosong">
                        {{ number_format($datum->kotak_kosong, 0, '', '.') }}
                    </td>
                @endif

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

                {{-- Suara Masuk --}}
                <td class="py-3 px-4 text-xs border suara-masuk">
                    {{ number_format($datum->suara_masuk, 0, '', '.') }}
                </td>

                {{-- Partisipasi --}}
                <td class="py-3 px-4 text-xs border partisipasi">
					@if ($datum->partisipasi >= 77.5)
                        <span class="bg-green-400 block text-white py-1 px-7 rounded text-xs">
                            {{ number_format($datum->partisipasi, 1, '.', '.') }}%
                        </span>
                    @else
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