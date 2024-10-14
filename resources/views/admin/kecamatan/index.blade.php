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

    @php $status = session('status_ekspor_kecamatan'); @endphp
    @if($status != null)
        @include('components.alert-gagal', ['message' => 'Kecamatan gagal diekspor.'])
    @endif

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md mb-5">
        <div class="flex flex-col justify-between items-start mb-4 space-y-2-mobile">
            <div class="flex items-center space-x-2 w-full-mobile mb-8">
                <span class="text-lg font-bold"><i class="fas fa-map-marker-alt me-1"></i> Kecamatan</span>
            </div>
            
            <div class="flex flex-col-mobile w-full justify-between gap-5 space-y-2-mobile">
                <div class="flex flex-col-mobile gap-3">
                    <form action="{{ route('kecamatan') }}" method="GET">
                        <div class="flex items-center border border-gray-300 rounded-lg bg-gray-100 px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                            </svg>
                            <input type="search" placeholder="Cari kecamatan" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
                            @if (request()->has('kabupaten'))
                                <input type="hidden" name="kabupaten" value="{{ request()->get('kabupaten') }}">
                            @endif
                        </div>                  
                    </form>
    
                    @include('components.dropdown-kabupaten', ['kabupaten' => $kabupaten, 'routeName' => 'kecamatan'])
                </div>

                <div class="flex flex-col-mobile gap-3">
                    <button id="addKecamatanBtn" class="bg-[#3560A0] text-white py-2 px-4 rounded-lg w-full-mobile">+ Tambah Kecamatan</button>
                    
                    <div class="flex">
                        <button id="importKecamatanBtn" class="bg-[#008080] w-full-mobile text-white py-2 px-4 rounded-s-lg">
                            <i class="fas fa-file-import me-1"></i>
                            <span>Impor</span>
                        </button>
                        <button id="exportKecamatanBtn" class="bg-[#FA8072] w-full-mobile text-white py-2 px-4 rounded-e-lg">
                            <i class="fas fa-file-export me-1"></i>
                            <span>Ekspor</span>
                        </button>
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
                    @forelse ($kecamatan as $kec)
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">{{ $kec->getThreeDigitsId() }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $kec->id }}" data-nama="{{ $kec->nama }}">{{ $kec->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $kec->kabupaten->id }}">{{ $kec->kabupaten->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">
                                <button class="text-[#3560A0] hover:text-blue-900 edit-kecamatan-btn"><i class="fas fa-edit"></i></button>
                                <button class="text-red-600 hover:text-red-900 ml-3 hapus-kecamatan-btn"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-gray-200 text-center">
                            <td class="py-5" colspan="4">
                                @if (request()->has('cari'))
                                    <p>Tidak ada data kecamatan dengan kata kunci "{{ request()->get('cari') }}"</p>
                                @else
                                    <p>Belum ada data kecamatan</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $kecamatan->links('vendor.pagination.tailwind', ['namaModel' => 'kecamatan']) }}
    </div>
</main>

@include('admin.kecamatan.tambah-modal')
@include('admin.kecamatan.edit-modal')
@include('admin.kecamatan.hapus-modal')
@include('admin.kecamatan.ekspor-modal')

<script>
    // Tutup modal saat tombol esc di tekan
    document.addEventListener('keyup', function(event) {
        if (event.key === "Escape") {
            closeAddKecamatanModal();
            closeEditKecamatanModal();
            closeDeleteKecamatanModal();
            closeExportKecamatanModal();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target == addKecamatanModal) {
            closeAddKecamatanModal();
        }

        if (event.target == editKecamatanModal) {
            closeEditKecamatanModal();
        }

        if (event.target == deleteKecamatanModal) {
            closeDeleteKecamatanModal();
        }

        if (event.target == exportKecamatanModal) {
            closeExportKecamatanModal();
        }
    });
</script>

@include('admin.layout.footer')