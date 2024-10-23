<!-- Add Calon Modal -->
<div id="addCalonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('calon.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Tambah Calon</h3>

			{{-- Nama calon --}}
			<label for="addCalonName" class="mb-2 block">Nama Calon</label>
            <input
                type="text"
                id="addCalonName"
                name="nama_calon_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama calon"
                required
            >
            <span class="text-red-800">{{ $errors->first('nama_calon_baru') }}</span>

			{{-- Nama calon wakil --}}
			<label for="addCalonWakilName" class="my-2 block">Nama Calon Wakil</label>
            <input
                type="text"
                id="addCalonWakilName"
                name="nama_calon_wakil_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama calon wakil"
                required
            >
            <span class="text-red-800">{{ $errors->first('nama_calon_wakil_baru') }}</span>

			{{-- Kabupaten --}}
			<label for="addCalonKabupaten" class="my-2 block">Kabupaten</label>
			<select
                id="addCalonKabupaten"
                name="kabupaten_id_calon_baru"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
            >
				@foreach ($kabupaten as $kab)
					<option value="{{ $kab->id }}">{{ $kab->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kabupaten_id_calon_baru') }}</span>

            {{-- Foto --}}
            <label for="addCalonPhoto" class="mt-2 block">Foto</label>
            <input
                type="file"
                id="addCalonPhoto"
                name="foto_calon_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Foto calon"
            >
			<span class="text-red-800">{{ $errors->first('foto_calon_baru') }}</span>

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