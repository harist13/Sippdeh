<!-- Add Kabupaten/Kota Modal -->
<div id="addKabupatenModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('kabupaten.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Tambah Kabupaten/Kota</h3>

			{{-- Logo --}}
            <label for="addKabupatenLogo" class="mb-1 block">Logo</label>
            <div class="mb-2">
                <img id="previewLogoAdd" src="" alt="Preview Logo" class="w-32 h-32 object-contain mx-auto hidden">
            </div>
            <input type="file" id="addKabupatenLogo" name="logo_kabupaten_baru" accept="image/*"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="text-gray-500 text-sm">Format: JPG, JPEG, PNG (max 2MB)</span>
            <span class="text-red-800">{{ $errors->first('logo_kabupaten_baru') }}</span>

			{{-- Nama kabupaten --}}
			<label for="addKabupatenName" class="mb-1 block">Nama</label>
            <input type="text" id="addKabupatenName" name="nama_kabupaten_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama kabupaten" required>
            <span class="text-red-800">{{ $errors->first('nama_kabupaten_baru') }}</span>

			{{-- Provinsi --}}
			<label for="addKabupatenProvinsi" class="my-1 block">Provinsi</label>
			<select id="addKabupatenProvinsi" name="provinsi_id_kabupaten_baru" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
				@foreach ($provinsi as $prov)
					<option value="{{ $prov->id }}">{{ $prov->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('provinsi_id_kabupaten_baru') }}</span>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelAddKabupaten" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmAddKabupaten" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Tambah
				</button>
            </div>
        </form>
    </div>
</div>

<script>
	function showAddKabupatenModal() {
		const addKabupatenModal = document.getElementById('addKabupatenModal');
		addKabupatenModal.classList.remove('hidden');
	}

	function closeAddKabupatenModal() {
		const addKabupatenModal = document.getElementById('addKabupatenModal');
		addKabupatenModal.classList.add('hidden');
        // Reset form
        document.getElementById('addKabupatenName').value = '';
        document.getElementById('addKabupatenLogo').value = '';
        document.getElementById('previewLogoAdd').classList.add('hidden');
	}

    // Preview logo saat file dipilih untuk tambah
    document.getElementById('addKabupatenLogo').addEventListener('change', function(e) {
        const previewLogo = document.getElementById('previewLogoAdd');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewLogo.src = e.target.result;
                previewLogo.classList.remove('hidden');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    document.getElementById('addKabupatenBtn').addEventListener('click', showAddKabupatenModal);
    document.getElementById('cancelAddKabupaten').addEventListener('click', closeAddKabupatenModal);
</script>

@error('nama_kabupaten_baru')
    <script>
        showAddKabupatenModal();
    </script>
@enderror

@error('provinsi_id_kabupaten_baru')
    <script>
        showAddKabupatenModal();
    </script>
@enderror

@error('logo_kabupaten_baru')
    <script>
        showAddKabupatenModal();
    </script>
@enderror