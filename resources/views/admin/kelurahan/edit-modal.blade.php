<!-- Edit Kelurahan Modal -->
<div id="editKelurahanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editKelurahanForm" action="#" method="POST">
            @method('PUT')
            @csrf
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Edit Kelurahan</h3>

			{{-- Nama kelurahan --}}
			<label for="editKelurahanName" class="mb-1 block">Nama</label>
            <input type="text" id="editKelurahanName" name="nama"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama kelurahan" required>
            <span class="text-red-800">{{ $errors->first('nama') }}</span>

			{{-- Kecamatan --}}
			<label for="editKelurahanKecamatanId" class="my-1 block">Kecamatan</label>
			<select id="editKelurahanKecamatanId" name="kecamatan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
				@foreach ($kecamatan as $kec)
					<option value="{{ $kec->id }}">{{ $kec->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kecamatan_id') }}</span>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelEditKelurahan" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmEditKelurahan" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Simpan
				</button>
            </div>
        </form>
    </div>
</div>

<script>
    function getKelurahanId() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.id;
    }

    function getKelurahanName() {
        return this.closest('tr').querySelector('td:nth-child(2)').dataset.nama;
    }

    function getKelurahanKecamatanId() {
        return this.closest('tr').querySelector('td:nth-child(3)').dataset.id;
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
        const kelurahanId = getKelurahanId.call(this);
        const kelurahanUpdateRoute = `{{ route('kelurahan.update', ['kelurahan' => '__kelurahan__']) }}`;
        const kelurahanUpdateUrl = kelurahanUpdateRoute.replace('__kelurahan__', kelurahanId);

        return kelurahanUpdateUrl;
    }

    document.querySelectorAll('.edit-kelurahan-btn').forEach(button => {
        button.addEventListener('click', function() {
            showEditKelurahanModal();

            const editKelurahanName = document.getElementById('editKelurahanName');
            editKelurahanName.value = getKelurahanName.call(this);

            const editKelurahanKecamatanId = document.getElementById('editKelurahanKecamatanId');
            editKelurahanKecamatanId.value = getKelurahanKecamatanId.call(this);

            const editKelurahanForm = document.getElementById('editKelurahanForm');
            editKelurahanForm.action = getUpdateKelurahanUrl.call(this);
        });
    });

    document.getElementById('cancelEditKelurahan').addEventListener('click', closeEditKelurahanModal);
</script>

@php $isThereAnyError = $errors->count() > 0; @endphp
@if ($isThereAnyError)
    <script>
        showEditKelurahanModal();
    </script>
@endif