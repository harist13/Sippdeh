<!-- Add Kecamatan Modal -->
<div id="addKecamatanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('kecamatan.store') }}" method="POST">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Tambah Kecamatan</h3>

			{{-- Nama kecamatan --}}
			<label for="addKecamatanName" class="mb-1 block">Nama</label>
            <input type="text" id="addKecamatanName" name="nama_kecamatan_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama kecamatan" required>
            <span class="text-red-800">{{ $errors->first('nama_kecamatan_baru') }}</span>

			{{-- Kabupaten --}}
			<label for="addKecamatanKabupaten" class="my-1 block">Kabupaten</label>
			<select id="addKecamatanKabupaten" name="kabupaten_id_kecamatan_baru" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
				@foreach ($kabupaten as $kab)
					<option value="{{ $kab->id }}">{{ $kab->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kabupaten_id_kecamatan_baru') }}</span>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelAddKecamatan" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmAddKecamatan" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Tambah
				</button>
            </div>
        </form>
    </div>
</div>

<script>
	function showAddKecamatanModal() {
		const addKecamatanModal = document.getElementById('addKecamatanModal');
		addKecamatanModal.classList.remove('hidden');
	}

	function closeAddKecamatanModal() {
		const addKecamatanModal = document.getElementById('addKecamatanModal');
		addKecamatanModal.classList.add('hidden');
	}

    document.getElementById('addKecamatanBtn').addEventListener('click', showAddKecamatanModal);
    document.getElementById('cancelAddKecamatan').addEventListener('click', closeAddKecamatanModal);
</script>

@error('nama_kecamatan_baru')
    <script>
        showAddKecamatanModal();
    </script>
@enderror

@error('provinsi_id_kecamatan_baru')
    <script>
        showAddKecamatanModal();
    </script>
@enderror