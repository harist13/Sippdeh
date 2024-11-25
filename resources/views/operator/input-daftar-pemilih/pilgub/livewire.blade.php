<div>
    <div class="bg-white sticky top-20 z-20 rounded-t-[20px] shadow-lg">
        {{-- Action Buttons Section --}}
        <div class="p-4">
            <div class="container mx-auto">
                {{-- Actionable --}}
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
                    @include('operator.input-daftar-pemilih.pilgub.action-buttons')
                    
                    {{-- Cari dan Filter --}}
                    @include('operator.input-daftar-pemilih.pilgub.export-search-filter')
                </div>
                
                {{-- Success Message --}}
                @php $status = session('pesan_sukses'); @endphp
                @isset ($status)
                    <div class="mt-3">
                        @include('components.alert-berhasil', ['message' => $status, 'withoutMarginBottom' => true])
                    </div>
                @endisset

                {{-- Failed Message --}}
                @php $status = session('pesan_gagal'); @endphp
                @isset ($status)
                    <div class="mt-3">
                        @include('components.alert-gagal', ['message' => $status])
                    </div>
                @endisset

                {{-- Loading --}}
                @include('operator.input-daftar-pemilih.pilgub.loading-alert')

				{{-- Sticky Reference Header --}}
				<div id="stickyReferenceHeader" class="hidden">
					<div class="overflow-x-auto">
						<table class="min-w-full divide-y divide-gray-200">
							<thead class="bg-[#3560A0] text-white">
								<tr>
									<th rowspan="2" class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
										<input type="checkbox" disabled class="form-checkbox h-5 w-5 text-white border-white select-none rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
									</th>
									
									<th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 100px;">Kecamatan</th>

									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">DPTb</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">DPK</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg relative">
                {{-- Loading Overlay --}}
                <div wire:loading.delay wire:target.except="export" class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

                <div class="px-4">
                    @include('operator.input-daftar-pemilih.pilgub.table', compact('kecamatan'))
                </div>
            </div>
        </div>
    </div>
	
	{{-- Paginasi --}}
    <div class="py-4 px-6">
        {{ $kecamatan->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
    </div>

    {{-- Filter Pilgub Modal --}}
    {{-- @include(
        'operator.input-daftar-pemilih.pilgub.filter-modal',
        compact('selectedKecamatan','selectedKelurahan','includedColumns','partisipasi')
    ) --}}
</div>

@assets
    <script src="{{ asset('scripts/input-daftar-pemilih.js') }}"></script>
@endassets

@script
	<script type="text/javascript">
		const inputDaftarPemilihUI = new InputDaftarPemilihUIManager($wire);
		inputDaftarPemilihUI.initialize();
		inputDaftarPemilihUI.initializeHooks();
	</script>
@endscript

@push('scripts')
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			const mainTable = document.querySelector('.input-suara-table');
			const mainTableHeader = mainTable.querySelector('thead');
			const stickyHeader = document.getElementById('stickyReferenceHeader');
			
			function syncColumnWidths() {
				const mainHeaders = mainTable.querySelectorAll('thead th');
				const stickyHeaders = stickyHeader.querySelectorAll('thead th');

				// Sync widths for each column
				mainHeaders.forEach((header, index) => {
					if (stickyHeaders[index]) {
						const width = header.getBoundingClientRect().width;
						stickyHeaders[index].style.width = `${width}px`;
						stickyHeaders[index].style.minWidth = header.style.minWidth;
					}
				});

				// Ensure table widths match
				const mainTableWidth = mainTable.getBoundingClientRect().width;
				stickyHeader.querySelector('table').style.width = `${mainTableWidth}px`;
			}

			let lastKnownScrollPosition = 0;
			let ticking = false;

			function updateStickyHeader() {
				const mainHeaderRect = mainTableHeader.getBoundingClientRect();
				const triggerPosition = 80; // Adjust based on your needs

				if (mainHeaderRect.top < triggerPosition) {
					if (!stickyHeader.classList.contains('visible')) {
						stickyHeader.classList.remove('hidden');
						stickyHeader.classList.add('visible');
						syncColumnWidths(); // Sync widths when showing
					}
				} else {
					stickyHeader.classList.remove('visible');
					stickyHeader.classList.add('hidden');
				}
			}

			// Initial sync
			syncColumnWidths();

			// Sync on window resize
			window.addEventListener('resize', () => {
				if (stickyHeader.classList.contains('visible')) {
					syncColumnWidths();
				}
			});

			window.addEventListener('scroll', function() {
				lastKnownScrollPosition = window.scrollY;

				if (!ticking) {
					window.requestAnimationFrame(function() {
						updateStickyHeader();
						ticking = false;
					});
					ticking = true;
				}
			});

			// Handle horizontal scroll sync
			const mainTableContainer = mainTable.closest('.overflow-x-auto');
			const stickyHeaderContainer = stickyHeader.querySelector('.overflow-x-auto');

			if (mainTableContainer && stickyHeaderContainer) {
				mainTableContainer.addEventListener('scroll', function() {
					stickyHeaderContainer.scrollLeft = this.scrollLeft;
				});

				stickyHeaderContainer.addEventListener('scroll', function() {
					mainTableContainer.scrollLeft = this.scrollLeft;
				});
			}

			// Make sure widths are synced after any dynamic content changes
			new MutationObserver(() => {
				if (stickyHeader.classList.contains('visible')) {
					syncColumnWidths();
				}
			}).observe(mainTable, { 
				childList: true, 
				subtree: true, 
				attributes: true 
			});
		});
	</script>
@endpush