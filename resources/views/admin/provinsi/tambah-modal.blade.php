<!-- Add Provinsi Modal -->
<div id="addProvinsiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Tambah Provinsi</h3>
        <input type="text" id="addProvinsiName"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Nama Provinsi">
        <hr class="h-1 my-3">
        <div class="flex items-center">
            <button id="cancelAddProvinsi"
                class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400 mr-2">Batalkan</button>
            <button id="confirmAddProvinsi"
                class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Tambah</button>
        </div>
    </div>
</div>

<script>
    // Add Provinsi Modal
    const addProvinsiBtn = document.getElementById('addProvinsiBtn');
    const addProvinsiModal = document.getElementById('addProvinsiModal');
    const cancelAddProvinsi = document.getElementById('cancelAddProvinsi');
    const confirmAddProvinsi = document.getElementById('confirmAddProvinsi');

    addProvinsiBtn.onclick = function () {
        addProvinsiModal.classList.remove('hidden');
    }

    cancelAddProvinsi.onclick = function () {
        addProvinsiModal.classList.add('hidden');
    }
</script>