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
    @php $status = session('status_pembuatan_kecamatan'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kecamatan berhasil ditambahkan.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kecamatan gagal ditambahkan.'])
        @endif
    @endif

    @php $status = session('status_pengeditan_kecamatan'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kecamatan berhasil diedit.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kecamatan gagal diedit.'])
        @endif
    @endif

    @php $status = session('status_penghapusan_kecamatan'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kecamatan berhasil dihapus.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kecamatan gagal dihapus.'])
        @endif
    @endif

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md mb-5">
        <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile">
            <div class="flex items-center space-x-2 w-full-mobile">
                <span class="text-lg font-bold"><i class="fas fa-city"></i> Kecamatan</span>
            </div>
            <div class="flex flex-col-mobile gap-5 space-y-2-mobile w-full-mobile">
                <button id="addKecamatanBtn" class="bg-[#3560A0] text-white py-2 px-4 rounded-lg w-full-mobile">+ Tambah Kecamatan</button>

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
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kabupaten/Kota</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @foreach ($kecamatan as $kec)
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">{{ $kec->getThreeDigitsId() }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $kec->id }}" data-nama="{{ $kec->nama }}">{{ $kec->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $kec->kabupaten->id }}">{{ $kec->kabupaten->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">
                                <button class="text-[#3560A0] hover:text-blue-900 edit-kecamatan-btn"><i class="fas fa-edit"></i></button>
                                <button class="text-red-600 hover:text-red-900 ml-3 hapus-kecamatan-btn"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $kecamatan->links('vendor.pagination.tailwind', ['namaModel' => 'kecamatan']) }}
    </div>
</main>

@include('admin.kecamatan.tambah-modal')
@include('admin.kecamatan.edit-modal')
@include('admin.kecamatan.hapus-modal')

<script>
    // Dropdown functionality
    document.getElementById('dropdownButton').addEventListener('click', function() {
        var menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });

    cancelEditKecamatan.onclick = function() {
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