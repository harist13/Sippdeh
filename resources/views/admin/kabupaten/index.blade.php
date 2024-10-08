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
    @php $status = session('status_pembuatan_kabupaten'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kabupaten berhasil ditambahkan.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kabupaten gagal ditambahkan.'])
        @endif
    @endif

    @php $status = session('status_pengeditan_kabupaten'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kabupaten berhasil diedit.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kabupaten gagal diedit.'])
        @endif
    @endif

    @php $status = session('status_penghapusan_kabupaten'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kabupaten berhasil dihapus.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kabupaten gagal dihapus.'])
        @endif
    @endif

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md mb-5">
        <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile">
            <div class="flex items-center space-x-2 w-full-mobile">
                <span class="text-lg font-bold"><i class="fas fa-city"></i> Kabupaten / Kota</span>
            </div>
            <div class="flex flex-col-mobile gap-5 space-y-2-mobile w-full-mobile">
                <button id="addKabupatenBtn" class="bg-[#3560A0] text-white py-2 px-4 rounded-lg w-full-mobile">+ Tambah Kabupaten/Kota</button>

                <div class="relative w-full-mobile">
                    <button id="dropdownButton" class="bg-gray-100 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center justify-between w-full-mobile">
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
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Provinsi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @foreach ($kabupaten as $kota)
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">{{ $kota->getThreeDigitsId() }}</td>
                            <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">{{ $kota->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">{{ $kota->provinsi->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200  text-sm-mobile">
                                <button class="editKabupatenBtn text-[#3560A0] hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                <button class="text-red-600 hover:text-red-900 ml-3"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $kabupaten->links('vendor.pagination.tailwind', ['namaModel' => 'kabupaten']) }}
    </div>

    @include('admin.kabupaten.tambah-modal')

    <!-- Edit Kabupaten/Kota Modal -->
    <div id="editKabupatenModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Kabupaten/Kota</h3>
                <div class="mt-2 px-7 py-3">
                    <input type="text" id="editKabupatenName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Nama Kabupaten/Kota">
                    <select id="editKabupatenProvinsi" class="w-full px-3 py-2 mt-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option>Pilih Provinsi</option>
                        <option>Kalimantan Timur</option>
                        <!-- Add more provinces as needed -->
                    </select>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="cancelEditKabupaten" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-24 mr-2">
                        Batalkan
                    </button>
                    <button id="confirmEditKabupaten" class="px-4 py-2 bg-[#3560A0] text-white text-base font-medium rounded-md w-24 ml-2">
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

    // Add Kabupaten/Kota Modal
    var addKabupatenModal = document.getElementById('addKabupatenModal');
    var addKabupatenBtn = document.getElementById('addKabupatenBtn');
    var cancelAddKabupaten = document.getElementById('cancelAddKabupaten');
    var confirmAddKabupaten = document.getElementById('confirmAddKabupaten');

    addKabupatenBtn.onclick = function() {
        addKabupatenModal.classList.remove('hidden');
    }

    cancelAddKabupaten.onclick = function() {
        addKabupatenModal.classList.add('hidden');
    }

    // Edit Kabupaten/Kota Modal
    var editKabupatenModal = document.getElementById('editKabupatenModal');
    var editKabupatenBtns = document.querySelectorAll('.editKabupatenBtn');
    var cancelEditKabupaten = document.getElementById('cancelEditKabupaten');
    var confirmEditKabupaten = document.getElementById('confirmEditKabupaten');

    editKabupatenBtns.forEach(function(btn) {
        btn.onclick = function() {
            var kabupatenName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
            var kabupatenProvinsi = this.closest('tr').querySelector('td:nth-child(3)').textContent;
            document.getElementById('editKabupatenName').value = kabupatenName;
            document.getElementById('editKabupatenProvinsi').value = kabupatenProvinsi;
            editKabupatenModal.classList.remove('hidden');
        }
    });

    cancelEditKabupaten.onclick = function() {
        editKabupatenModal.classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target == addKabupatenModal) {
            addKabupatenModal.classList.add('hidden');
        }
        if (event.target == editKabupatenModal) {
            editKabupatenModal.classList.add('hidden');
        }
    }
</script>

@include('admin.layout.footer')