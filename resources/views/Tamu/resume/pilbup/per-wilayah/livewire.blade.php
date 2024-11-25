<div>
    <div class="bg-white rounded-[20px] mb-8">
        <div class="bg-white p-4">
            <div class="container mx-auto">
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    <h1 class="font-bold text-xl">Data Suara Pemilihan Bupati</h1>
                    
                    {{-- Cari dan Filter --}}
                    @include('Tamu.resume.pilbup.per-wilayah.export-search-filter')
                </div>
            </div>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg relative">
                    <!-- Loading Overlay -->
                    <div wire:loading.delay wire:target.except="export"
                        class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

                    @if (in_array('TPS', $includedColumns))
                        @include("Tamu.resume.pilbup.per-wilayah.wilayah-tables.tps-table", compact('suara', 'paslon', 'includedColumns'))
                    @else
                        @if (!empty($selectedKelurahan))
                            @include("Tamu.resume.pilbup.per-wilayah.wilayah-tables.kelurahan-table", compact('suara', 'paslon', 'includedColumns'))
                        @elseif (!empty($selectedKecamatan))
                            @include("Tamu.resume.pilbup.per-wilayah.wilayah-tables.kecamatan-table", compact('suara', 'paslon', 'includedColumns'))
                        @elseif (!empty($selectedKabupaten))
                            @include("Tamu.resume.pilbup.per-wilayah.wilayah-tables.kabupaten-table", compact('suara', 'paslon', 'includedColumns'))
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="py-4 px-6">
            {{ $suara->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Filter Pilgub Modal -->
    @include(
        'Tamu.resume.pilbup.per-wilayah.filter-modal',
        compact(
            'selectedKecamatan',
            'selectedKelurahan',
            'includedColumns',
            'partisipasi'
        )
    )
</div>