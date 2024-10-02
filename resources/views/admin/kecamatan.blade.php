@include('admin.layout.header')

<main class="container flex-grow px-4 mx-auto mt-6">
<div class="container mx-auto mt-8">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <span class="text-lg font-bold"><i class="fa fa-map-marker"></i> Kecamatan</span>
        </div>
        <div class="flex space-x-2">
            <div class="relative">
                <button id="dropdownButton" class="bg-gray-100 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center">
                    Pilih Kab/Kota <i class="fa fa-chevron-down ml-2"></i>
                </button>
                <div id="dropdownMenu" class="absolute mt-2 w-full rounded-lg shadow-lg bg-white z-10 hidden">
                    <ul class="py-1 text-gray-700">
                        <li class="px-4 py-2 hover:bg-gray-100">Samarinda</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Balikpapan</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Bontang</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Kutai Kartanegara</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Kutai Timur</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Kutai Barat</li>
                    </ul>
                </div>
            </div>
            <button id="addKecamatanBtn" class="bg-blue-500 text-white py-2 px-4 rounded-lg">+ Tambah Kecamatan</button>
            <input type="text" placeholder="Cari Kelurahan" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal text-sm">
            <thead>
                <tr>
                    <th class="px-4 py-3 bg-blue-600 text-left text-xs font-semibold text-white uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 bg-blue-600 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama Kecamatan</th>
                    <th class="px-4 py-3 bg-blue-600 text-left text-xs font-semibold text-white uppercase tracking-wider">Kabupaten/Kota</th>
                    <th class="px-4 py-3 bg-blue-600 text-left text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">001</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">Palaran</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">Samarinda</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                        <button class="editKecamatanBtn text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900 ml-3"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add Kecamatan Modal -->
<div id="addKecamatanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Kecamatan</h3>
            <div class="mt-2 px-7 py-3">
                <input type="text" id="addKecamatanName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Nama Kecamatan">
                <select id="addKecamatanKabupaten" class="w-full px-3 py-2 mt-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">Pilih Kabupaten/Kota</option>
                    <option value="1">Samarinda</option>
                    <option value="2">Balikpapan</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="items-center px-4 py-3">
                <button id="cancelAddKecamatan" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-24 mr-2">
                    Batalkan
                </button>
                <button id="confirmAddKecamatan" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-24 ml-2">
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Kecamatan Modal -->
<div id="editKecamatanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Kecamatan</h3>
            <div class="mt-2 px-7 py-3">
                <input type="text" id="editKecamatanName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Nama Kecamatan">
                <select id="editKecamatanKabupaten" class="w-full px-3 py-2 mt-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option>Pilih Kabupaten/Kota</option>
                    <option>Samarinda</option>
                    <option>Balikpapan</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="items-center px-4 py-3">
                <button id="cancelEditKecamatan" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-24 mr-2">
                    Batalkan
                </button>
                <button id="confirmEditKecamatan" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-24 ml-2">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

</main>

<script>
    // Dropdown functionality
    document.getElementById('dropdownButton').addEventListener('click', function() {
        var menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });

    // Add Kecamatan Modal
    var addKecamatanModal = document.getElementById('addKecamatanModal');
    var addKecamatanBtn = document.getElementById('addKecamatanBtn');
    var cancelAddKecamatan = document.getElementById('cancelAddKecamatan');
    var confirmAddKecamatan = document.getElementById('confirmAddKecamatan');

    addKecamatanBtn.onclick = function() {
        addKecamatanModal.classList.remove('hidden');
    }

    cancelAddKecamatan.onclick = function() {
        addKecamatanModal.classList.add('hidden');
    }

    confirmAddKecamatan.onclick = function() {
        // Add logic to save new Kecamatan
        var newKecamatanName = document.getElementById('addKecamatanName').value;
        var newKecamatanKabupaten = document.getElementById('addKecamatanKabupaten').value;
        console.log('Adding new Kecamatan:', newKecamatanName, 'Kabupaten/Kota:', newKecamatanKabupaten);
        addKecamatanModal.classList.add('hidden');
    }

    // Edit Kecamatan Modal
    var editKecamatanModal = document.getElementById('editKecamatanModal');
    var editKecamatanBtns = document.querySelectorAll('.editKecamatanBtn');
    var cancelEditKecamatan = document.getElementById('cancelEditKecamatan');
    var confirmEditKecamatan = document.getElementById('confirmEditKecamatan');

    editKecamatanBtns.forEach(function(btn) {
        btn.onclick = function() {
            var kecamatanName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
            var kecamatanKabupaten = this.closest('tr').querySelector('td:nth-child(3)').textContent;
            document.getElementById('editKecamatanName').value = kecamatanName;
            document.getElementById('editKecamatanKabupaten').value = kecamatanKabupaten;
            editKecamatanModal.classList.remove('hidden');
        }
    });

    cancelEditKecamatan.onclick = function() {
        editKecamatanModal.classList.add('hidden');
    }

    confirmEditKecamatan.onclick = function() {
        // Add logic to save edited Kecamatan
        var editedKecamatanName = document.getElementById('editKecamatanName').value;
        var editedKecamatanKabupaten = document.getElementById('editKecamatanKabupaten').value;
        console.log('Saving edited Kecamatan:', editedKecamatanName, 'Kabupaten/Kota:', editedKecamatanKabupaten);
        editKecamatanModal.classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == addKecamatanModal) {
            addKecamatanModal.classList.add('hidden');
        }
        if (event.target == editKecamatanModal) {
            editKecamatanModal.classList.add('hidden');
        }
    }
</script>

@include('admin.layout.footer')