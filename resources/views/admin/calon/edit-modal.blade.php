<div id="editCalonModal" class="bg-gray-600 bg-opacity-50 fixed inset-0 overflow-y-scroll hidden">
    <div class="bg-white border relative top-24 h-5/6 w-96 shadow-lg rounded-md mx-auto px-5 py-5 overflow-y-scroll">
        <form id="editCalonForm" action="#" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Edit Calon</h3>

			{{-- Nama calon --}}
			<label for="editCalonName" class="mb-2 block">Nama Calon</label>
            <input
                type="text"
                id="editCalonName"
                name="nama_calon"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama 1 (Ketua)"
                required
            >
            <span class="text-red-800">{{ $errors->first('nama_calon') }}</span>
            
            <input
                type="text"
                id="editCalonWakilName"
                name="nama_calon_wakil"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama 2 (Wakil)"
                required
            >
            <span class="text-red-800">{{ $errors->first('nama_calon_wakil') }}</span>

			{{-- Mencalon Sebagai --}}
			<label for="editCalonAs" class="my-3 mb-1 block">Mencalon Sebagai</label>
			<select
                id="editCalonAs"
                name="posisi"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
            >
                <option value="" selected disabled>Pilih</option>
                <option value="GUBERNUR">Gubernur/Wakil Gubernur</option>
                <option value="WALIKOTA">Walikota/Wakil Walikota</option>
                <option value="BUPATI">Bupati/Wakil Bupati</option>
			</select>
			<span class="text-red-800">{{ $errors->first('posisi') }}</span>

            {{-- Provinsi --}}
			<label for="editCalonProvinsi" class="my-3 mb-1 block">Provinsi</label>
			<select
                id="editCalonProvinsi"
                name="provinsi_id_calon"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:bg-gray-300"
                disabled
            >
                <option value="" selected disabled>Pilih</option>
				@foreach ($provinsi as $prov)
					<option value="{{ $prov->id }}">{{ $prov->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('provinsi_id_calon') }}</span>

            {{-- Kabupaten --}}
			<label for="editCalonKabupaten" class="my-3 mb-1 block">Kabupaten/Kota</label>
			<select
                id="editCalonKabupaten"
                name="kabupaten_id_calon"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:bg-gray-300"
                disabled
            >
                <option value="" selected disabled>Pilih</option>
				@foreach ($kabupaten as $kab)
					<option value="{{ $kab->id }}">{{ $kab->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kabupaten_id_calon') }}</span>

            {{-- Foto --}}
            <label for="editCalonPhoto" class="my-3 mb-1 block">Foto</label>
            <input
                type="file"
                id="editCalonPhoto"
                name="foto_calon"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Foto calon"
            >
			<span class="text-red-800">{{ $errors->first('foto_calon') }}</span>

            <p class="text-xs text-gray-500 my-3">
                Catatan: Pastikan gambar pasangan calon yang diunggah memiliki ukuran dimensi 300x200.
            </p>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelEditCalon" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmEditCalon" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Edit
				</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showEditCalonModal() {
        const editCalonModal = document.getElementById('editCalonModal');
        editCalonModal.classList.remove('hidden');
    }

    function closeEditCalonModal() {
        const editCalonModal = document.getElementById('editCalonModal');
        editCalonModal.classList.add('hidden');
    }

    function getCalonId() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.id;
    }

    function getNamaCalon() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.nama;
    }

    function getNamaCalonWakil() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.namaWakil;
    }

    function getPosisiCalon() {
        return this.closest('tr').querySelector('td:nth-child(3)').dataset.posisi;
    }

    function getProvinsiId() {
        return this.closest('tr').querySelector('td:nth-child(4)').dataset.provinsiId;
    }

    function getKabupatenId() {
        return this.closest('tr').querySelector('td:nth-child(4)').dataset.kabupatenId;
    }

    function getUpdateCalonUrl() {
        const calonId = getCalonId.call(this);
        const calonUpdateRoute = `{{ route('calon.update', ['calon' => '__id__']) }}`;
        const calonUpdateUrl = calonUpdateRoute.replace('__id__', calonId);

        return calonUpdateUrl;
    }

    function enableProvinsiSelector() {
        const provinsiSelector = document.getElementById('editCalonProvinsi');
        if (provinsiSelector) provinsiSelector.disabled = false;
    }

    function disableProvinsiSelector() {
        const provinsiSelector = document.getElementById('editCalonProvinsi');
        if (provinsiSelector) provinsiSelector.disabled = true;
    }

    function enableKabupatenSelector() {
        const kabupatenSelector = document.getElementById('editCalonKabupaten');
        if (kabupatenSelector) kabupatenSelector.disabled = false;
    }

    function disableKabupatenSelector() {
        const kabupatenSelector = document.getElementById('editCalonKabupaten');
        if (kabupatenSelector) kabupatenSelector.disabled = true;
    }

    function handlePosisiCalon(event) {
        const posisi = event.target.value;

        if (posisi === 'GUBERNUR') {
            enableProvinsiSelector();
            disableKabupatenSelector();
        }
        
        if (posisi === 'WALIKOTA' || posisi === 'BUPATI') {
            disableProvinsiSelector();
            enableKabupatenSelector();
        }
    }
    
    document.addEventListener('DOMContentLoaded', function () {
        disableProvinsiSelector();
        disableKabupatenSelector();

        document.getElementById('editCalonAs').addEventListener('change', handlePosisiCalon);

        document.querySelectorAll('.edit-calon-btn')
            .forEach(btn => btn.addEventListener('click', function() {
                showEditCalonModal();

                const editCalonName = document.getElementById('editCalonName');
                editCalonName.value = getNamaCalon.call(this);

                const editCalonWakilName = document.getElementById('editCalonWakilName');
                editCalonWakilName.value = getNamaCalonWakil.call(this);

                const editCalonAs = document.getElementById('editCalonAs');
                editCalonAs.value = getPosisiCalon.call(this);
                editCalonAs.dispatchEvent(new Event('change'));

                const editCalonProvinsi = document.getElementById('editCalonProvinsi');
                editCalonProvinsi.value = getProvinsiId.call(this);

                const editCalonKabupaten = document.getElementById('editCalonKabupaten');
                editCalonKabupaten.value = getKabupatenId.call(this);

                const editCalonForm = document.getElementById('editCalonForm');
                editCalonForm.action = getUpdateCalonUrl.call(this);
            }));
        
        document.getElementById('cancelEditCalon').addEventListener('click', closeAddCalonModal);
    });
</script>

@error('nama_calon'. 'nama_calon_wakil', 'posisi', 'kabupaten_id_calon', 'provinsi_id_calon', 'foto_calon')
    <script>
        showEditCalonModal();
    </script>
@enderror