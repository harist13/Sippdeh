<!-- Edit Kabupaten/Kota Modal -->
<div id="editKabupatenModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editKabupatenForm" action="#" method="POST">
            @method('PUT')
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Edit Kabupaten/Kota</h3>

			{{-- Nama kabupaten --}}
			<label for="editKabupatenName" class="mb-1 block">Nama</label>
            <input type="text" id="editKabupatenName" name="nama_kabupaten"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama kabupaten" required>
            <span class="text-red-800">{{ $errors->first('nama_kabupaten') }}</span>

			{{-- Provinsi --}}
			<label for="editKabupatenProvinsiId" class="my-1 block">Provinsi</label>
			<select id="editKabupatenProvinsiId" name="provinsi_id_kabupaten" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
				@foreach ($provinsi as $prov)
					<option value="{{ $prov->id }}">{{ $prov->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('provinsi_id_kabupaten') }}</span>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelEditKabupaten" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmEditKabupaten" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Simpan
				</button>
            </div>
        </form>
    </div>
</div>

<script>
    function getKabupatenId() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.id;
    }

    function getKabupatenName() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.nama;
    }

    function getKabupatenProvinsiId() {
        return this.closest('tr').querySelector('td:nth-child(3)').dataset.id;
    }

    function showEditKabupatenModal() {
		const editKabupatenModal = document.getElementById('editKabupatenModal');
		editKabupatenModal.classList.remove('hidden');
	}

	function closeEditKabupatenModal() {
		const editKabupatenModal = document.getElementById('editKabupatenModal');
		editKabupatenModal.classList.add('hidden');
	}

    function getUpdateKabupatenUrl() {
        const kabupatenId = getKabupatenId.call(this);
        const kabupatenUpdateRoute = `{{ route('kabupaten.update', ['kabupaten' => '__kabupaten__']) }}`;
        const kabupatenUpdateUrl = kabupatenUpdateRoute.replace('__kabupaten__', kabupatenId);

        return kabupatenUpdateUrl;
    }

    document.querySelectorAll('.editKabupatenBtn').forEach(button => {
        button.addEventListener('click', function() {
            showEditKabupatenModal();

            const editKabupatenName = document.getElementById('editKabupatenName');
            editKabupatenName.value = getKabupatenName.call(this);

            const editKabupatenProvinsiId = document.getElementById('editKabupatenProvinsiId');
            editKabupatenProvinsiId.value = getKabupatenProvinsiId.call(this);

            const editKabupatenForm = document.getElementById('editKabupatenForm');
            editKabupatenForm.action = getUpdateKabupatenUrl.call(this);
        });
    });

    document.getElementById('cancelEditKabupaten').addEventListener('click', closeEditKabupatenModal);
</script>

@error('nama_kabupaten')
    <script>
        showEditKabupatenModal();
    </script>
@enderror

@error('provinsi_id_kabupaten')
    <script>
        showEditKabupatenModal();
    </script>
@enderror