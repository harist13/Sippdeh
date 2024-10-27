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
    @php $status = session('pesan_sukses'); @endphp
    @isset ($status)
        @include('components.alert-berhasil', ['message' => $status])
    @endisset

    @php $status = session('pesan_gagal'); @endphp
    @isset ($status)
        @include('components.alert-gagal', ['message' => $status])
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
            <img src="{{ asset('assets/icon/pasangan_calon.svg') }}" alt="Calon">
            <span class="font-bold">Calon</span>
        </div>

        <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile gap-y-5">
            <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                @include('components.dropdown-kabupaten', ['kabupaten' => $kabupaten, 'routeName' => 'calon'])

                <form action="{{ route('calon') }}" method="GET">
                    <div class="flex items-center border border-gray-300 rounded-lg bg-gray-100 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                        </svg>
                        <input type="search" placeholder="Cari calon" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
                        @if (request()->has('kabupaten'))
                            <input type="hidden" name="kabupaten" value="{{ request()->get('kabupaten') }}">
                        @endif
                    </div>         
                </form>
            </div>

            <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                <div class="flex gap-2">
                    <button id="importCalonBtn" class="bg-[#58DA91] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-file-import me-1"></i>
                        <span>Impor</span>
                    </button>
                    <button id="exportCalonBtn" class="bg-[#EE3C46] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-file-export me-1"></i>
                        <span>Ekspor</span>
                    </button>
                </div>
                <button id="addCalonBtn" class="bg-[#0070FF] text-white py-2 px-4 rounded-lg w-full-mobile">
                    <i class="fas fa-plus me-1"></i>
                    <span>Tambah Calon</span>
                </button>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto mb-5">
            <table class="min-w-full leading-normal text-sm-mobile">
                <thead>
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Pasangan Calon</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Menjabat Sebagai</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Provinsi atau Kabupaten/Kota</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Foto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @forelse ($calon as $cal)
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">{{ $cal->getThreeDigitsId() }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile" data-id="{{ $cal->id }}" data-nama="{{ $cal->nama }}" data-nama-wakil="{{ $cal->nama_wakil }}">{{ $cal->nama }}/{{ $cal->nama_wakil }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile capitalize" data-posisi="{{ $cal->posisi }}">{{ strtolower($cal->posisi) }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile capitalize" data-provinsi-id="{{ $cal->provinsi?->id }}" data-kabupaten-id="{{ $cal->kabupaten?->id }}">{{ strtolower($cal->posisi == 'GUBERNUR' ? $cal->provinsi->nama : $cal->kabupaten->nama) }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile flex items-start">
                                @if ($cal->foto != null)
                                    <img src="{{ $disk->url($cal->foto) }}" class="rounded-md mr-1" width="150" height="75" alt="{{ $cal->nama }}">
                                @else
                                    Gambar belum diunggah
                                @endif
                            </td>
                            <td class="px-4 py-4 border-b border-gray-200 text-sm-mobile">
                                <div class="flex items-center">
                                    <button class="text-[#3560A0] hover:text-blue-900 edit-calon-btn"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900 ml-4 hapus-calon-btn"><i class="fas fa-trash-alt"></i></button>
                                    @if ($cal->foto != null)
                                        <button class="text-red-600 hover:text-red-900 ml-4 pt-1 hapus-gambar-calon-btn" data-url="{{ $disk->url($cal->foto) }}">
                                            <img src="{{ asset('assets/icon/delete_image.svg') }}" alt="Hapus Gambar">
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-gray-200 text-center">
                            <td class="py-5" colspan="5">
                                @if (request()->has('cari'))
                                    <p>Tidak ada data calon dengan kata kunci "{{ request()->get('cari') }}"</p>
                                @else
                                    <p>Belum ada data calon</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $calon->links('vendor.pagination.tailwind', ['namaModel' => 'calon']) }}
    </div>
</main>

@include('admin.calon.tambah-modal')
@include('admin.calon.edit-modal')
@include('admin.calon.hapus-modal')
@include('admin.calon.hapus-gambar-modal')

<script>
    // Tutup modal saat tombol esc di tekan
    document.addEventListener('keyup', function(event) {
        if (event.key === "Escape") {
            closeAddCalonModal();
            closeEditCalonModal();
            closeDeleteCalonModal();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target == addCalonModal) {
            closeAddCalonModal();
        }

        if (event.target == editCalonModal) {
            closeEditCalonModal();
        }

        if (event.target == deleteCalonModal) {
            closeDeleteCalonModal();
        }
    });
</script>

@include('admin.layout.footer')