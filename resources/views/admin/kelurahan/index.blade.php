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
    @php $status = session('status_pembuatan_kelurahan'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kelurahan berhasil ditambahkan.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kelurahan gagal ditambahkan.'])
        @endif
    @endif

    @php $status = session('status_pengeditan_kelurahan'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kelurahan berhasil diedit.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kelurahan gagal diedit.'])
        @endif
    @endif

    @php $status = session('status_penghapusan_kelurahan'); @endphp
    @if($status != null)
        @if ($status == 'berhasil')
            @include('components.alert-berhasil', ['message' => 'Kelurahan berhasil dihapus.'])
        @else
            @include('components.alert-gagal', ['message' => 'Kelurahan gagal dihapus.'])
        @endif
    @endif

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md mb-5">
        <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile">
            <div class="flex items-center space-x-2 w-full-mobile">
                <span class="text-lg font-bold"><i class="fas fa-city"></i> Kelurahan</span>
            </div>
            <div class="flex flex-col-mobile gap-5 space-y-2-mobile w-full-mobile">
                <div class="relative w-[300px] w-full-mobile">
                    <button id="dropdownButton" class="bg-gray-100 w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center justify-between w-full-mobile">
                        Pilih Kab/Kota <i class="fas fa-chevron-right ml-2"></i>
                    </button>
                    <div id="dropdownMenu" class="absolute mt-2 inset-x-0 rounded-lg shadow-lg bg-white z-10 hidden">
                        <ul class="py-1 text-gray-700">
                            @if (request()->has('cari'))
                                <a href="{{ route('kelurahan') }}?cari={{ request()->get('cari') }}">
                                    <li class="px-4 py-2 hover:bg-gray-100">
                                        Semua
                                    </li>
                                </a>
                            @else
                                <a href="{{ route('kelurahan') }}">
                                    <li class="px-4 py-2 hover:bg-gray-100">
                                        Semua
                                    </li>
                                </a>
                            @endif
                            @foreach ($kabupaten as $kab)
                                @if (request()->has('cari'))
                                    <a href="{{ route('kelurahan') }}?cari={{ request()->get('cari') }}&kabupaten={{ $kab->id }}">   
                                        <li class="px-4 py-2 hover:bg-gray-100">{{ $kab->nama }}</li>
                                    </a>
                                @else
                                    <a href="{{ route('kelurahan') }}?kabupaten={{ $kab->id }}">
                                        <li class="px-4 py-2 hover:bg-gray-100">{{ $kab->nama }}</li>
                                    </a>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <form action="{{ route('kelurahan') }}" method="GET">
                    <div class="flex items-center border border-gray-300 rounded-lg bg-gray-100 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                        </svg>
                        <input type="search" placeholder="Cari kelurahan" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
                        @if (request()->has('kabupaten'))
                            <input type="hidden" name="kabupaten" value="{{ request()->get('kabupaten') }}">
                        @endif
                    </div>                  
                </form>

                <button id="addKelurahanBtn" class="bg-[#3560A0] text-white py-2 px-4 rounded-lg w-full-mobile">+ Tambah Kelurahan</button>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto mb-5">
            <table class="min-w-full leading-normal text-sm-mobile">
                <thead>
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kecamatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @forelse ($kelurahan as $kel)
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">{{ $kel->getThreeDigitsId() }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $kel->id }}" data-nama="{{ $kel->nama }}">{{ $kel->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $kel->kecamatan->id }}">{{ $kel->kecamatan->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">
                                <button class="text-[#3560A0] hover:text-blue-900 edit-kelurahan-btn"><i class="fas fa-edit"></i></button>
                                <button class="text-red-600 hover:text-red-900 ml-3 hapus-kelurahan-btn"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-gray-200 text-center">
                            <td class="py-5" colspan="4">
                                @if (request()->has('cari'))
                                    <p>Tidak ada data kelurahan dengan kata kunci "{{ request()->get('cari') }}"</p>
                                @else
                                    <p>Belum ada data kelurahan</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $kelurahan->links('vendor.pagination.tailwind', ['namaModel' => 'kelurahan']) }}
    </div>
</main>

@include('admin.kelurahan.tambah-modal')
@include('admin.kelurahan.edit-modal')
@include('admin.kelurahan.hapus-modal')

<script>
    // Dropdown kabupaten/kota
    document.getElementById('dropdownButton').addEventListener('click', function() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });

    // Tutup modal saat tombol esc di tekan
    document.addEventListener('keyup', function(event) {
        if(event.key === "Escape") {
            closeAddKelurahanModal();
            closeEditKelurahanModal();
            closeDeleteKelurahanModal();
        }
    });

    // Tutup modal saat overlay diklik
    document.addEventListener('click', function(event) {
        if (event.target == addKelurahanModal) {
            closeAddKelurahanModal();
        }

        if (event.target == editKelurahanModal) {
            closeEditKelurahanModal();
        }

        if (event.target == deleteKelurahanModal) {
            closeDeleteKelurahanModal();
        }
    });
</script>

@include('admin.layout.footer')