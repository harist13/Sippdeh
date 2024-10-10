<!-- Add Provinsi Modal -->
<div id="addProvinsiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('provinsi.store') }}" method="POST">
            @csrf
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Tambah Provinsi</h3>
            <input type="text" id="addProvinsiName" name="nama_provinsi_baru"
                class="w-full px-3 py-2 mb-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama provinsi" required>
            <span class="text-red-800">{{ $errors->first('nama_provinsi_baru') }}</span>
            <hr class="h-1 my-3">
            <div class="flex items-center">
                <button id="cancelAddProvinsi"
                    class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
                <button type="submit" id="confirmAddProvinsi"
                    class="flex-1 px-4 py-2 bg-[#3560A0] text-white rounded-md hover:bg-blue-700">Tambah</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showAddProvinsiModal() {
        addProvinsiModal.classList.remove('hidden');
    }

    function closeAddProvinsiModal() {
        addProvinsiModal.classList.add('hidden');
    }

    document.getElementById('addProvinsiBtn').addEventListener('click', showAddProvinsiModal);
    document.getElementById('cancelAddProvinsi').addEventListener('click', closeAddProvinsiModal);
</script>

@error('nama_provinsi_baru')
    <script>
        showAddProvinsiModal();
    </script>
@enderror