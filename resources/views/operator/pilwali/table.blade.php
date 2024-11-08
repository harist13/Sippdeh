@php
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    $isTPSColumnIgnored = !in_array('TPS', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);
@endphp

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
            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
                DPT
            </th>

            @foreach ($paslon as $calon)
                <th wire:key="{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isCalonColumnIgnored ? 'hidden' : '' }} bg-blue-950" style="min-width: 100px;">
                    {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                </th>
            @endforeach

            <th class="py-4 px-2 text-center font-semibold text-sm border border-white select-none {{ $isCalonColumnIgnored ? 'hidden' : '' }}" style="min-width: 200px;">
                Calon
            </th>

            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Sah
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Tidak Sah
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Abstain
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Suara Masuk
            </th>
            <th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">
                Partisipasi
            </th>
        </tr>
    </thead>
    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
        @forelse ($tps as $tpsDatum)
            <tr wire:key="{{ $tpsDatum->id }}" class="border-b text-center select-none cursor-pointer hover:bg-gray-200 tps" data-id="{{ $tpsDatum->id }}">
                <td class="py-3 px-4 text-xs border nomor" data-id="{{ $tpsDatum->id }}">{{ $tpsDatum->getThreeDigitsId() }}</td>
                <td class="py-3 px-4 text-xs border centang" data-id="{{ $tpsDatum->id }}">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 cursor-pointer">
                </td>

                <td class="py-3 px-4 text-xs border kecamatan {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}" data-kecamatan-id="{{ $tpsDatum->tps->kelurahan->kecamatan->id }}">
                    {{ $tpsDatum->tps->kelurahan->kecamatan->nama }}
                </td>
                <td class="py-3 px-4 text-xs border kelurahan {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}" data-kelurahan-id="{{ $tpsDatum->tps->kelurahan->id }}">
                    {{ $tpsDatum->tps->kelurahan->nama }}
                </td>
                <td class="py-3 px-4 text-xs border tps {{ $isTPSColumnIgnored ? 'hidden' : '' }}">{{ $tpsDatum->nama }}</td>
                <td class="py-3 px-4 text-xs border dpt" data-value="{{ $tpsDatum->dpt }}">
                    <span class="value">{{ $tpsDatum->dpt }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-xs text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-1 w-20 focus:outline-none hidden" data-default-value="{{ $tpsDatum->dpt }}" data-value="{{ $tpsDatum->dpt }}">
                </td>

                @foreach ($paslon as $calon)
                    @php
                        $suaraCalon = $tpsDatum->suaraCalonByCalonId($calon->id)->first();
                        $suara = $suaraCalon != null ? $suaraCalon->suara : 0;
                    @endphp
                    <td wire:key="{{ $tpsDatum->id }}{{ $calon->id }}" class="py-3 px-4 text-xs border paslon {{ $isCalonColumnIgnored ? 'hidden' : '' }}" data-id="{{ $calon->id }}" data-suara="{{ $suara }}">
                        <span class="value">{{ $suara }}</span>
                        <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-xs text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-1 w-20 focus:outline-none hidden" value="{{ $suara }}" data-default-value="{{ $suara }}" autocomplete="off">
                    </td>
                @endforeach

                <td class="py-3 px-4 text-xs border posisi {{ $isCalonColumnIgnored ? 'hidden' : '' }}">Gubernur/<br>Wakil Gubernur</td>
                <td class="py-3 px-4 text-xs border suara-sah" data-value="{{ $tpsDatum->suara_sah }}">{{ $tpsDatum->suara_sah }}</td>
                <td class="py-3 px-4 text-xs border suara-tidak-sah" data-value="{{ $tpsDatum->suara_tidak_sah }}">
                    <span class="value">{{ $tpsDatum->suara_tidak_sah }}</span>
                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-xs text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-1 w-20 focus:outline-none hidden" data-default-value="{{ $tpsDatum->suara_tidak_sah }}" data-value="{{ $tpsDatum->suara_tidak_sah }}">
                </td>
                <td class="py-3 px-4 text-xs border abstain" data-value="{{ $tpsDatum->abstain }}">{{ $tpsDatum->abstain }}</td>
                <td class="py-3 px-4 text-xs border suara-masuk" data-value="{{ $tpsDatum->suara_masuk }}">{{ $tpsDatum->suara_masuk }}</td>
                <td class="py-3 px-4 text-xs border partisipasi {{ strtolower($tpsDatum->partisipasi) }}">
					@if ($tpsDatum->partisipasi <= 100 && $tpsDatum->partisipasi >= 80)
						<span class="bg-green-400 block text-white py-1 px-7 rounded text-xs">
							{{ $tpsDatum->partisipasi }}%
						</span>
					@endif

					@if ($tpsDatum->partisipasi < 80 && $tpsDatum->partisipasi >= 60)
						<span class="bg-yellow-400 block text-white py-1 px-7 rounded text-xs">
							{{ $tpsDatum->partisipasi }}%
						</span>
					@endif

					@if ($tpsDatum->partisipasi < 60)
						<span class="bg-red-400 block text-white py-1 px-7 rounded text-xs">
							{{ $tpsDatum->partisipasi }}%
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