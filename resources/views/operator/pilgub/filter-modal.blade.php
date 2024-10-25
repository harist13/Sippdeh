<!-- Filter Pilgub Modal -->
<div id="filterPilgubModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('provinsi.export') }}" method="GET">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Filter</h3>

			{{-- Jumlah Data --}}
			<label for="pilihJumlahData" class="mb-3 font-bold block">Jumlah Data</label>
			<ul class="flex gap-2">
				<li class="flex items-center gap-2">
					<button type="button" class="bg-[#ECEFF5] text-[#344054] py-2 px-7 rounded text-sm">10</button>
				</li>
				<li class="flex items-center gap-2">
					<button type="button" class="bg-[#ECEFF5] text-[#344054] py-2 px-7 rounded text-sm">20</button>
				</li>
				<li class="flex items-center gap-2">
					<button type="button" class="bg-[#ECEFF5] text-[#344054] py-2 px-7 rounded text-sm">50</button>
				</li>
				<li class="flex items-center gap-2">
					<button type="button" class="bg-[#ECEFF5] text-[#344054] py-2 px-7 rounded text-sm">100</button>
				</li>
			</ul>
			{{-- <span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span> --}}

			{{-- Kolom --}}
			<label for="pilihKolom" class="mb-3 font-bold mt-5 block">Kolom</label>
			<ul class="flex flex-col gap-2">
				<li class="flex items-center gap-2 w-full">
					<label class="flex items-center gap-3" for="pilihKecamatan">
						<input type="checkbox" id="pilihKecamatan" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
						<span class="cursor-pointer select-none">Kecamatan</span>
					</label>
				</li>
				<li class="flex items-center gap-2 w-full">
					<label class="flex items-center gap-3" for="pilihKelurahan">
						<input type="checkbox" id="pilihKelurahan" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
						<span class="cursor-pointer select-none">Kelurahan</span>
					</label>
				</li>
				<li class="flex items-center gap-2 w-full">
					<label class="flex items-center gap-3" for="pilihTPS">
						<input type="checkbox" id="pilihTPS" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
						<span class="cursor-pointer select-none">TPS</span>
					</label>
				</li>
				<li class="flex items-center gap-2 w-full">
					<label class="flex items-center gap-3" for="pilihCalon">
						<input type="checkbox" id="pilihCalon" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
						<span class="cursor-pointer select-none">Calon</span>
					</label>
				</li>
			</ul>
			{{-- <span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span> --}}

			{{-- Tingkat Partisipasi --}}
			<label for="pilihTingkatPartisipasi" class="mb-3 font-bold mt-5 block">Tingkat Partisipasi</label>
			<ul class="flex gap-2">
				<li class="flex items-center gap-2">
					<button type="button" class="bg-green-400 text-white py-2 px-7 rounded text-sm">Hijau</button>
				</li>
				<li class="flex items-center gap-2">
					<button type="button" class="bg-yellow-400 text-white py-2 px-7 rounded text-sm">Kuning</button>
				</li>
				<li class="flex items-center gap-2">
					<button type="button" class="bg-red-400 text-white py-2 px-7 rounded text-sm">Merah</button>
				</li>
			</ul>
			{{-- <span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span> --}}

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelFilterPilgub" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batal
				</button>
                <button type="submit" id="confirmFilterPilgub" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Simpan
				</button>
            </div>
        </form>
    </div>
</div>

<script>
	function showFilterPilgubModal() {
		const filterPilgubModal = document.getElementById('filterPilgubModal');
		filterPilgubModal.classList.remove('hidden');
	}

	function closeFilterPilgubModal() {
		const filterPilgubModal = document.getElementById('filterPilgubModal');
		filterPilgubModal.classList.add('hidden');
	}

    document.getElementById('openFilterPilgub').addEventListener('click', showFilterPilgubModal);
    document.getElementById('cancelFilterPilgub').addEventListener('click', closeFilterPilgubModal);
</script>

@error('kabupaten_id')
    <script>
        showFilterPilgubModal();
    </script>
@enderror