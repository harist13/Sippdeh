<div id="filterPilwaliModal" class="bg-gray-600 bg-opacity-50 fixed inset-0 hidden z-50">
    <div class="bg-white border max-h-[80%] my-24 w-96 shadow-lg rounded-md mx-auto px-5 py-5 overflow-y-scroll">
        <div class="flex items-center mb-5">
            <i class="fas fa-arrow-left mr-3 select-none cursor-pointer" id="cancelFilterPilwali"></i>
            <h3 class="text-lg font-medium text-gray-900">Filter Suara Pemilihan Walikota</h3>
        </div>
    
        @livewire(
            'admin.resume.pilwali.filter-resume-suara-pilwali',
            compact(
                'selectedKecamatan',
                'selectedKelurahan',
                'includedColumns',
                'partisipasi'
            )
        )
    </div>
</div>
