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
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile">
        <div class="flex items-center space-x-2 w-full-mobile">
            <span class="text-lg font-bold"><i class="fa fa-user-group"></i> Calon</span>
        </div>
        <div class="flex flex-col-mobile space-y-2-mobile w-full-mobile">
            <div class="relative w-full-mobile">
                <button id="dropdownButton" class="bg-gray-100 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center justify-between w-full-mobile">
                    Pilih Kab/Kota <i class="fa fa-chevron-down ml-2"></i>
                </button>
                <div id="dropdownMenu" class="absolute mt-2 w-full rounded-lg shadow-lg bg-white z-10 hidden">
                    <ul class="py-1 text-gray-700">
                        <li class="px-4 py-2 hover:bg-gray-100">Samarinda</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Balikpapan</li>
                        <li class="px-4 py-2 hover:bg-gray-100">Bontang</li>
                    </ul>
                </div>
            </div>
            <button id="addCalonBtn" class="bg-blue-500 text-white py-2 px-4 rounded-lg w-full-mobile">+ Tambah Calon</button>
            <input type="text" placeholder="Cari" class="border border-gray-300 rounded-lg px-4 py-2 w-full-mobile">
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto">
        <table class="min-w-full leading-normal text-sm-mobile">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Pasangan Calon</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kabupaten/Kota</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Foto</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-gray-100">
                <tr class="hover:bg-gray-200">
                    <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">001</td>
                    <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">Andi Harun / Saefuddin Zuhri</td>
                    <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">Samarinda</td>
                    <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">Gambar belum upload</td>
                    <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">
                        <button class="editCalonBtn text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                        <button class="text-red-600 hover:text-red-900 ml-3"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <!-- Add more rows here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add Calon Modal -->
<div id="addCalonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full sm:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 mt-3 text-center" >Tambah Calon</h3>
            <div class="mt-2 space-y-4">
                <div>
                    <label for="addCalonName" class="block text-sm font-medium text-gray-700">Nama Calon</label>
                    <input type="text" id="addCalonName" class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Pasangan Calon">
                </div>
                <div>
                    <label for="addCalonKota" class="block text-sm font-medium text-gray-700">Kota</label>
                    <div class="relative">
                        <select id="addCalonKota" class="mt-1 block w-full pl-3 pr-10 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 appearance-none">
                            <option value="">Pilih Kab/Kota</option>
                            <option value="Samarinda">Samarinda</option>
                            <option value="Balikpapan">Balikpapan</option>
                            <option value="Bontang">Bontang</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="addCalonFoto" class="block text-sm font-medium text-gray-700">Foto</label>
                    <div class="mt-1 flex items-center">
                        <span class="inline-block bg-gray-100 rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 w-full">
                            <i class="fas fa-image mr-2"></i> Masukkan Foto
                        </span>
                        <input id="addCalonFoto" name="addCalonFoto" type="file" class="sr-only">
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3 mt-4 flex justify-end space-x-3">
                <button id="cancelAddCalon" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full sm:w-24 hover:bg-gray-400">
                    Batalkan
                </button>
                <button id="confirmAddCalon" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full sm:w-24 hover:bg-blue-600">
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Calon Modal -->
<div id="editCalonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full sm:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 mt-3 text-center">Edit Calon</h3>
            <div class="mt-2 space-y-4">
                <div>
                    <label for="editCalonName" class="block text-sm font-medium text-gray-700">Nama Calon</label>
                    <input type="text" id="editCalonName" class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Pasangan Calon">
                </div>
                <div>
                    <label for="editCalonKota" class="block text-sm font-medium text-gray-700">Kota</label>
                    <div class="relative">
                        <select id="editCalonKota" class="mt-1 block w-full pl-3 pr-10 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 appearance-none">
                            <option value="">Pilih Kab/Kota</option>
                            <option value="Samarinda">Samarinda</option>
                            <option value="Balikpapan">Balikpapan</option>
                            <option value="Bontang">Bontang</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="editCalonFoto" class="block text-sm font-medium text-gray-700">Foto</label>
                    <div class="mt-1 flex items-center">
                        <span class="inline-block bg-gray-100 rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 w-full">
                            <i class="fas fa-image mr-2"></i> Masukkan Foto
                        </span>
                        <input id="editCalonFoto" name="editCalonFoto" type="file" class="sr-only">
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3 mt-4 flex justify-end space-x-3">
                <button id="cancelEditCalon" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full sm:w-24 hover:bg-gray-400">
                    Batalkan
                </button>
                <button id="confirmEditCalon" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full sm:w-24 hover:bg-blue-600">
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

    // Add Calon Modal
    var addCalonModal = document.getElementById('addCalonModal');
    var addCalonBtn = document.getElementById('addCalonBtn');
    var cancelAddCalon = document.getElementById('cancelAddCalon');
    var confirmAddCalon = document.getElementById('confirmAddCalon');

    addCalonBtn.onclick = function() {
        addCalonModal.classList.remove('hidden');
    }

    cancelAddCalon.onclick = function() {
        addCalonModal.classList.add('hidden');
    }

    confirmAddCalon.onclick = function() {
        // Add logic to save new Calon
        var newCalonName = document.getElementById('addCalonName').value;
        var newCalonKota = document.getElementById('addCalonKota').value;
        var newCalonFoto = document.getElementById('addCalonFoto').value;
        console.log('Adding new Calon:', newCalonName, 'Kota:', newCalonKota, 'Foto:', newCalonFoto);
        addCalonModal.classList.add('hidden');
    }

    // Edit Calon Modal
    var editCalonModal = document.getElementById('editCalonModal');
    var editCalonBtns = document.querySelectorAll('.editCalonBtn');
    var cancelEditCalon = document.getElementById('cancelEditCalon');
    var confirmEditCalon = document.getElementById('confirmEditCalon');

    editCalonBtns.forEach(function(btn) {
        btn.onclick = function() {
            var calonName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
            var calonKota = this.closest('tr').querySelector('td:nth-child(4)').textContent;
            document.getElementById('editCalonName').value = calonName;
            document.getElementById('editCalonKota').value = calonKota;
            editCalonModal.classList.remove('hidden');
        }
    });

    cancelEditCalon.onclick = function() {
        editCalonModal.classList.add('hidden');
    }

    confirmEditCalon.onclick = function() {
        // Add logic to save edited Calon
        var editedCalonName = document.getElementById('editCalonName').value;
        var editedCalonKota = document.getElementById('editCalonKota').value;
        var editedCalonFoto = document.getElementById('editCalonFoto').value;
        console.log('Saving edited Calon:', editedCalonName, 'Kota:', editedCalonKota, 'Foto:', editedCalonFoto);
        editCalonModal.classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == addCalonModal) {
            addCalonModal.classList.add('hidden');
        }
        if (event.target == editCalonModal) {
            editCalonModal.classList.add('hidden');
        }
    }

    // File input styling for Add Modal
    document.querySelector('#addCalonModal .inline-block').addEventListener('click', function() {
        document.getElementById('addCalonFoto').click();
    });

    document.getElementById('addCalonFoto').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        this.previousElementSibling.textContent = fileName;
    });

    // File input styling for Edit Modal
    document.querySelector('#editCalonModal .inline-block').addEventListener('click', function() {
        document.getElementById('editCalonFoto').click();
    });

    document.getElementById('editCalonFoto').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        this.previousElementSibling.textContent = fileName;
    });
</script>

@include('admin.layout.footer')