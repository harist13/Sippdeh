<!-- Edit Kelurahan Modal -->
<div id="editKelurahanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editKelurahanForm" action="#" method="POST">
            @method('PUT')
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Edit Kelurahan</h3>

			{{-- Nama kelurahan --}}
            <div class="mb-3">
                <label for="editKelurahanName" class="mb-1 block">Nama</label>
                <input type="text" id="editKelurahanName" name="name"
                    class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nama kelurahan" required>
            </div>

			{{-- Kecamatan --}}
            <div class="mb-3">
                <label for="editKelurahanKecamatanId" class="my-1 block">Kecamatan</label>
                <select id="editKelurahanKecamatanId" name="kecamatan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @foreach ($kecamatan as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                </select>
            </div>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="closeEditKelurahan" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
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

    function getKecamatanId() {
        return this.closest('tr').dataset.kecamatanId;
    }

    function showEditKelurahanModal() {
		const editKelurahanModal = document.getElementById('editKelurahanModal');
		editKelurahanModal.classList.remove('hidden');
	}

	function closeEditKelurahanModal() {
		const editKelurahanModal = document.getElementById('editKelurahanModal');
		editKelurahanModal.classList.add('hidden');
	}

    function getUpdateKelurahanUrl() {
        const kelurahanId = getId.call(this);
        const kelurahanUpdateRoute = `{{ route('kelurahan.update', ['kelurahan' => '__kelurahan__']) }}`;
        const kelurahanUpdateUrl = kelurahanUpdateRoute.replace('__kelurahan__', kelurahanId);

        return kelurahanUpdateUrl;
    }

    function onEditKelurahanButtonClick() {
        const editKelurahanName = document.getElementById('editKelurahanName');
        editKelurahanName.value = getName.call(this);
    
        const editKelurahanKecamatanId = document.getElementById('editKelurahanKecamatanId');
        editKelurahanKecamatanId.value = getKecamatanId.call(this);
    
        const editKelurahanForm = document.getElementById('editKelurahanForm');
        editKelurahanForm.action = getUpdateKelurahanUrl.call(this);

        showEditKelurahanModal();
    }

    document.querySelectorAll('.edit-kelurahan')
        .forEach(button => button.addEventListener('click', onEditKelurahanButtonClick));

    document.getElementById('closeEditKelurahan').addEventListener('click', closeEditKelurahanModal);
</script>