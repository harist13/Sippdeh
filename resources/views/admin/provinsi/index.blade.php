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
    @media (max-width: 768px) {
        .flex-col-mobile {
            flex-direction: column;
        }

        .w-full-mobile {
            width: 100%;
        }

        .space-y-2-mobile>*+* {
            margin-top: 0.5rem;
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        .text-sm-mobile {
            font-size: 0.875rem;
        }

        .px-2-mobile {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }

</style>

<main class="container flex-grow px-4 mx-auto mt-6">
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile">
            <div class="flex items-center space-x-2 w-full-mobile">
                <span class="text-lg font-bold"><i class="fas fa-map-marked-alt"></i> Provinsi</span>
            </div>
            <div class="flex flex-col-mobile space-y-2-mobile w-full-mobile">
                <button id="addProvinsiBtn" class="bg-blue-500 text-white py-2 px-4 rounded-lg w-full-mobile">+ Tambah
                    Provinsi</button>
                <div class="relative w-full-mobile">
                    <button id="dropdownButton"
                        class="bg-gray-100 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center justify-between w-full-mobile">
                        Pilih Kab/Kota <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                    <div id="dropdownMenu" class="absolute mt-2 w-full rounded-lg shadow-lg bg-white z-10 hidden">
                        <ul class="py-1 text-gray-700">
                            <li class="px-4 py-2 hover:bg-gray-100">Samarinda</li>
                            <li class="px-4 py-2 hover:bg-gray-100">Balikpapan</li>
                            <!-- Add more cities as needed -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto mb-5">
            <table class="min-w-full leading-normal text-sm-mobile">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @foreach ($provinsi as $p)
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">{{ $p->id }}</td>
                            <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">{{ $p->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">
                                <button class="edit-provinsi-btn text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $provinsi->links('vendor.pagination.tailwind', ['namaModel' => 'provinsi']) }}
    </div>

    @include('admin.provinsi.tambah-modal')
    @include('admin.provinsi.edit-modal')
</main>


<script>
    document.getElementById('dropdownButton').addEventListener('click', function () {
        var menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });

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

    confirmAddProvinsi.onclick = function () {
        const provinsiName = document.getElementById('addProvinsiName').value;
        console.log('Adding new provinsi:', provinsiName);
        // Add your logic here to save the new provinsi
        addProvinsiModal.classList.add('hidden');
    }

    // Edit Provinsi Modal
    const editProvinsiModal = document.getElementById('editProvinsiModal');
    const cancelEditProvinsi = document.getElementById('cancelEditProvinsi');
    const confirmEditProvinsi = document.getElementById('confirmEditProvinsi');
    const editButtons = document.querySelectorAll('.edit-provinsi-btn');

    editButtons.forEach(button => {
        button.onclick = function () {
            const provinsiName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
            document.getElementById('editProvinsiName').value = provinsiName;
            editProvinsiModal.classList.remove('hidden');
        }
    });

    cancelEditProvinsi.onclick = function () {
        editProvinsiModal.classList.add('hidden');
    }

    confirmEditProvinsi.onclick = function () {
        const provinsiName = document.getElementById('editProvinsiName').value;
        console.log('Editing provinsi:', provinsiName);
        // Add your logic here to save the edited provinsi
        editProvinsiModal.classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function (event) {
        if (event.target == addProvinsiModal) {
            addProvinsiModal.classList.add('hidden');
        }
        if (event.target == editProvinsiModal) {
            editProvinsiModal.classList.add('hidden');
        }
    }

</script>

@include('admin.layout.footer')
