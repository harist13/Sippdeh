<div class="px-6">
    <div class="bg-white rounded-[20px] mb-8">
        <div class="bg-white p-4 rounded-t-[20px]">
            <div class="container mx-auto">
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    <h1 class="font-bold text-xl">Data Suara Pemilihan Gubernur</h1>
                    
                    {{-- Cari dan Filter --}}
                    @include('Tamu.dashboard.resume-suara-pilgub.export-search-filter')
                </div>
            </div>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden border-b border-gray-200 shadow">
                    <div class="relative px-4">
                        {{-- Loading Overlay --}}
                        <div wire:loading.delay wire:target.except="export" class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>
                        
                        @include("Tamu.dashboard.resume-suara-pilgub.wilayah-tables.kecamatan-table", compact('suara', 'paslon'))
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4 px-6">
            {{ $suara->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- Filter Pilgub Modal --}}
    {{-- @include(
        'Tamu.dashboard.resume-suara-pilgub.filter-modal',
        compact(
            'selectedKabupaten',
            'selectedKecamatan',
            'selectedKelurahan',
            'includedColumns',
            'partisipasi'
        )
    ) --}}
</div>
