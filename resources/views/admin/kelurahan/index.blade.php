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
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.00691 9.10478L10.0069 3.77144C11.1436 2.76108 12.8564 2.76108 13.9931 3.77144L19.9931 9.10478C20.6336 9.67408 21 10.4901 21 11.347V21.2498H22C22.4142 21.2498 22.75 21.5856 22.75 21.9998C22.75 22.414 22.4142 22.7498 22 22.7498H2C1.58579 22.7498 1.25 22.414 1.25 21.9998C1.25 21.5856 1.58579 21.2498 2 21.2498H3V11.347C3 10.4901 3.36644 9.67408 4.00691 9.10478ZM9.25 8.99981C9.25 8.58559 9.58579 8.24981 10 8.24981H14C14.4142 8.24981 14.75 8.58559 14.75 8.99981C14.75 9.41402 14.4142 9.74981 14 9.74981H10C9.58579 9.74981 9.25 9.41402 9.25 8.99981ZM14.052 11.2498C14.9505 11.2498 15.6997 11.2498 16.2945 11.3297C16.9223 11.4141 17.4891 11.5998 17.9445 12.0553C18.4 12.5107 18.5857 13.0775 18.6701 13.7053C18.7501 14.3001 18.75 15.0493 18.75 15.9478L18.75 21.2498H17.25V15.9998C17.25 15.0358 17.2484 14.3882 17.1835 13.9052C17.1214 13.4437 17.0142 13.2462 16.8839 13.1159C16.7536 12.9856 16.5561 12.8784 16.0946 12.8163C15.6116 12.7514 14.964 12.7498 14 12.7498H10C9.03599 12.7498 8.38843 12.7514 7.90539 12.8163C7.44393 12.8784 7.24643 12.9856 7.11612 13.1159C6.9858 13.2462 6.87858 13.4437 6.81654 13.9052C6.75159 14.3882 6.75 15.0358 6.75 15.9998V21.2498H5.25L5.25 15.9478C5.24997 15.0493 5.24994 14.3001 5.32991 13.7053C5.41432 13.0775 5.59999 12.5107 6.05546 12.0553C6.51093 11.5998 7.07773 11.4141 7.70552 11.3297C8.3003 11.2498 9.04952 11.2498 9.948 11.2498H14.052ZM8.25 15.4998C8.25 15.0856 8.58579 14.7498 9 14.7498H15C15.4142 14.7498 15.75 15.0856 15.75 15.4998C15.75 15.914 15.4142 16.2498 15 16.2498H9C8.58579 16.2498 8.25 15.914 8.25 15.4998ZM8.25 18.4998C8.25 18.0856 8.58579 17.7498 9 17.7498H15C15.4142 17.7498 15.75 18.0856 15.75 18.4998C15.75 18.914 15.4142 19.2498 15 19.2498H9C8.58579 19.2498 8.25 18.914 8.25 18.4998Z" fill="currentColor"/>
            </svg>
            <span class="font-bold">Kelurahan</span>
        </div>

        <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile gap-y-5">
            <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                @include('components.dropdown-kabupaten', ['kabupaten' => $kabupaten, 'routeName' => 'kelurahan'])

                <form action="{{ route('kelurahan') }}" method="GET">
                    <div class="flex items-center border border-gray-300 rounded-lg bg-gray-100 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                        </svg>
                        <input type="search" placeholder="Cari kelurahan" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
                        @if (request()->has('kelurahan'))
                            <input type="hidden" name="kelurahan" value="{{ request()->get('kelurahan') }}">
                        @endif
                    </div>         
                </form>
            </div>

            <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                <div class="flex gap-2">
                    <button id="importKelurahanBtn" class="bg-[#58DA91] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-file-import me-1"></i>
                        <span>Impor</span>
                    </button>
                    <button id="exportKelurahanBtn" class="bg-[#EE3C46] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-file-export me-1"></i>
                        <span>Ekspor</span>
                    </button>
                </div>
                <button id="addKelurahanBtn" class="bg-[#0070FF] text-white py-2 px-4 rounded-lg w-full-mobile">
                    <i class="fas fa-plus me-1"></i>
                    <span>Tambah Kelurahan</span>
                </button>
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
@include('admin.kelurahan.impor-modal')
@include('admin.kelurahan.ekspor-modal')

<script>
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