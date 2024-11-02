@php
	$isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
	$isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
	$isTPSColumnIgnored = !in_array('TPS', $includedColumns);
	$isCalonColumnIgnored = !in_array('CALON', $includedColumns);
@endphp

<table class="min-w-full divide-y divide-gray-200">
	<thead class="bg-[#3560A0] text-white">
		<tr>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">NO</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">
				<input type="checkbox" id="checkAll" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
			</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('KECAMATAN', $includedColumns) ? 'hidden' : '' }}" style="min-width: 200px;">Kecamatan</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('KELURAHAN', $includedColumns) ? 'hidden' : '' }}" style="min-width: 200px;">Kelurahan</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('TPS', $includedColumns) ? 'hidden' : '' }}" style="min-width: 200px;">TPS</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 100px;">DPT</th>
			@foreach ($paslon as $calon)
				<th wire:key="{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('CALON', $includedColumns) ? 'hidden' : '' }}" style="min-width: 300px;">
					{{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
				</th>
			@endforeach
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('CALON', $includedColumns) ? 'hidden' : '' }}" style="min-width: 200px;">Calon</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Sah</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Tidak Sah</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Jumlah Pengguna<br>Tidak Pilih</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Masuk</th>
			<th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">Partisipasi</th>
		</tr>
	</thead>
	<tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
		@forelse ($tps as $tpsDatum)
			<tr wire:key="{{ $tpsDatum->id }}" class="border-b text-center tps" data-id="{{ $tpsDatum->id }}">
				{{-- ID TPS --}}
				<td
					class="py-3 px-4 border nomor"
					data-id="{{ $tpsDatum->id }}"
				>
					{{ $tpsDatum->getThreeDigitsId() }}
				</td>

				{{-- Checkbox --}}
				<td
					class="py-3 px-4 border centang"
					data-id="{{ $tpsDatum->id }}"
				>
					<input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
				</td>

				{{-- Kecamatan --}}
				<td
					class="py-3 px-4 border kecamatan {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}"
					data-kecamatan-id="{{ $tpsDatum->tps->kelurahan->kecamatan->id }}"
				>
					{{ $tpsDatum->tps->kelurahan->kecamatan->nama }}
				</td>

				{{-- Kelurahan --}}
				<td
					class="py-3 px-4 border kelurahan {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}"
					data-kelurahan-id="{{ $tpsDatum->tps->kelurahan->id }}"
				>
					{{ $tpsDatum->tps->kelurahan->nama }}
				</td>

				{{-- Nama TPS --}}
				<td class="py-3 px-4 border tps {{ $isTPSColumnIgnored ? 'hidden' : '' }}">{{ $tpsDatum->nama }}</td>

				{{-- DPT --}}
				<td
					class="py-3 px-4 border dpt"
					data-value="{{ $tpsDatum->dpt }}"
				>
					<span class="value">{{ $tpsDatum->dpt }}</span>
					<input
						type="number"
						placeholder="Jumlah"
						class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none hidden"
						data-default-value="{{ $tpsDatum->dpt }}"
						data-value="{{ $tpsDatum->dpt }}"
					>
				</td>

				{{-- Calon-calon --}}
				@foreach ($paslon as $calon)
					@php
						$suaraCalon = $tpsDatum->suaraCalonByCalonId($calon->id)->first();
						$suara = $suaraCalon != null ? $suaraCalon->suara : 0;
					@endphp

					<td
						wire:key="{{ $tpsDatum->id }}{{ $calon->id }}"
						class="py-3 px-4 border paslon {{ $isCalonColumnIgnored ? 'hidden' : '' }}"
						data-id="{{ $calon->id }}"
						data-suara="{{ $suara }}"
					>
						<span class="value">{{ $suara }}</span>
						<input
							type="number"
							placeholder="Jumlah"
							class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none hidden"
							value="{{ $suara }}"
							data-default-value="{{ $suara }}"
							autocomplete="off"
						>
					</td>
				@endforeach

				{{-- Posisi --}}
				<td class="py-3 px-4 border posisi {{ !in_array('CALON', $includedColumns) ? 'hidden' : '' }}">
					Gubernur/<br>Wakil Gubernur
				</td>

				{{-- Suara Sah --}}
				<td
					class="py-3 px-4 border suara-sah"
					data-value="{{ $tpsDatum->suara_sah }}"
				>
					{{ $tpsDatum->suara_sah }}
				</td>

				{{-- Suara Tidak Sah (Editable) --}}
				<td
					class="py-3 px-4 border suara-tidak-sah"
					data-value="{{ $tpsDatum->suara_tidak_sah }}"
				>
					<span class="value">{{ $tpsDatum->suara_tidak_sah }}</span>
					<input
						type="number"
						placeholder="Jumlah"
						class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none hidden"
						data-default-value="{{ $tpsDatum->suara_tidak_sah }}"
						data-value="{{ $tpsDatum->suara_tidak_sah }}"
					>
				</td>

				{{-- Jumlah Pengguna yang Tidak Pilih --}}
				<td
					class="py-3 px-4 border jumlah-pengguna-tidak-pilih"
					data-value="{{ $tpsDatum->jumlah_pengguna_tidak_pilih }}"
				>
					{{ $tpsDatum->jumlah_pengguna_tidak_pilih }}
				</td>

				{{-- Suara Masuk --}}
				<td
					class="py-3 px-4 border suara-masuk"
					data-value="{{ $tpsDatum->suara_masuk }}"
				>
					{{ $tpsDatum->suara_masuk }}
				</td>

				{{-- Partisipasi --}}
				<td
					class="text-center py-3 px-4 border partisipasi"
					data-value="{{ $tpsDatum->partisipasi }}"
				>
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
				<td class="text-center p-6" colspan="100">Belum ada TPS.</td>
			</tr>
		@endforelse
	</tbody>
</table>