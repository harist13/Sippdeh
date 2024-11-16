@php
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    $isTPSColumnIgnored = !in_array('TPS', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);

    $isPilkadaTunggal = count($paslon) == 1;
@endphp

@push('styles')
    <style>
        /* Disable spinner on number input */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-[#3560A0] text-white">
        <tr>
            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
                NO
            </th>
            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
                <input type="checkbox" id="checkAll" class="form-checkbox h-5 w-5 text-white border-white select-none rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
            </th>
			
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}" style="min-width: 100px;">
                Kecamatan
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}" style="min-width: 100px;">
                Kelurahan
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isTPSColumnIgnored ? 'hidden' : '' }}" style="min-width: 100px;">
                TPS
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                DPT
            </th>

            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isCalonColumnIgnored ? 'hidden' : '' }} bg-blue-950" style="min-width: 100px;" {{ !$isPilkadaTunggal ? 'hidden' : '' }}>
                Kotak Kosong
            </th>

            @foreach ($paslon as $calon)
                <th wire:key="{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isCalonColumnIgnored ? 'hidden' : '' }} bg-blue-950" style="min-width: 100px;">
                    {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                </th>
            @endforeach

            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Sah
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Tidak Sah
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Masuk
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Abstain
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Partisipasi
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

                {{-- Checkbox --}}
                <td class="py-3 px-4 border centang" data-id="{{ $datum->id }}">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 cursor-pointer">
                </td>

                {{-- Kecamatan --}}
                <td class="py-3 px-4 text-xs border kecamatan {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}" data-kecamatan-id="{{ $datum->tps?->kelurahan?->kecamatan?->id ?? '-' }}">
                    {{ $datum->tps?->kelurahan?->kecamatan?->nama ?? '-' }}
                </td>

                {{-- Kelurahan --}}
                <td class="py-3 px-4 text-xs border kelurahan {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}" data-kelurahan-id="{{ $datum->tps?->kelurahan?->id ?? '-' }}">
                    {{ $datum->tps?->kelurahan?->nama ?? '-' }}
                </td>

                {{-- Nama TPS --}}
                <td class="py-3 px-4 border text-xs tps {{ $isTPSColumnIgnored ? 'hidden' : '' }}">{{ $datum->nama }}</td>

                {{-- DPT --}}
                <td class="py-3 px-4 text-xs border dpt" data-value="{{ $datum->dpt }}">
                    <span class="value">{{ $datum->dpt }}</span>
                </td>

                {{-- Kotak Kosong --}}
                <td class="py-3 px-4 text-xs border kotak-kosong {{ $isCalonColumnIgnored ? 'hidden' : '' }}" data-value="{{ $datum->kotak_kosong }}" {{ !$isPilkadaTunggal ? 'hidden' : '' }}>
                    <span class="value">{{ $datum->kotak_kosong }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-16 focus:outline-none hidden" value="{{ $datum->kotak_kosong }}" data-default-value="{{ $datum->kotak_kosong }}" autocomplete="off">
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
					@if ($datum->partisipasi <= 100 && $datum->partisipasi >= 80)
						<span class="bg-green-400 block text-white py-1 px-7 rounded text-xs">
							{{ $datum->partisipasi }}%
						</span>
					@endif

					@if ($datum->partisipasi < 80 && $datum->partisipasi >= 60)
						<span class="bg-yellow-400 block text-white py-1 px-7 rounded text-xs">
							{{ $datum->partisipasi }}%
						</span>
					@endif

					@if ($datum->partisipasi < 60)
						<span class="bg-red-400 block text-white py-1 px-7 rounded text-xs">
							{{ $datum->partisipasi }}%
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