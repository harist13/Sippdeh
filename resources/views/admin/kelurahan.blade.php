@include('admin.layout.header')
<style>
    .container {
        max-width: 1200px;
    }
    .rounded-lg {
        border-radius: 0.5rem;
    }
    .shadow-md {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    table tr:nth-child(even) {
        background-color: #f8f8f8;
    }
    
    /* Responsive styles */
    @media (max-width: 640px) {
        .flex-col-mobile {
            flex-direction: column;
        }
        .w-full-mobile {
            width: 100%;
        }
        .space-y-2-mobile > * + * {
            margin-top: 0.5rem;
        }
        .mt-4-mobile {
            margin-top: 1rem;
        }
        .px-2-mobile {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .text-sm-mobile {
            font-size: 0.875rem;
        }
        .overflow-x-auto {
            overflow-x: auto;
        }
    }
</style>
<main class="container flex-grow px-4 mx-auto mt-6">
<div class="container mx-auto mt-8">
    <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile">
        <div class="flex items-center space-x-2 w-full-mobile">
            <span class="text-lg font-bold"><i class="fas fa-map-marker-alt"></i> Kelurahan</span>
        </div>
        <div class="flex flex-col-mobile space-y-2-mobile w-full-mobile">
            <div class="relative w-full-mobile">
                <button id="dropdownButton" class="bg-gray-100 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center justify-between w-full-mobile">
                    Pilih Kab/Kota <i class="fas fa-chevron-right ml-2"></i>
                </button>
                <div id="dropdownMenu" class="absolute mt-2 w-full rounded-lg shadow-lg bg-white z-10 hidden">
                    <ul class="py-1 text-gray-700">
                        <li class="px-4 py-2 hover:bg-gray-100">Samarinda</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Loa Janan Ilir</li>
                    </ul>
                </div>
            </div>
            <button id="addKelurahanBtn" class="bg-blue-500 text-white py-2 px-4 rounded-lg w-full-mobile">+ Tambah Kelurahan</button>
            <div class="relative w-full-mobile">
                <input type="text" placeholder="Cari Kelurahan" class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 w-full-mobile">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto">
        <table class="min-w-full leading-normal text-sm-mobile">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kabupaten/Kota</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kecamatan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kelurahan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm-mobile">001</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm-mobile">Samarinda</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm-mobile">Palaran</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm-mobile">Bantuas</td>
                    <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm-mobile">
                        <button class="editKelurahanBtn text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900 ml-3"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add Kelurahan Modal -->
<div id="addKelurahanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full sm:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Kelurahan</h3>
            <div class="mt-2 px-7 py-3">
                <select id="addKelurahanKabupaten" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 mb-3">
                    <option value="">Pilih Kabupaten/Kota</option>
                    <option value="1">Samarinda</option>
                    <option value="2">Loa Janan Ilir</option>
                </select>
                <select id="addKelurahanKecamatan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 mb-3">
                    <option value="">Pilih Kecamatan</option>
                    <option value="1">Palaran</option>
                    <option value="2">Samarinda Seberang</option>
                </select>
                <input type="text" id="addKelurahanName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Nama Kelurahan">
            </div>
            <div class="items-center px-4 py-3">
                <button id="cancelAddKelurahan" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full sm:w-24 mb-2 sm:mb-0 sm:mr-2">
                    Batalkan
                </button>
                <button id="confirmAddKelurahan" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full sm:w-24">
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Kelurahan Modal -->
<div id="editKelurahanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full sm:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Kelurahan</h3>
            <div class="mt-2 px-7 py-3">
                <select id="editKelurahanKabupaten" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 mb-3">
                    <option>Pilih Kabupaten/Kota</option>
                    <option>Samarinda</option>
                    <option>Loa Janan Ilir</option>
                </select>
                <select id="editKelurahanKecamatan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 mb-3">
                    <option>Pilih Kecamatan</option>
                    <option>Palaran</option>
                    <option>Samarinda Seberang</option>
                </select>
                <input type="text" id="editKelurahanName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Nama Kelurahan">
            </div>
            <div class="items-center px-4 py-3">
                <button id="cancelEditKelurahan" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full sm:w-24 mb-2 sm:mb-0 sm:mr-2">
                    Batalkan
                </button>
                <button id="confirmEditKelurahan" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full sm:w-24">
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

    // Add Kelurahan Modal
    var addKelurahanModal = document.getElementById('addKelurahanModal');
    var addKelurahanBtn = document.getElementById('addKelurahanBtn');
    var cancelAddKelurahan = document.getElementById('cancelAddKelurahan');
    var confirmAddKelurahan = document.getElementById('confirmAddKelurahan');

    addKelurahanBtn.onclick = function() {
        addKelurahanModal.classList.remove('hidden');
    }

    cancelAddKelurahan.onclick = function() {
        addKelurahanModal.classList.add('hidden');
    }

    confirmAddKelurahan.onclick = function() {
        // Add logic to save new Kelurahan
        var newKelurahanKabupaten = document.getElementById('addKelurahanKabupaten').value;
        var newKelurahanKecamatan = document.getElementById('addKelurahanKecamatan').value;
        var newKelurahanName = document.getElementById('addKelurahanName').value;
        console.log('Adding new Kelurahan:', newKelurahanName, 'Kecamatan:', newKelurahanKecamatan, 'Kabupaten/Kota:', newKelurahanKabupaten);
        addKelurahanModal.classList.add('hidden');
    }

    // Edit Kelurahan Modal
    var editKelurahanModal = document.getElementById('editKelurahanModal');
    var editKelurahanBtns = document.querySelectorAll('.editKelurahanBtn');
    var cancelEditKelurahan = document.getElementById('cancelEditKelurahan');
    var confirmEditKelurahan = document.getElementById('confirmEditKelurahan');

    editKelurahanBtns.forEach(function(btn) {
        btn.onclick = function() {
            var kelurahanKabupaten = this.closest('tr').querySelector('td:nth-child(2)').textContent;
            var kelurahanKecamatan = this.closest('tr').querySelector('td:nth-child(3)').textContent;
            var kelurahanName = this.closest('tr').querySelector('td:nth-child(4)').textContent;
            document.getElementById('editKelurahanKabupaten').value = kelurahanKabupaten;
            document.getElementById('editKelurahanKecamatan').value = kelurahanKecamatan;
            document.getElementById('editKelurahanName').value = kelurahanName;
            editKelurahanModal.classList.remove('hidden');
        }
    });

    cancelEditKelurahan.onclick = function() {
        editKelurahanModal.classList.add('hidden');
    }

    confirmEditKelurahan.onclick = function() {
        // Add logic to save edited Kelurahan
        var editedKelurahanKabupaten = document.getElementById('editKelurahanKabupaten').value;
        var editedKelurahanKecamatan = document.getElementById('editKelurahanKecamatan').value;
        var editedKelurahanName = document.getElementById('editKelurahanName').value;
        console.log('Saving edited Kelurahan:', editedKelurahanName, 'Kecamatan:', editedKelurahanKecamatan, 'Kabupaten/Kota:', editedKelurahanKabupaten);
        editKelurahanModal.classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == addKelurahanModal) {
            addKelurahanModal.classList.add('hidden');
        }
        if (event.target == editKelurahanModal) {
            editKelurahanModal.classList.add('hidden');
        }
    }
</script>

@include('admin.layout.footer')