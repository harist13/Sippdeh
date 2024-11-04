@extends('admin.layout.app')

@push('styles')
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
@endpush

@section('content')
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
                <img src="{{ asset('assets/icon/tps.svg') }}" class="mr-1" alt="TPS">
                <span class="font-bold">TPS</span>
            </div>

            <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile gap-y-5">
                <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                    @include('components.dropdown-kabupaten', ['kabupaten' => $kabupaten, 'routeName' => 'tps'])

                    <form action="{{ route('tps') }}" method="GET">
                        <div class="flex items-center border border-gray-300 rounded-lg bg-gray-100 px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                            </svg>
                            <input type="search" placeholder="Cari TPS" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
                            @if (request()->has('kabupaten'))
                                <input type="hidden" name="kabupaten" value="{{ request()->get('kabupaten') }}">
                            @endif
                        </div>         
                    </form>
                </div>

                <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                    <div class="flex gap-2">
                        <button id="importTPSBtn" class="bg-[#58DA91] text-white py-2 px-4 rounded-lg w-full-mobile">
                            <i class="fas fa-file-import me-1"></i>
                            <span>Impor</span>
                        </button>
                        <button id="exportTPSBtn" class="bg-[#EE3C46] text-white py-2 px-4 rounded-lg w-full-mobile">
                            <i class="fas fa-file-export me-1"></i>
                            <span>Ekspor</span>
                        </button>
                    </div>
                    <button id="addTPSBtn" class="bg-[#0070FF] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-plus me-1"></i>
                        <span>Tambah TPS</span>
                    </button>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto mb-5">
                <table class="min-w-full leading-normal text-sm-mobile">
                    <thead>
                        <tr class="bg-[#3560A0] text-white">
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">ID</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">Kabupaten/Kota</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">Kecamatan</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">Kelurahan</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">TPS</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100">
                        @forelse ($tps as $data)
                            <tr class="hover:bg-gray-200">
                                <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">{{ $data->getThreeDigitsId() }}</td>
                                <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">{{ $data->kelurahan->kecamatan->kabupaten->nama }}</td>
                                <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r" data-id="{{ $data->kelurahan->kecamatan->id }}">{{ $data->kelurahan->kecamatan->nama }}</td>
                                <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">{{ $data->kelurahan->nama }}</td>
                                <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r" data-id="{{ $data->id }}" data-nama="{{ $data->nama }}">{{ $data->nama }}</td>
                                <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">
                                    <button class="text-[#3560A0] hover:text-blue-900 edit-tps-btn"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900 ml-3 hapus-tps-btn"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr class="hover:bg-gray-200 text-center">
                                <td class="py-5" colspan="6">
                                    @if (request()->has('cari'))
                                        <p>Tidak ada data TPS dengan kata kunci "{{ request()->get('cari') }}"</p>
                                    @else
                                        <p>Belum ada data TPS</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $tps->links('vendor.pagination.custom') }}
            </div>
        </div>
    </main>

    @include('admin.tps.tambah-modal')
    @include('admin.tps.edit-modal')
    @include('admin.tps.hapus-modal')
    @include('admin.tps.ekspor-modal')
    @include('admin.tps.impor-modal')
@endsection

@push('scripts')
    <script>
        // Tutup modal saat tombol esc di tekan
        document.addEventListener('keyup', function(event) {
            if(event.key === "Escape") {
                closeAddTPSModal();
                closeEditTPSModal();
                closeDeleteTPSModal();
                closeImportTPSModal();
                closeExportTPSModal();
            }
        });

        // Tutup modal saat overlay diklik
        document.addEventListener('click', function(event) {
            if (event.target == addTPSModal) {
                closeAddTPSModal();
            }

            if (event.target == editTPSModal) {
                closeEditTPSModal();
            }

            if (event.target == deleteTPSModal) {
                closeDeleteTPSModal();
            }

            if (event.target == importTPSModal) {
                closeImportTPSModal();
            }

            if (event.target == exportTPSModal) {
                closeExportTPSModal();
            }
        });
    </script>
@endpush