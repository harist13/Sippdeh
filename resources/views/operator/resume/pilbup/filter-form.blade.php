<div>
	<!-- Kecamatan Multiple Select -->
	<div class="relative mb-5" x-data="{ open: false }" @click.away="open = false">
			<label class="block text-sm font-semibold mb-3">Kecamatan</label>
			<div class="relative">
					<button @click="open = !open"
									type="button"
									class="w-full h-10 px-3 text-sm border border-[#e0e0e0] rounded-lg focus:outline-none focus:border-[#3560a0] bg-white flex items-center justify-between">
							@if (count($selectedKecamatan) > 0)
									<span>{{ count($selectedKecamatan) }} dipilih</span>
							@else
									<span>Pilih Kecamatan</span>
							@endif
							<svg class="w-4 h-4" :class="{'transform rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
							</svg>
					</button>
					<div x-show="open" 
							 class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg">
							<!-- Dropdown list -->
							<div class="max-h-60 overflow-y-auto">
									@foreach($kecamatan as $kec)
									<label class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
											<input type="checkbox" 
														 wire:model.live="selectedKecamatan" 
														 value="{{ $kec['id'] }}"
														 class="rounded border-gray-300 text-[#3560a0] focus:ring-[#3560a0]">
											<span class="ml-2">{{ $kec['name'] }}</span>
									</label>
									@endforeach
							</div>
					</div>
			</div>
	</div>

	<!-- Kelurahan Multiple Select -->
	<div class="relative mb-5" x-data="{ open: false }" @click.away="open = false">
			<label class="block text-sm font-semibold mb-3">Kelurahan</label>
			<div class="relative">
					<button @click="open = !open" 
									type="button"
									class="w-full h-10 px-3 text-sm border border-[#e0e0e0] rounded-lg focus:outline-none focus:border-[#3560a0] bg-white flex items-center justify-between"
									:class="{ 'opacity-50 cursor-not-allowed': {{ count($selectedKecamatan) === 0 ? 'true' : 'false' }} }"
									:disabled="{{ count($selectedKecamatan) === 0 ? 'true' : 'false' }}">
							@if (count($selectedKelurahan) > 0)
									<span>{{ count($selectedKelurahan) }} dipilih</span>
							@else
									<span>Pilih Kelurahan</span>
							@endif
							<svg class="w-4 h-4" :class="{'transform rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
							</svg>
					</button>
					<div x-show="open && {{ count($selectedKecamatan) > 0 ? 'true' : 'false' }}" 
							 class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg">
							<!-- Dropdown list -->
							<div class="max-h-60 overflow-y-auto">
									@foreach($kelurahan as $kel)
									<label class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
											<input type="checkbox" 
														 wire:model.live="selectedKelurahan" 
														 value="{{ $kel['id'] }}"
														 class="rounded border-gray-300 text-[#3560a0] focus:ring-[#3560a0]">
											<span class="ml-2">{{ $kel['name'] }}</span>
									</label>
									@endforeach
							</div>
					</div>
			</div>
	</div>

	<!-- Show Columns Section -->
	<div class="relative mb-5">
		<label class="block text-sm font-semibold mb-3">Tampilkan Kolom</label>
		<div class="grid grid-cols-2 gap-2">
			@foreach($availableColumns as $column)
				<label wire:key class="flex items-center space-x-2 cursor-pointer">
					<input type="checkbox"
						wire:model.live="includedColumns"
						value="{{ $column }}"
						class="rounded border-gray-300 text-[#3560a0] focus:ring-[#3560a0]">
					<span class="text-sm">{{ $column }}</span>
				</label>
			@endforeach
		</div>
	</div>

	<!-- Partisipasi Section -->
	<div class="relative mb-5">
			<label class="block text-sm font-semibold mb-3">Tingkat Partisipasi</label>
			<div class="flex flex-wrap gap-2">
					@foreach(['HIJAU', 'KUNING', 'MERAH'] as $color)
					<label class="flex items-center cursor-pointer">
							<input type="checkbox" 
									wire:model.live="partisipasi" 
									value="{{ $color }}" 
									class="hidden">
							<span class="px-3 py-1 rounded-full border text-sm font-medium transition-all duration-200"
									:class="{ 
											'bg-[#3560a0]': @js(in_array($color, $partisipasi)),
											'text-[#69d788]': @js(in_array($color, $partisipasi)) && '{{ $color }}' === 'HIJAU',
											'text-[#ffe608]': @js(in_array($color, $partisipasi)) && '{{ $color }}' === 'KUNING',
											'text-[#fe756c]': @js(in_array($color, $partisipasi)) && '{{ $color }}' === 'MERAH',
											'text-gray-600 bg-white': !@js(in_array($color, $partisipasi))
									}">
									{{ ucfirst($color) }}
							</span>
					</label>
					@endforeach
			</div>
	</div>

	<hr class="h-1 my-3">

	<div class="flex gap-4">
			<button type="button" 
							wire:loading.attr="disabled" 
							wire:target="resetFilter" 
							wire:click="resetFilter" 
							class="flex-1 relative bg-gray-300 disabled:bg-[#d1d5d06c] hover:bg-gray-400 text-black rounded-md px-4 py-2 mr-2">
					<div class="flex items-center justify-center">
							<svg wire:loading wire:target="resetFilter" class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#3560A0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
									<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
									<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
							<span>Reset</span>
					</div>
			</button>

			<button type="button" 
							wire:loading.attr="disabled" 
							wire:target="applyFilter" 
							wire:click="applyFilter" 
							class="flex-1 relative bg-[#3560A0] disabled:bg-[#0070F06c] hover:bg-blue-700 text-white rounded-md px-4 py-2">
					<div class="flex items-center justify-center">
							<svg wire:loading wire:target="applyFilter" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
									<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
									<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
							<span>Terapkan</span>
					</div>
			</button>
	</div>
</div>