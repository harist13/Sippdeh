<!-- Add Kelurahan Modal -->
<div id="addKelurahanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('kelurahan.store') }}" method="POST">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Tambah Kelurahan</h3>

			{{-- Nama kelurahan --}}
            <div class="mb-3">
                <label for="addKelurahanName" class="mb-1 block">Nama</label>
                <input type="text" id="addKelurahanName" name="name"
                    class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nama kelurahan" required>
            </div>

			{{-- Kecamatan --}}
            <div class="mb-3">
                <label for="addKelurahanKecamatan" class="my-1 block">Kecamatan</label>
                <select id="addKelurahanKecamatan" name="kecamatan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @foreach ($kecamatan as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                </select>
            </div>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="closeAddKelurahan" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Tambah
				</button>
            </div>
        </form>
    </div>
</div>

<script>
	function showAddKelurahanModal() {
		const addKelurahanModal = document.getElementById('addKelurahanModal');
		addKelurahanModal.classList.remove('hidden');
	}

	function closeAddKelurahanModal() {
		const addKelurahanModal = document.getElementById('addKelurahanModal');
		addKelurahanModal.classList.add('hidden');
	}

    document.getElementById('addKelurahan').addEventListener('click', showAddKelurahanModal);
    document.getElementById('closeAddKelurahan').addEventListener('click', closeAddKelurahanModal);
</script>