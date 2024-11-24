<div>
    <div class="bg-white rounded-[20px] mb-8">
        <div class="bg-white p-4">
            <div class="container mx-auto">
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    <h1 class="font-bold text-xl">Data Suara Pemilihan Walikota Per Wilayah</h1>
                    {{-- Cari dan Filter --}}
                    @include('operator.resume.pilwali.per-wilayah.export-search-filter')
                </div>
            </div>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg relative">
                    <!-- Loading Overlay -->
                    <div wire:loading.delay wire:target.except="export" class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

                    @if (!empty($selectedKelurahan))
                        @include("operator.resume.pilwali.per-wilayah.wilayah-tables.kelurahan-table", compact('suara', 'paslon', 'includedColumns'))
                    @else
                        @include("operator.resume.pilwali.per-wilayah.wilayah-tables.kecamatan-table", compact('suara', 'paslon', 'includedColumns'))
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
        'operator.resume.pilwali.per-wilayah.filter-modal',
        compact(
            'selectedKecamatan',
            'selectedKelurahan',
            'includedColumns',
            'partisipasi'
        )
    )
</div>