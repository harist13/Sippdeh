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
    @media (max-width: 1024px) {
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
    @php $pesanSukses = session('pesan_sukses'); @endphp
    @isset ($pesanSukses)
        @include('components.alert-berhasil', ['message' => $pesanSukses])
    @endisset

    @php $pesanGagal = session('pesan_gagal'); @endphp
    @isset ($pesanGagal)
        @include('components.alert-gagal', ['message' => $pesanGagal])
    @endisset

    @php $catatanImpor = session('catatan_impor'); @endphp
    @isset ($catatanImpor)
        <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 mb-3 rounded relative" role="alert">
            <strong class="font-bold mb-1 block">Catatan pengimporan:</strong>
            <ul class="list-disc ms-5">
                @foreach ($catatanImpor as $catatan)
                    <li>{!! $catatan !!}</li>
                @endforeach
            </ul>
        </div>
    @endisset

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md mb-5">
        <div class="flex items-center space-x-2 w-full-mobile mb-5">
            <img src="{{ asset('assets/icon/kabupaten.svg') }}" class="mr-1" alt="Kabupaten">
            <span class="font-bold mt-1">Kabupaten</span>
        </div>
        
        <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile gap-y-5">
            <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                @include('components.dropdown-kabupaten', ['kabupaten' => $kabupaten, 'routeName' => 'kabupaten'])

                <form action="{{ route('kabupaten') }}" method="GET">
                    <div class="flex items-center border border-gray-300 rounded-lg bg-gray-100 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                        </svg>
                        <input type="search" placeholder="Cari kabupaten" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
                        @if (request()->has('kabupaten'))
                            <input type="hidden" name="kabupaten" value="{{ request()->get('kabupaten') }}">
                        @endif
                    </div>         
                </form>
            </div>

            <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                <div class="flex gap-2">
                    <button id="importKabupatenBtn" class="bg-[#58DA91] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-file-import me-1"></i>
                        <span>Impor</span>
                    </button>
                    <button id="exportKabupatenBtn" class="bg-[#EE3C46] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-file-export me-1"></i>
                        <span>Ekspor</span>
                    </button>
                </div>
                <button id="addKabupatenBtn" class="bg-[#0070FF] text-white py-2 px-4 rounded-lg w-full-mobile">
                    <i class="fas fa-plus me-1"></i>
                    <span>Tambah Kabupaten</span>
                </button>
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
                    @forelse ($kabupaten as $kota)
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">{{ $kota->getThreeDigitsId() }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $kota->id }}" data-nama="{{ $kota->nama }}">{{ $kota->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $kota->provinsi->id }}">{{ $kota->provinsi->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">
                                <button class="editKabupatenBtn text-[#3560A0] hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                <button class="text-red-600 hover:text-red-900 ml-3 hapus-kabupaten-btn"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-gray-200 text-center">
                            <td class="py-5" colspan="4">
                                @if (request()->has('cari'))
                                    <p>Tidak ada data kabupaten dengan kata kunci "{{ request()->get('cari') }}"</p>
                                @else
                                    <p>Belum ada data kabupaten</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $kabupaten->links('vendor.pagination.tailwind', ['namaModel' => 'kabupaten']) }}
    </div>
</main>

@include('admin.kabupaten.tambah-modal')
@include('admin.kabupaten.edit-modal')
@include('admin.kabupaten.hapus-modal')
@include('admin.kabupaten.impor-modal')
@include('admin.kabupaten.ekspor-modal')

<script>
    // Tutup modal saat tombol esc di tekan
    document.addEventListener('keyup', function(event) {
        if(event.key === "Escape") {
            closeAddKabupatenModal();
            closeEditKabupatenModal();
            closeDeleteKabupatenModal();
        }
    });

    // Tutup modal saat overlay diklik
    document.addEventListener('click', function(event) {
        if (event.target == addKabupatenModal) {
            closeAddKabupatenModal();
        }

        if (event.target == editKabupatenModal) {
            closeEditKabupatenModal();
        }

        if (event.target == deleteKabupatenModal) {
            closeDeleteKabupatenModal();
        }
    });
</script>

@include('admin.layout.footer')