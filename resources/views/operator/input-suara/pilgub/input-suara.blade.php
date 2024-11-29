@php
    $isKabupatenColumnIgnored = !in_array('KABUPATEN', $includedColumns);
    $isKecamatanColumnIgnored = !in_array('KECAMATAN', $includedColumns);
    $isKelurahanColumnIgnored = !in_array('KELURAHAN', $includedColumns);
    $isTPSColumnIgnored = !in_array('TPS', $includedColumns);
    $isCalonColumnIgnored = !in_array('CALON', $includedColumns);

    $isPilkadaTunggal = count($paslon) == 1;

    $totalDpt = $tps->sum(fn ($datum) => $datum->dpt ?? 0);
    $totalSuaraSah = $tps->sum(fn ($datum) => $datum->suara_sah ?? 0);
    $totalSuaraTidakSah = $tps->sum(fn ($datum) => $datum->suara_tidak_sah ?? 0);
    $totalSuaraMasuk = $tps->sum(fn ($datum) => $datum->suara_masuk ?? 0);
    $totalAbstain = $tps->sum(fn ($datum) => $datum->abstain ?? 0);
	
	try {
        $totalPartisipasi = ($totalSuaraMasuk / $totalDpt) * 100;
    } catch (DivisionByZeroError $error) {
        $totalPartisipasi = 0;
    }

    $totalsPerCalon = [];
    foreach ($paslon as $calon) {
        $totalsPerCalon[$calon->id] = $tps->sum(fn($datum) => $datum->suaraCalonByCalonId($calon->id)?->first()?->suara ?? 0);
    }

    $totalKotakKosong = $tps->sum(fn ($datum) => $datum->kotak_kosong ?? 0);
@endphp

@push('styles')
<style>
    #stickyReferenceHeader {
        transition: opacity 0.2s ease-in-out;
		margin-top: 10px;
    }
    
    #stickyReferenceHeader.visible {
        display: block;
        opacity: 1;
    }
    
    #stickyReferenceHeader.hidden {
        display: none;
        opacity: 0;
    }

    /* Ensure exact table width matching */
    #stickyReferenceHeader table {
        table-layout: fixed;
        width: 100%;
    }

    #stickyReferenceHeader table th,
    .input-suara-table th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Remove any margin/padding that might affect alignment */
    #stickyReferenceHeader .overflow-x-auto,
    .input-suara-table-container .overflow-x-auto {
        margin: 0;
        padding: 0;
    }

    /* Ensure containers have same width */
    .table-wrapper {
        width: 100%;
        position: relative;
    }
</style>
@endpush

<div>
    <div class="bg-white sticky top-20 z-20 rounded-t-[20px] shadow-lg">
        {{-- Action Buttons Section --}}
        <div class="p-4">
            <div class="container mx-auto">
                {{-- Actionable --}}
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
                    @include('operator.input-suara.pilgub.action-buttons')
                    
                    {{-- Cari dan Filter --}}
                    @include('operator.input-suara.pilgub.export-search-filter')
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
                @include('operator.input-suara.pilgub.loading-alert')

				{{-- Sticky Reference Header --}}
				<div id="stickyReferenceHeader" class="hidden">
					<div class="overflow-x-auto">
						<table class="min-w-full divide-y divide-gray-200">
							<thead class="bg-[#3560A0] text-white">
								<tr>
									{{-- <th rowspan="2" class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">NO</th> --}}
									<th rowspan="2" class="py-4 px-2 text-center font-semibold text-sm border border-white select-none" style="min-width: 50px;">
										<input type="checkbox" disabled class="form-checkbox h-5 w-5 text-white border-white select-none rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
									</th>
									
									<th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKabupatenColumnIgnored ? 'hidden' : '' }}" style="min-width: 100px;">Kabupaten/Kota</th>
									<th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKecamatanColumnIgnored ? 'hidden' : '' }}" style="min-width: 100px;">Kecamatan</th>
									<th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isKelurahanColumnIgnored ? 'hidden' : '' }}" style="min-width: 100px;">Kelurahan</th>
									<th rowspan="2" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isTPSColumnIgnored ? 'hidden' : '' }}" style="min-width: 100px;">TPS</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">DPT</th>

									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isCalonColumnIgnored ? 'hidden' : '' }} bg-blue-950" style="min-width: 100px;" {{ !$isPilkadaTunggal ? 'hidden' : '' }}>Kotak Kosong</th>
		
									@foreach ($paslon as $calon)
										<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none {{ $isCalonColumnIgnored ? 'hidden' : '' }} bg-blue-950" style="min-width: 100px;">
											{{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
										</th>
									@endforeach

									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">Suara Sah</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">Suara Tidak Sah</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">Suara Masuk</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">Abstain</th>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none" style="min-width: 50px;">Partisipasi</th>
								</tr>
								<tr>
									<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-dpt">
										{{ number_format($totalDpt, 0, '.', '.') }}
									</th>

									{{-- Kotak Kosong --}}
									@if ($isPilkadaTunggal && !$isCalonColumnIgnored)
										<th class="py-4 px-2 text-center font-semibold text-xs border border-white select-none total-kotak-kosong bg-blue-950">
											{{ $totalKotakKosong }}
										</th>
									@endif
								
									{{-- Calon Totals --}}
									@if (!$isCalonColumnIgnored)
										@foreach ($paslon as $calon)
											<th wire:key="total-{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-xs border border-white select-none bg-blue-950 total-calon">
												{{ number_format($totalsPerCalon[$calon->id], 0, '.', '.') }}
											</th>
										@endforeach
									@endif
								
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
                    @include('operator.input-suara.pilgub.table', compact('tps', 'paslon', 'includedColumns'))
                </div>
            </div>
        </div>
    </div>
	
	{{-- Paginasi --}}
    <div class="py-4 px-6">
        {{ $tps->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
    </div>

    {{-- Filter Pilgub Modal --}}
    @include(
        'operator.input-suara.pilgub.filter-modal',
        compact('selectedKecamatan','selectedKelurahan','includedColumns','partisipasi')
    )
</div>

@assets
    <script src="{{ asset('scripts/input-suara.js') }}"></script>
@endassets

@script
	<script type="text/javascript">
		const inputSuaraUI = new InputSuaraUIManager($wire);
		inputSuaraUI.initialize();
		inputSuaraUI.initializeHooks();
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