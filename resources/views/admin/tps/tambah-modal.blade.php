<!-- Add TPS Modal -->
<div id="addTPSModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('tps.store') }}" method="POST">
            @csrf
            <h3 class="text-lg text-center leading-6 font-medium text-gray-900 mb-5">Tambah TPS</h3>

			{{-- Nama tps --}}
			<label for="addTPSName" class="mb-1 block">Nama</label>
            <input type="text" id="addTPSName" name="nama_tps_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <span class="text-red-800">{{ $errors->first('nama_tps_baru') }}</span>

			{{-- DPT --}}
			<label for="addTPSDPT" class="mb-1 block">DPT</label>
            <input type="number" id="addTPSDPT" name="dpt_tps_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <span class="text-red-800">{{ $errors->first('dpt_tps_baru') }}</span>

			{{-- Kelurahan --}}
			<label for="addTPSKelurahan" class="my-1 block">Kelurahan</label>
			<select id="addTPSKelurahan" name="kelurahan_id_tps_baru" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
				@foreach ($kelurahan as $kel)
					<option value="{{ $kel->id }}">{{ $kel->nama }}</option>
				@endforeach
			</select>
			<span class="text-red-800">{{ $errors->first('kelurahan_id_tps_baru') }}</span>

            <hr class="h-1 my-3">

            <div class="flex items-center">
                <button type="button" id="cancelAddTPS" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">
					Batalkan
				</button>
                <button type="submit" id="confirmAddTPS" class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">
					Tambah
				</button>
            </div>
        </form>
    </div>
</div>

<script>
	function showAddTPSModal() {
		const addTPSModal = document.getElementById('addTPSModal');
		addTPSModal.classList.remove('hidden');
	}

	function closeAddTPSModal() {
		const addTPSModal = document.getElementById('addTPSModal');
		addTPSModal.classList.add('hidden');
	}

    document.getElementById('addTPSBtn').addEventListener('click', showAddTPSModal);
    document.getElementById('cancelAddTPS').addEventListener('click', closeAddTPSModal);
</script>

@error('nama_tps_baru', 'dpt_tps_baru', 'kelurahan_id_tps_baru')
    <script>
        showAddTPSModal();
    </script>
@enderror