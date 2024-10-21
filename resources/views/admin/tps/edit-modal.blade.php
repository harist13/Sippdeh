<!-- Edit TPS Modal -->
<div id="editTPSModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form id="editTPSForm" action="#" method="POST">
            @method('PUT')
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Edit TPS</h3>

			{{-- Nama TPS --}}
			<label for="editTPSName" class="mb-1 block">Nama</label>
            <input type="text" id="editTPSName" name="nama_tps"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama TPS" required>
            <span class="text-red-800">{{ $errors->first('nama_tps') }}</span>

			{{-- Kelurahan --}}
			<label for="editTPSKelurahanId" class="my-1 block">Kelurahan</label>
			<select id="editTPSKelurahanId" name="kelurahan_id_tps" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
				@foreach ($kelurahan as $kel)
					<option value="{{ $kel->id }}">{{ $kel->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kelurahan_id_tps') }}</span>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelEditTPS" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmEditTPS" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Simpan
				</button>
            </div>
        </form>
    </div>
</div>

<script>
    function getTPSId() {
        return this.closest('tr').querySelector('td:nth-child(5)').dataset.id;
    }

    function getTPSName() {
        return this.closest('tr').querySelector('td:nth-child(5)').dataset.nama;
    }

    function getTPSKelurahanId() {
        return this.closest('tr').querySelector('td:nth-child(3)').dataset.id;
    }

    function showEditTPSModal() {
		const editTPSModal = document.getElementById('editTPSModal');
		editTPSModal.classList.remove('hidden');
	}

	function closeEditTPSModal() {
		const editTPSModal = document.getElementById('editTPSModal');
		editTPSModal.classList.add('hidden');
	}

    function getUpdateTPSUrl() {
        const tpsId = getTPSId.call(this);
        const tpsUpdateRoute = `{{ route('tps.update', ['tp' => '__tp__']) }}`;
        const tpsUpdateUrl = tpsUpdateRoute.replace('__tp__', tpsId);

        return tpsUpdateUrl;
    }

    document.querySelectorAll('.edit-tps-btn').forEach(button => {
        button.addEventListener('click', function() {
            showEditTPSModal();

            const editTPSName = document.getElementById('editTPSName');
            editTPSName.value = getTPSName.call(this);

            const editTPSKelurahanId = document.getElementById('editTPSKelurahanId');
            editTPSKelurahanId.value = getTPSKelurahanId.call(this);

            const editTPSForm = document.getElementById('editTPSForm');
            console.log(editTPSForm)
            editTPSForm.action = getUpdateTPSUrl.call(this);
        });
    });

    document.getElementById('cancelEditTPS').addEventListener('click', closeEditTPSModal);
</script>

@error('nama_tps')
    <script>
        showEditTPSModal();
    </script>
@enderror

@error('kelurahan_id_tps')
    <script>
        showEditTPSModal();
    </script>
@enderror