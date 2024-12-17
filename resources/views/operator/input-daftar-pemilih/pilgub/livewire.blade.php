@php
    // $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    // $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    // $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    // $isTPSColumnIgnored = !in_array('TPS', $includedColumns);

    // $isCalonColumnIgnored = !in_array('CALON', $includedColumns);
    $isPilkadaTunggal = count($paslon) == 1;
@endphp

@php
    $totalDptb = $kecamatan->sum(fn ($datum) => $datum->dptb ?? 0);
    $totalDpk = $kecamatan->sum(fn ($datum) => $datum->dpk ?? 0);
    $totalSuaraSah = $kecamatan->sum(fn ($datum) => $datum->suara_sah ?? 0);
    $totalSuaraTidakSah = $kecamatan->sum(fn ($datum) => $datum->suara_tidak_sah ?? 0);
    $totalSuaraMasuk = $kecamatan->sum(fn ($datum) => $datum->suara_masuk ?? 0);
    $totalAbstain = $kecamatan->sum(fn ($datum) => $datum->abstain ?? 0);
	
	try {
        $totalPartisipasi = ($totalSuaraMasuk / $totalDpt) * 100;
    } catch (DivisionByZeroError $error) {
        $totalPartisipasi = 0;
    }

    $totalsPerCalon = [];
    foreach ($paslon as $calon) {
        $totalsPerCalon[$calon->id] = $kecamatan->sum(fn($datum) => $datum->getCalonSuaraByCalonId($calon->id)?->total_suara ?? 0);
    }

    $totalKotakKosong = $kecamatan->sum(fn ($datum) => $datum->kotak_kosong ?? 0);
@endphp

<div>
    <div class="bg-white sticky top-20 z-20 rounded-t-[20px] shadow-lg">
        {{-- Action Buttons Section --}}
        <div class="p-4">
            <div class="container mx-auto">
                {{-- Actionable --}}
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
					@InputSuaraEnabled
						@include('operator.input-daftar-pemilih.pilgub.action-buttons')
					@endInputSuaraEnabled
                    
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
					<div class="overflow-x-auto mt-4">
						<table class="min-w-full divide-y divide-gray-200">
							<thead class="bg-[#3560A0] text-white">
								<tr>
									@InputSuaraEnabled
										<th rowspan="2" class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="width: 30px;">
											<input type="checkbox" id="checkAll" class="form-checkbox h-5 w-5 text-white border-white select-none rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
										</th>
									@endInputSuaraEnabled
									
									<th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 300px">
										Kecamatan
									</th>
									
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 100px">
										DPTb
									</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="width: 100px">
										DPK
									</th>

									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950" style="min-width: 100px;" {{ !$isPilkadaTunggal ? 'hidden' : '' }}>
										Kotak Kosong
									</th>
						
									@foreach ($paslon as $calon)
										<th wire:key="{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950" style="min-width: 100px;">
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
								<tr>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-dptb">
										{{ number_format($totalDptb, 0, '.', '.') }}
									</th>
						
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-dpk">
										{{ number_format($totalDpk, 0, '.', '.') }}
									</th>

									{{-- Kotak Kosong --}}
									@if ($isPilkadaTunggal)
										<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-kotak-kosong bg-blue-950">
											{{ number_format($totalKotakKosong, 0, '.', '.') }}
										</th>
									@endif
						
									@foreach ($paslon as $calon)
										<th wire:key="total-{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950 total-calon">
											{{ number_format($totalsPerCalon[$calon->id], 0, '.', '.') }}
										</th>
									@endforeach
								
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-suara-sah">
										{{ number_format($totalSuaraSah, 0, '.', '.') }}
									</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-suara-tidak-sah">
										{{ number_format($totalSuaraTidakSah, 0, '.', '.') }}
									</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-suara-masuk">
										{{ number_format($totalSuaraMasuk, 0, '.', '.') }}
									</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-abstain">
										{{ number_format($totalAbstain, 0, '.', '.') }}
									</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none rata-rata-paritisipasi">
										{{ number_format($totalPartisipasi, 1, '.', '.') }}%
									</th>
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