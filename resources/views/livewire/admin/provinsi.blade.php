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
            <img src="{{ asset('assets/icon/provinsi.svg') }}" class="mr-1" alt="Provinsi">
            <span class="font-bold">Provinsi</span>
        </div>

        <div class="flex flex-col-mobile justify-between items-center mb-4 space-y-2-mobile gap-y-5">
            <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                <div class="relative w-[300px] w-full-mobile">
                    <select wire:model.live="kabupatenId" class="bg-gray-100 w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-lg flex items-center justify-between w-full-mobile">
                        <option value="0" {{ $kabupatenId == 0 ? 'selected' : '' }}>
                            Semua
                        </option>

                        @foreach ($kabupaten as $kab)
                            <option value="{{ $kab->id }}" {{ $kabupatenId == $kab->id ? 'selected' : '' }}>
                                {{ $kab->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>                

                <div class="flex items-center border border-gray-300 rounded-lg bg-gray-100 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                    </svg>
                    <input wire:model.live.debounce.250ms="keyword" type="search" placeholder="Cari provinsi" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ $keyword }}">
                </div>
            </div>

            <div class="flex flex-col-mobile gap-x-2 space-y-2-mobile w-full-mobile">
                <div class="flex gap-2">
                    <button id="importProvinsiBtn" class="bg-[#58DA91] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-file-import me-1"></i>
                        <span>Impor</span>
                    </button>
                    <button id="exportProvinsiBtn" class="bg-[#EE3C46] text-white py-2 px-4 rounded-lg w-full-mobile">
                        <i class="fas fa-file-export me-1"></i>
                        <span>Ekspor</span>
                    </button>
                </div>
                <button id="addProvinsiBtn" class="bg-[#0070FF] text-white py-2 px-4 rounded-lg w-full-mobile">
                    <i class="fas fa-plus me-1"></i>
                    <span>Tambah Provinsi</span>
                </button>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto relative mb-5">
            <!-- Loading Overlay -->
            <div wire:loading.delay class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

            <table class="min-w-full leading-normal text-sm-mobile">
                <thead>
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">
                            ID
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">
                            Nama
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-white">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @forelse ($provinsi as $prov)
                        <tr wire:key="{{ $prov->id }}" class="hover:bg-gray-200">
                            <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r" data-id="{{ $prov->id }}">{{ $prov->getThreeDigitsId() }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">{{ $prov->nama }}</td>
                            <td class="px-4 py-4 border-b border-gray-200 text-center text-sm-mobile border-r">
                                <button class="edit-provinsi-btn text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900 hapus-provinsi-btn">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-gray-200 text-center">
                            <td class="py-5" colspan="4">
                                @if ($keyword)
                                    <p>Tidak ada data provinsi dengan kata kunci "{{ $keyword }}"</p>
                                @else
                                    <p>Belum ada data provinsi</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $provinsi->links('vendor.livewire.simple') }}
        </div>
    </div>

    @include('admin.provinsi.tambah-modal')
    @include('admin.provinsi.edit-modal')
    @include('admin.provinsi.hapus-modal')
    @include('admin.provinsi.ekspor-modal')
    @include('admin.provinsi.impor-modal')
</main>

@script
    <script>
        // Tutup modal saat tombol esc di tekan
        document.addEventListener('keyup', function(event) {
            if(event.key === "Escape") {
                closeAddProvinsiModal();
                closeEditProvinsiModal();
                closeDeleteProvinsiModal();
                closeImportProvinsiModal();
                closeExportProvinsiModal();
            }
        });

        // Tutup modal saat overlay diklik
        document.addEventListener('click', function(event) {
            if (event.target == addProvinsiModal) {
                closeAddProvinsiModal();
            }

            if (event.target == editProvinsiModal) {
                closeEditProvinsiModal();
            }

            if (event.target == deleteProvinsiModal) {
                closeDeleteProvinsiModal();
            }

            if (event.target == importProvinsiModal) {
                closeImportProvinsiModal();
            }

            if (event.target == exportProvinsiModal) {
                closeExportProvinsiModal();
            }
        });
    </script>
@endscript