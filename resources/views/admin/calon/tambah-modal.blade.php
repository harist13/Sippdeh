<!-- Add Calon Modal -->
<div id="addCalonModal" class="bg-gray-600 bg-opacity-50 fixed inset-0 overflow-y-scroll hidden">
    <div class="bg-white border relative top-24 h-5/6 w-96 shadow-lg rounded-md mx-auto px-5 py-5 overflow-y-scroll">
        <form action="{{ route('calon.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Tambah Calon</h3>

			{{-- Nama calon --}}
			<label for="addCalonName" class="mb-3 block">Nama Calon</label>
            <input
                type="text"
                id="addCalonName"
                name="nama_calon_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama 1 (Ketua)"
                required
            >
            <span class="text-red-800">{{ $errors->first('nama_calon_baru') }}</span>
            
            <input
                type="text"
                id="addCalonWakilName"
                name="nama_calon_wakil_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama 2 (Wakil)"
                required
            >
            <span class="text-red-800">{{ $errors->first('nama_calon_wakil_baru') }}</span>

			{{-- Mencalon Sebagai --}}
			<label for="addCalonAs" class="my-3 mb-1 block">Mencalon Sebagai</label>
			<select
                id="addCalonAs"
                name="posisi"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
            >
                <option value="" selected disabled>Pilih</option>
                <option value="GUBERNUR">Gubernur/Wakil Gubernur</option>
                <option value="WALIKOTA">WaliKota/Wakil WaliKota</option>
			</select>
			<span class="text-red-800">{{ $errors->first('posisi') }}</span>

            {{-- Provinsi --}}
			<label for="addCalonProvinsi" class="my-3 mb-1 block">Provinsi</label>
			<select
                id="addCalonProvinsi"
                name="provinsi_id_calon_baru"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
                disabled
            >
				@foreach ($provinsi as $prov)
					<option value="{{ $prov->id }}">{{ $prov->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('provinsi_id_calon_baru') }}</span>

            {{-- Kabupaten --}}
			<label for="addCalonKabupaten" class="my-3 mb-1 block">Kabupaten</label>
			<select
                id="addCalonKabupaten"
                name="kabupaten_id_calon_baru"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
                disabled
            >
				@foreach ($kabupaten as $kab)
					<option value="{{ $kab->id }}">{{ $kab->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kabupaten_id_calon_baru') }}</span>

            {{-- Foto --}}
            <label for="addCalonPhoto" class="my-3 mb-1 block">Foto</label>
            <input
                type="file"
                id="addCalonPhoto"
                name="foto_calon_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Foto calon"
            >
			<span class="text-red-800">{{ $errors->first('foto_calon_baru') }}</span>

            <p class="text-xs text-gray-500 my-3">
                Catatan: Pastikan gambar pasangan calon yang diunggah memiliki ukuran dimensi 300x200.
            </p>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelAddCalon" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmAddCalon" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Tambah
				</button>
            </div>
        </form>
    </div>
</div>

<script>
	function showAddCalonModal() {
		const addCalonModal = document.getElementById('addCalonModal');
		addCalonModal.classList.remove('hidden');
	}

	function closeAddCalonModal() {
		const addCalonModal = document.getElementById('addCalonModal');
		addCalonModal.classList.add('hidden');
	}

    function getProvinsiSelector() {
        const id = 'addCalonProvinsi';
        const provinsiSelector = document.getElementById(id);
        
        if (provinsiSelector == null) {
            throw new Error(`Selector provinsi dengan id '${$id}' tidak ditemukan.`);
        }

        return provinsiSelector;
    }

    function getKabupatenSelector() {
        const id = 'addCalonKabupaten';
        const kabupatenSelector = document.getElementById(id);
        
        if (kabupatenSelector == null) {
            throw new Error(`Selector kabupaten dengan id '${$id}' tidak ditemukan.`);
        }

        return kabupatenSelector;
    }

    function enableProvinsiSelectors() {
        const provinsiSelector = getProvinsiSelector();
        provinsiSelector.disabled = false;
    }

    function disableProvinsiSelectors() {
        const provinsiSelector = getProvinsiSelector();
        provinsiSelector.disabled = true;
    }

    function enableKabupatenSelectors() {
        const kabupatenSelector = getKabupatenSelector();
        kabupatenSelector.disabled = false;
    }

    function disableKabupatenSelectors() {
        const kabupatenSelector = getKabupatenSelector();
        kabupatenSelector.disabled = true;
    }

    function changeCalonPosisi(event) {
        const posisi = event.target.value;
        if (posisi == 'GUBERNUR') {
            enableProvinsiSelectors();
            disableKabupatenSelectors();
        }

        if (posisi == 'WALIKOTA') {
            disableProvinsiSelectors();
            enableKabupatenSelectors();
        }
    }

    document.getElementById('addCalonAs').addEventListener('change', changeCalonPosisi);

    document.getElementById('addCalonBtn').addEventListener('click', showAddCalonModal);
    document.getElementById('cancelAddCalon').addEventListener('click', closeAddCalonModal);
</script>

@error('nama_calon_baru')
    <script>
        showAddCalonModal();
    </script>
@enderror

@error('kabupaten_id_calon_baru')
    <script>
        showAddCalonModal();
    </script>
@enderror

@error('foto_calon_baru')
    <script>
        showAddCalonModal();
    </script>
@enderror