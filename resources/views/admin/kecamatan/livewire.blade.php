<div>
	<div class="container mx-auto p-6 bg-white rounded-lg shadow-md mb-5">
		<div class="flex items-center space-x-2 w-full-mobile mb-5">
			<img src="{{ asset('assets/icon/kecamatan.svg') }}" class="mr-1" alt="Kecamatan">
			<span class="font-bold">Kecamatan</span>
		</div>

		<div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile gap-y-5">
			<div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
				{{-- Search Input --}}
                <div class="flex items-center rounded-lg border bg-[#ECEFF5] px-4 py-2">
                    {{-- Loading Icon --}}
                    <svg wire:loading wire:target="keyword" class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#3560A0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
    
                    {{-- Search Icon --}}
                    <svg wire:loading.remove wire:target="keyword" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                    </svg>
    
                    {{-- Input --}}
                    <input 
                        wire:model.live.debounce.500ms="keyword"
                        type="search" 
                        placeholder="Cari"
                        name="cari" 
                        id="search"
                        class="ml-2 w-[300px] bg-transparent focus:outline-none text-gray-600"
                    >
                </div>
			</div>

			<div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
				{{-- <div class="flex gap-2">
					<button id="importKecamatanBtn" class="bg-[#58DA91] text-white py-2 px-4 rounded-lg w-full-mobile">
						<i class="fas fa-file-import me-1"></i>
						<span>Impor</span>
					</button>
					<button id="exportKecamatanBtn" class="bg-[#EE3C46] text-white py-2 px-4 rounded-lg w-full-mobile">
						<i class="fas fa-file-export me-1"></i>
						<span>Ekspor</span>
					</button>
				</div> --}}
				
				<button id="addKecamatanBtn" class="bg-[#0070FF] text-white py-2 px-4 rounded-lg w-full-mobile">
					<i class="fas fa-plus me-1"></i>
					<span>Tambah Kecamatan</span>
				</button>
			</div>
		</div>

		<div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto relative mb-5">
			<!-- Loading Overlay -->
            <div wire:loading.delay class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

			<table class="min-w-full leading-normal text-sm-mobile">
				<thead>
					<tr class="bg-[#3560A0] text-white">
						<th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">ID</th>
						<th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">Nama</th>
						<th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">Kabupaten/Kota</th>
						<th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">Aksi</th>
					</tr>
				</thead>
				<tbody class="bg-gray-100">
					@forelse ($kecamatan as $kec)
						<tr class="hover:bg-gray-200" data-id="{{ $kec->id }}" data-nama="{{ $kec->nama }}" data-kabupaten-id="{{ $kec->kabupaten->id }}">
							<td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">{{ $kec->getThreeDigitsId() }}</td>
							<td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">{{ $kec->nama }}</td>
							<td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">{{ $kec->kabupaten->nama }}</td>
							<td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">
								<button class="text-[#3560A0] hover:text-blue-900 edit-kecamatan"><i class="fas fa-edit"></i></button>
								<button class="text-red-600 hover:text-red-900 ml-3 hapus-kecamatan"><i class="fas fa-trash-alt"></i></button>
							</td>
						</tr>
					@empty
						<tr class="hover:bg-gray-200 text-center">
							<td class="py-5" colspan="4">
								@if (request()->has('cari'))
									<p>Tidak ada data kecamatan dengan kata kunci "{{ request()->get('cari') }}"</p>
								@else
									<p>Belum ada data kecamatan</p>
								@endif
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		{{-- Pagination --}}
		<div class="mt-4">
			{{ $kecamatan->links('vendor.livewire.simple') }}
		</div>
	</div>

	@include('admin.kecamatan.tambah-modal')
	@include('admin.kecamatan.edit-modal')
	@include('admin.kecamatan.hapus-modal')
	{{-- @include('admin.kecamatan.ekspor-modal') --}}
	{{-- @include('admin.kecamatan.impor-modal') --}}
</div>

@script
	<script>
		// Tutup modal saat tombol esc di tekan
		document.addEventListener('keyup', function(event) {
			if (event.key === "Escape") {
				closeAddKecamatanModal();
				closeEditKecamatanModal();
				closeDeleteKecamatanModal();
				// closeExportKecamatanModal();
				// closeImportKecamatanModal();
			}
		});

		document.addEventListener('click', function(event) {
			if (event.target == addKecamatanModal) {
				closeAddKecamatanModal();
			}

			if (event.target == editKecamatanModal) {
				closeEditKecamatanModal();
			}

			if (event.target == deleteKecamatanModal) {
				closeDeleteKecamatanModal();
			}

			// if (event.target == exportKecamatanModal) {
			// 	closeExportKecamatanModal();
			// }

			// if (event.target == importKecamatanModal) {
			// 	closeImportKecamatanModal();
			// }
		});
	</script>
@endscript