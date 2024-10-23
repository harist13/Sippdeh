<!-- Edit Calon Modal -->
<div id="editCalonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editCalonForm" action="#" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Edit Calon</h3>

			{{-- Nama calon --}}
			<label for="editCalonName" class="mt-2 mb-1 block">Nama</label>
            <input
                type="text"
                id="editCalonName"
                name="nama_calon"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama calon"
                required
            >
            <span class="text-red-800">{{ $errors->first('nama_calon') }}</span>

			{{-- Nama calon wakil --}}
			<label for="editCalonWakilName" class="mt-2 mb-1 block">Nama Wakil</label>
            <input
                type="text"
                id="editCalonWakilName"
                name="nama_calon_wakil"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama calon"
                required
            >
            <span class="text-red-800">{{ $errors->first('nama_calon') }}</span>

			{{-- Kabupaten --}}
			<label for="editCalonKabupatenId" class="mt-2 mb-1 block">Kabupaten</label>
			<select
                id="editCalonKabupatenId"
                name="kabupaten_id_calon"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
            >
				@foreach ($kabupaten as $kab)
					<option value="{{ $kab->id }}">{{ $kab->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kabupaten_id_calon') }}</span>

            {{-- Foto --}}
            <label for="editCalonPhoto" class="mt-2 mb-1 block">Foto</label>
            <input
                type="file"
                id="editCalonPhoto"
                name="foto_calon"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Foto calon"
            >
            <span class="text-red-800">{{ $errors->first('foto_calon') }}</span>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelEditCalon" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmEditCalon" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Simpan
				</button>
            </div>
        </form>
    </div>
</div>

<script>
    function getCalonId() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.id;
    }

    function getCalonName() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.nama;
    }

    function getCalonWakilName() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.namaWakil;
    }

    function getCalonKabupatenId() {
        return this.closest('tr').querySelector('td:nth-child(3)').dataset.id;
    }

    function showEditCalonModal() {
		const editCalonModal = document.getElementById('editCalonModal');
		editCalonModal.classList.remove('hidden');
	}

	function closeEditCalonModal() {
		const editCalonModal = document.getElementById('editCalonModal');
		editCalonModal.classList.add('hidden');
	}

    function getUpdateCalonUrl() {
        const calonId = getCalonId.call(this);
        const calonUpdateRoute = `{{ route('calon.update', ['calon' => '__calon__']) }}`;
        const calonUpdateUrl = calonUpdateRoute.replace('__calon__', calonId);

        return calonUpdateUrl;
    }

    document.querySelectorAll('.edit-calon-btn').forEach(button => {
        button.addEventListener('click', function() {
            showEditCalonModal();

            const editCalonName = document.getElementById('editCalonName');
            editCalonName.value = getCalonName.call(this);

            const editCalonWakilName = document.getElementById('editCalonWakilName');
            editCalonWakilName.value = getCalonWakilName.call(this);

            const editCalonKabupatenId = document.getElementById('editCalonKabupatenId');
            editCalonKabupatenId.value = getCalonKabupatenId.call(this);

            const editCalonForm = document.getElementById('editCalonForm');
            editCalonForm.action = getUpdateCalonUrl.call(this);
        });
    });

    document.getElementById('cancelEditCalon').addEventListener('click', closeEditCalonModal);
</script>

@error('nama_calon')
    <script>
        showEditCalonModal();
    </script>
@enderror

@error('provinsi_id_calon')
    <script>
        showEditCalonModal();
    </script>
@enderror

@error('foto_calon')
    <script>
        showEditCalonModal();
    </script>
@enderror