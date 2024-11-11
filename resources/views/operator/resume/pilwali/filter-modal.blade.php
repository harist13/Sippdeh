<div id="filterPilwaliModal" class="bg-gray-600 bg-opacity-50 fixed inset-0 hidden z-50">
    <div class="bg-white border max-h-[80%] my-24 w-96 shadow-lg rounded-md mx-auto px-5 py-5 overflow-y-scroll">
        <livewire:operator.resume.pilwali.filter-suara-pilwali
			:selected-kecamatan="$selectedKecamatan"
            :selected-kelurahan="$selectedKelurahan"
			:included-columns="$includedColumns"
			:partisipasi="$partisipasi"
		/>
    </div>
</div>
