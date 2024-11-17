<!-- Edit Kecamatan Modal -->
<div id="editKecamatanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editKecamatanForm" action="#" method="POST">
            @method('PUT')
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Edit Kecamatan</h3>

			{{-- Nama kecamatan --}}
            <div class="mb-3">
                <label for="editKecamatanName" class="mb-1 block">Nama</label>
                <input type="text" id="editKecamatanName" name="name"
                    class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nama kecamatan" required>
            </div>

			{{-- Kabupaten --}}
            <div class="mb-3">
                <label for="editKecamatanKabupatenId" class="my-1 block">Kabupaten/Kota</label>
                <select id="editKecamatanKabupatenId" name="kabupaten_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @foreach ($kabupaten as $kab)
                        <option value="{{ $kab->id }}">{{ $kab->nama }}</option>
                    @endforeach
                </select>
            </div>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="closeEditKecamatan" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Simpan
				</button>
            </div>
        </form>
    </div>
</div>

<script>
    function getId() {
        return this.closest('tr').dataset.id;
    }

    function getName() {
        return this.closest('tr').dataset.nama;
    }

    function getKabupatenId() {
        return this.closest('tr').dataset.kabupatenId;
    }

    function showEditKecamatanModal() {
		const editKecamatanModal = document.getElementById('editKecamatanModal');
		editKecamatanModal.classList.remove('hidden');
	}

	function closeEditKecamatanModal() {
		const editKecamatanModal = document.getElementById('editKecamatanModal');
		editKecamatanModal.classList.add('hidden');
	}

    function getUpdateUrl() {
        const kecamatanId = getId.call(this);
        const kecamatanUpdateRoute = `{{ route('kecamatan.update', ['kecamatan' => '__kecamatan__']) }}`;
        const kecamatanUpdateUrl = kecamatanUpdateRoute.replace('__kecamatan__', kecamatanId);

        return kecamatanUpdateUrl;
    }

    function onEditButtonClick() {
        const editKecamatanName = document.getElementById('editKecamatanName');
        editKecamatanName.value = getName.call(this);

        const editKecamatanKabupatenId = document.getElementById('editKecamatanKabupatenId');
        editKecamatanKabupatenId.value = getKabupatenId.call(this);

        const editKecamatanForm = document.getElementById('editKecamatanForm');
        editKecamatanForm.action = getUpdateUrl.call(this);

        showEditKecamatanModal();
    }

    document.querySelectorAll('.edit-kecamatan')
        .forEach(button => button.addEventListener('click', onEditButtonClick));

    document.getElementById('closeEditKecamatan').addEventListener('click', closeEditKecamatanModal);
</script>