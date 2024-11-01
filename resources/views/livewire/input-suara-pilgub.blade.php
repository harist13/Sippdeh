<div>
    <div class="bg-white rounded-[20px] mb-8 shadow-lg">
        <div class="bg-white sticky top-20 p-4 z-10 rounded-t-[20px] shadow-lg">
            <div class="container mx-auto">
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
                    <style>
                        /* Tooltip styling */
                        .tooltip:hover .absolute {
                            display: block;
                        }
                    </style>
                    <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-2 lg:order-1">
                        <!-- Save Changes Button -->
                        <div class="flex items-center mr-7">
                            <button class="bg-[#58DA91] disabled:bg-[#58da906c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="simpanPerubahanData" wire:loading.attr="disabled" wire:target.except="applyFilter">
                                <i class="fas fa-check mr-3"></i>
                                Simpan Perubahan Data
                            </button>
                            <span class="tooltip relative cursor-pointer text-sm text-gray-500 ml-3">
                                <i class="fas fa-question-circle"></i>
                                <span class="absolute top-full left-0 mt-1 w-max px-2 py-1 bg-gray-800 text-white rounded shadow-md text-xs hidden group-hover:block">
                                    Bisa juga dengan menekan "Ctrl + Enter" atau "Ctrl + S"
                                </span>
                            </span>
                        </div>
                    
                        <!-- Cancel Edit Button -->
                        <div class="flex items-center !mr-7">
                            <button class="bg-[#EE3C46] disabled:bg-[#EE3C406c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="batalUbahData" wire:loading.attr="disabled" wire:target.except="applyFilter">
                                <i class="fas fa-times mr-3"></i>
                                Batal Ubah Data
                            </button>
                            <span class="tooltip relative cursor-pointer text-sm text-gray-500 ml-3">
                                <i class="fas fa-question-circle"></i>
                                <span class="absolute top-full left-0 mt-1 w-max px-2 py-1 bg-gray-800 text-white rounded shadow-md text-xs hidden group-hover:block">
                                    Bisa juga dengan menekan tombol "Esc"
                                </span>
                            </span>
                        </div>
                    
                        <!-- Edit Selected Button -->
                        <div class="flex items-center">
                            <button class="bg-[#0070FF] disabled:bg-[#0070F06c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="ubahDataTercentang" wire:loading.attr="disabled" wire:target.except="applyFilter">
                                <i class="fas fa-edit mr-3"></i>
                                Ubah Data Tercentang
                            </button>
                            <span class="tooltip relative cursor-pointer text-sm text-gray-500 ml-3">
                                <i class="fas fa-question-circle"></i>
                                <span class="absolute top-full left-0 mt-1 w-max px-2 py-1 bg-gray-800 text-white rounded shadow-md text-xs hidden group-hover:block">
                                    Bisa juga dengan menekan tombol "Ctrl + U"
                                </span>
                            </span>
                        </div>
                    </div>         
    
                    {{-- Cari dan Filter --}}
                    <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-1 lg:order-2">
                        <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                            </svg>
                            <input wire:model.live.debounce.250ms="keyword" type="search" placeholder="Cari" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
                        </div>
                        <button class="flex items-center justify-center bg-[#ECEFF5] text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full" id="openFilterPilgub">
                            <img src="{{ asset('assets/icon/filter-lines.png') }}" alt="Filter" class="w-4 h-4 mr-2">
                            <span class="text-[#344054]">Filter</span>
                        </button>
                    </div>
                </div>
                
                @php $status = session('pesan_sukses'); @endphp
                @isset ($status)
                    <div class="mt-3">
                        @include('components.alert-berhasil', ['message' => $status, 'withoutMarginBottom' => true])
                    </div>
                @endisset

                @php $status = session('pesan_gagal'); @endphp
                @isset ($status)
                    <div class="mt-3">
                        @include('components.alert-gagal', ['message' => $status])
                    </div>
                @endisset

                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative items-center hidden mt-3" id="loading" role="alert">
                    <svg class="animate-spin h-5 w-5 mr-3 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <strong class="font-bold">Mohon tunggu:</strong>
                    <span class="block sm:inline ml-2">Sedang menyimpan data...</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg relative">
                    <!-- Loading Overlay -->
                    <div wire:loading.delay wire:target.except="applyFilter" class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

                    <div class="px-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#3560A0] text-white">
                                <tr>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">NO</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">
                                        <input type="checkbox" id="checkAll" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
                                    </th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('KECAMATAN', $ignoredColumns) ? 'hidden' : '' }}" style="min-width: 200px;">Kecamatan</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('KELURAHAN', $ignoredColumns) ? 'hidden' : '' }}" style="min-width: 200px;">Kelurahan</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('TPS', $ignoredColumns) ? 'hidden' : '' }}" style="min-width: 200px;">TPS</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 100px;">DPT</th>
                                    @foreach ($paslon as $calon)
                                        <th wire:key="{{ $calon->id }}" class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('CALON', $ignoredColumns) ? 'hidden' : '' }}" style="min-width: 300px;">
                                            {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                                        </th>
                                    @endforeach
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white {{ !in_array('CALON', $ignoredColumns) ? 'hidden' : '' }}" style="min-width: 200px;">Calon</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Sah</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Tidak Sah</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Jumlah Pengguna<br>Tidak Pilih</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Masuk</th>
                                    <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">Partisipasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                                @forelse ($tps as $t)
                                    <tr wire:key="{{ $t->id }}" class="border-b text-center tps" data-id="{{ $t->id }}">
                                        {{-- ID TPS --}}
                                        <td
                                            class="py-3 px-4 border nomor"
                                            data-id="{{ $t->id }}"
                                        >
                                            {{ $t->getThreeDigitsId() }}
                                        </td>
    
                                        {{-- Checkbox --}}
                                        <td
                                            class="py-3 px-4 border centang"
                                            data-id="{{ $t->id }}"
                                        >
                                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                        </td>
    
                                        {{-- Kecamatan --}}
                                        <td
                                            class="py-3 px-4 border kecamatan {{ !in_array('KECAMATAN', $ignoredColumns) ? 'hidden' : '' }}"
                                            data-kecamatan-id="{{ $t->tps->kelurahan->kecamatan->id }}"
                                        >
                                            {{ $t->tps->kelurahan->kecamatan->nama }}
                                        </td>
    
                                        {{-- Kelurahan --}}
                                        <td
                                            class="py-3 px-4 border kelurahan {{ !in_array('KELURAHAN', $ignoredColumns) ? 'hidden' : '' }}"
                                            data-kelurahan-id="{{ $t->tps->kelurahan->id }}"
                                        >
                                            {{ $t->tps->kelurahan->nama }}
                                        </td>
    
                                        {{-- Nama TPS --}}
                                        <td class="py-3 px-4 border tps {{ !in_array('TPS', $ignoredColumns) ? 'hidden' : '' }}">{{ $t->nama }}</td>
    
                                        {{-- DPT --}}
                                        <td
                                            class="py-3 px-4 border dpt"
                                            data-value="{{ $t->dpt }}"
                                        >
                                            <span class="value">{{ $t->dpt }}</span>
                                            <input
                                                type="number"
                                                placeholder="Jumlah"
                                                class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none hidden"
                                                data-default-value="{{ $t->dpt }}"
                                                data-value="{{ $t->dpt }}"
                                            >
                                        </td>
    
                                        {{-- Calon-calon --}}
                                        @foreach ($paslon as $calon)
                                            @php
                                                $suaraCalon = $t->suaraCalonByCalonId($calon->id)->first();
                                                $suara = $suaraCalon != null ? $suaraCalon->suara : 0;
                                            @endphp
    
                                            <td
                                                wire:key="{{ $t->id }}{{ $calon->id }}"
                                                class="py-3 px-4 border paslon {{ !in_array('CALON', $ignoredColumns) ? 'hidden' : '' }}"
                                                data-id="{{ $calon->id }}"
                                                data-suara="{{ $suara }}"
                                            >
                                                <span class="value">{{ $suara }}</span>
                                                <input
                                                    type="number"
                                                    placeholder="Jumlah"
                                                    class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none hidden"
                                                    value="{{ $suara }}"
                                                    data-default-value="{{ $suara }}"
                                                    autocomplete="off"
                                                >
                                            </td>
                                        @endforeach
    
                                        {{-- Posisi --}}
                                        <td class="py-3 px-4 border posisi {{ !in_array('CALON', $ignoredColumns) ? 'hidden' : '' }}">
                                            Gubernur/<br>Wakil Gubernur
                                        </td>
    
                                        {{-- Suara Sah --}}
                                        <td
                                            class="py-3 px-4 border suara-sah"
                                            data-value="{{ $t->suara_sah }}"
                                        >
                                            {{ $t->suara_sah }}
                                        </td>
    
                                        {{-- Suara Tidak Sah (Editable) --}}
                                        <td
                                            class="py-3 px-4 border suara-tidak-sah"
                                            data-value="{{ $t->suara_tidak_sah }}"
                                        >
                                            <span class="value">{{ $t->suara_tidak_sah }}</span>
                                            <input
                                                type="number"
                                                placeholder="Jumlah"
                                                class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none hidden"
                                                data-default-value="{{ $t->suara_tidak_sah }}"
                                                data-value="{{ $t->suara_tidak_sah }}"
                                            >
                                        </td>
    
                                        {{-- Jumlah Pengguna yang Tidak Pilih --}}
                                        <td
                                            class="py-3 px-4 border jumlah-pengguna-tidak-pilih"
                                            data-value="{{ $t->jumlah_pengguna_tidak_pilih }}"
                                        >
                                            {{ $t->jumlah_pengguna_tidak_pilih }}
                                        </td>
    
                                        {{-- Suara Masuk --}}
                                        <td
                                            class="py-3 px-4 border suara-masuk"
                                            data-value="{{ $t->suara_masuk }}"
                                        >
                                            {{ $t->suara_masuk }}
                                        </td>
    
                                        {{-- Partisipasi --}}
                                        @php
                                            $partisipasi = $t->partisipasi;
                                        @endphp
                                        <td
                                            class="text-center py-3 px-4 border partisipasi"
                                            data-value="{{ $partisipasi }}"
                                        >
                                            @if ($partisipasi <= 100 && $partisipasi >= 80)
                                                <span class="bg-green-400 block text-white py-1 px-7 rounded text-xs">
                                                    {{ $partisipasi }}%
                                                </span>
                                            @endif

                                            @if ($partisipasi < 80 && $partisipasi >= 60)
                                                <span class="bg-yellow-400 block text-white py-1 px-7 rounded text-xs">
                                                    {{ $partisipasi }}%
                                                </span>
                                            @endif

                                            @if ($partisipasi < 60)
                                                <span class="bg-red-400 block text-white py-1 px-7 rounded text-xs">
                                                    {{ $partisipasi }}%
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center p-6" colspan="100">Belum ada TPS.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4 px-6">
            {{ $tps->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Filter Pilgub Modal -->
    <div id="filterPilgubModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-20 hidden">
        <style>
            /* Add blue border to span when checkbox is checked */
            input[type="checkbox"]:checked + span {
                border-color: #3b82f6;
            }
        </style>

        <div class="relative inset-y-1/2 -translate-y-1/2 mx-auto px-5 py-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex items-center mb-5">
                <i class="fas fa-arrow-left mr-3 select-none cursor-pointer" id="cancelFilterPilgub"></i>
                <h3 class="text-lg font-medium text-gray-900">Filter</h3>
            </div>

            {{-- Kolom --}}
            <label for="pilihKolom" class="mb-3 font-bold mt-5 block">Kolom</label>
            <ul class="flex flex-col gap-2">
                <li class="flex items-center gap-2 w-full">
                    <label class="flex items-center gap-3" for="pilihKecamatan">
                        <input type="checkbox" id="pilihKecamatan" value="KECAMATAN" wire:model="ignoredColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                        <span class="cursor-pointer select-none">Kecamatan</span>
                    </label>
                </li>
                <li class="flex items-center gap-2 w-full">
                    <label class="flex items-center gap-3" for="pilihKelurahan">
                        <input type="checkbox" id="pilihKelurahan" value="KELURAHAN" wire:model="ignoredColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                        <span class="cursor-pointer select-none">Kelurahan</span>
                    </label>
                </li>
                <li class="flex items-center gap-2 w-full">
                    <label class="flex items-center gap-3" for="pilihTPS">
                        <input type="checkbox" id="pilihTPS" value="TPS" wire:model="ignoredColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                        <span class="cursor-pointer select-none">TPS</span>
                    </label>
                </li>
                <li class="flex items-center gap-2 w-full">
                    <label class="flex items-center gap-3" for="pilihCalon">
                        <input type="checkbox" id="pilihCalon" value="CALON" wire:model="ignoredColumns" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200 cursor-pointer">
                        <span class="cursor-pointer select-none">Calon</span>
                    </label>
                </li>
            </ul>
            {{-- <span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span> --}}

            {{-- Tingkat Partisipasi --}}
            <label for="pilihTingkatPartisipasi" class="mb-3 font-bold mt-5 block">Tingkat Partisipasi</label>
            <div class="flex gap-2">
                <label for="hijau" class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="hidden" id="hijau" value="HIJAU" wire:model="partisipasi" />
                    <span class="bg-green-400 text-white py-2 px-7 rounded text-sm select-none border-2">> 80%</span>
                </label>
                <label for="kuning" class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="hidden" id="kuning" value="KUNING" wire:model="partisipasi" />
                    <span class="bg-yellow-400 text-white py-2 px-7 rounded text-sm select-none border-2">> 60%</span>
                </label>
                <label for="merah" class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="hidden" id="merah" value="MERAH" wire:model="partisipasi" />
                    <span class="bg-red-400 text-white py-2 px-7 rounded text-sm select-none border-2">< 20%</span>
                </label>
            </div>
            {{-- <span class="text-red-800">{{ $errors->first('kabupaten_id') }}</span> --}}

            <hr class="h-1 my-3">

            <div class="flex">
                <button type="button" wire:loading.attr="disabled" wire:target="resetFilter" wire:click="resetFilter" class="flex-1 bg-gray-300 disabled:bg-[#d1d5d06c] hover:bg-gray-400 text-black rounded-md px-4 py-2 mr-2">
                    Reset
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="applyFilter" wire:click="applyFilter" id="applyFilterPilgub" class="flex-1 bg-[#3560A0] disabled:bg-[#0070F06c] hover:bg-blue-700 text-white rounded-md px-4 py-2">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

@script
    <script type="text/javascript">
        class A {}

        class TPS {
            constructor(id, dpt, suaraTidakSah) {
                this.id = id;
                this.dpt = dpt;
                this.suaraCalon = [];
                this.suaraTidakSah = suaraTidakSah;
            }

            addSuaraCalon(id, suara) {
                this.suaraCalon.push({ id, suara });
            }

            static updateSuaraCalon(tpsId, calonId, newSuara) {
                // Get all TPS data from LocalStorage
                const data = JSON.parse(localStorage.getItem('tps_data')) || [];

                // Find the TPS object by tpsId
                const tpsIndex = data.findIndex(tps => tps.id === tpsId);
                if (tpsIndex === -1) {
                    console.error(`TPS with id ${tpsId} not found.`);
                    return;
                }

                // Find the specific suara_calon within the TPS object
                const calonIndex = data[tpsIndex].suara_calon.findIndex(calon => calon.id === calonId);
                if (calonIndex === -1) {
                    console.error(`Calon with id ${calonId} not found in TPS ${tpsId}.`);
                    return;
                }

                // Update the suara value
                data[tpsIndex].suara_calon[calonIndex].suara = newSuara;

                // Save the updated data back to LocalStorage
                localStorage.setItem('tps_data', JSON.stringify(data));
                console.log(`Updated calon id ${calonId} in TPS ${tpsId} with new suara: ${newSuara}`);
            }

            get suaraSah() {
                return this.suaraCalon.reduce(function(acc, sc) {
                    return acc + sc.suara;
                }, 0);
            }

            get jumlahPenggunaTidakPilih() {
                return this.dpt - this.suaraMasuk;
            }

            get suaraMasuk() {
                return this.suaraSah + this.suaraTidakSah;
            }

            get partisipasi() {
                if (this.dpt == 0) {
                    return 0;
                }

                return parseFloat(((this.suaraMasuk / this.dpt) * 100).toFixed(1));
            }

            toObject() {
                return {
                    id: this.id,
                    dpt: this.dpt,
                    suara_calon: this.suaraCalon,
                    suara_sah: this.suaraSah,
                    suara_tidak_sah: this.suaraTidakSah,
                    jumlah_pengguna_tidak_pilih: this.jumlahPenggunaTidakPilih,
                    suara_masuk: this.suaraMasuk,
                    partisipasi: this.partisipasi
                };
            }

            static fromObject(obj) {
                const tps = new TPS(
                    obj.id,
                    obj.dpt,
                    obj.suara_tidak_sah
                );

                obj.suara_calon.forEach(sc => tps.addSuaraCalon(sc.id, parseInt(sc.suara)));

                return tps;
            }

            static getAllTPS() {
                try {
                    const data = JSON.parse(localStorage.getItem('tps_data')) || [];
                    return Array.isArray(data) ? data.map(item => TPS.fromObject(item)) : [];
                } catch {
                    return [];
                }
            }

            save() {
                if (TPS.exists(this.id)) {
                    return;
                }
                
                const data = TPS.getAllTPS();
                data.push(this);
                localStorage.setItem('tps_data', JSON.stringify(data.map(tps => tps.toObject())));
            }

            static update(id, updatedData) {
                const data = TPS.getAllTPS();
                const index = data.findIndex(item => item.id === id);

                if (index !== -1) {
                    Object.assign(data[index], updatedData);
                    localStorage.setItem('tps_data', JSON.stringify(data.map(tps => tps.toObject())));
                } else {
                    console.error(`TPS dengan id ${id} tidak ditemukan.`);
                }
            }

            static delete(id) {
                const data = TPS.getAllTPS();
                const updatedData = data.filter(item => item.id !== id);
                localStorage.setItem('tps_data', JSON.stringify(updatedData.map(tps => tps.toObject())));
            }

            static deleteAll() {
                localStorage.removeItem('tps_data');
            }

            static getById(id) {
                const data = TPS.getAllTPS();
                const tps = data.find(item => item.id === id) || null;

                if (tps == null) {
                    return null;
                }

                return tps;
            }

            static exists(id) {
                return TPS.getAllTPS().some(item => item.id === id);
            }
        }

        const checksCheckAllCheckboxes = () => document.getElementById('checkAll').checked = true;
        const unchecksCheckAllCheckboxes = () => document.getElementById('checkAll').checked = false;

        const enableEditModeState = () => localStorage.setItem('is_edit_mode', '1');
        const cancelEditModeState = () => localStorage.removeItem('is_edit_mode');

        const enableSubmitButton = () => document.getElementById('simpanPerubahanData').disabled = false;
        const disableSubmitButton = () => document.getElementById('simpanPerubahanData').disabled = true;

        const enableCancelEditButton = () => document.getElementById('batalUbahData').disabled = false;
        const disableCancelEditButton = () => document.getElementById('batalUbahData').disabled = true;

        const enableEnterEditModeButton = () => document.getElementById('ubahDataTercentang').disabled = false;
        const disableEnterEditModeButton = () => document.getElementById('ubahDataTercentang').disabled = true;

        function showLoadingMessage() {
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('loading').classList.add('flex');
        }

        function hideLoadingMessage() {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('loading').classList.remove('flex');
        }
        
        function isEditMode() {
            const isIt = localStorage.getItem('is_edit_mode') || 0;
            return isIt == '1';
        }
        
        function addTPSToLocalStorage(id, dpt, suaraCalon, suaraTidakSah) {
            const tps = new TPS(id, parseInt(dpt), parseInt(suaraTidakSah));
    
            // Add suaraCalon after the TPS object is instantiated
            suaraCalon.forEach(sc => tps.addSuaraCalon(sc.id, parseInt(sc.suara)));
            
            // Save after all data is added to the TPS instance
            tps.save();
        }
        
        function enableCheckboxes() {
            document.getElementById('checkAll').disabled = false;
            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(checkbox => checkbox.disabled = false);
        }

        function disableCheckboxes() {
            document.getElementById('checkAll').disabled = true;
            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(checkbox => checkbox.disabled = true);
        }

        function syncEditableCellMode({ tpsRow, cellQuery, onChange }) {
            const rowDataset = tpsRow.querySelector('td.nomor').dataset;
            const tpsId = rowDataset.id;
            
            tpsRow.querySelectorAll(cellQuery).forEach(function(cell) {
                const value = cell.querySelector('span');
                const input = cell.querySelector('input');
                
                if (isEditMode() && TPS.exists(tpsId)) {
                    // Change to input
                    value.classList.add('hidden');
                    input.classList.remove('hidden');

                    const cellDataset = cell.dataset;
                    input.onkeyup = () => onChange(tpsId, cellDataset, event.target.value);
                } else {
                    // Change to value
                    value.classList.remove('hidden');
                    input.classList.add('hidden');
                }
            });
        }

        function syncTableMode() {
            document.querySelectorAll('tr.tps').forEach(tpsRow => {
                syncEditableCellMode({
                    tpsRow,
                    cellQuery: 'td.dpt',
                    onChange: function(tpsId, cellDataset, value) {
                        TPS.update(tpsId, { dpt: parseInt(event.target.value) });
                        syncTableDataWithSelectedTPS();
                    }
                });

                syncEditableCellMode({
                    tpsRow,
                    cellQuery: 'td.suara-tidak-sah',
                    onChange: function(tpsId, cellDataset, value) {
                        TPS.update(tpsId, { suaraTidakSah: parseInt(event.target.value) });
                        syncTableDataWithSelectedTPS();
                    }
                });

                syncEditableCellMode({
                    tpsRow,
                    cellQuery: 'td.paslon',
                    onChange: function(tpsId, cellDataset, value) {
                        const calonId = cellDataset.id;
                        TPS.updateSuaraCalon(tpsId, calonId, value);
                        syncTableDataWithSelectedTPS();
                    }
                });
            });
        }

        function syncActionButtons() {
            if (isEditMode()) {
                // Set the action buttons
                enableSubmitButton();
                enableCancelEditButton();
                syncEnterEditModeButtonState();
            } else {
                // Set the action buttons
                disableSubmitButton();
                disableCancelEditButton();
                syncEnterEditModeButtonState();
            }
        }

        function syncCheckboxesState() {
            if (isEditMode()) {
                disableCheckboxes();
            } else {
                enableCheckboxes();
            }
        }

        function syncTableDataWithSelectedTPS() {
            if (isEditMode()) {
                document.querySelectorAll('tr.tps').forEach(tpsRow => {
                    const tpsId = tpsRow.querySelector('td.nomor').dataset.id;
                    const tps = TPS.getById(tpsId);
    
                    if (tps instanceof TPS) {
                        const dptCell = tpsRow.querySelector('td.dpt');
                        dptCell.dataset.value = tps.dpt;
                        dptCell.querySelector('span').textContent = tps.dpt;
    
                        tps.suaraCalon.forEach(function(sc) {
                            const suaraCalonCell = tpsRow.querySelector(`td.paslon[data-id="${sc.id}"]`);
                            const suaraCalonValue = suaraCalonCell.querySelector('span');
                            suaraCalonValue.value = sc.suara;
                        });
    
                        const suaraSahCell = tpsRow.querySelector('td.suara-sah');
                        suaraSahCell.dataset.value = tps.suaraSah;
                        suaraSahCell.textContent = tps.suaraSah;
    
                        const suaraTidakSahCell = tpsRow.querySelector('td.suara-tidak-sah');
                        suaraTidakSahCell.dataset.value = tps.suaraTidakSah;
                        suaraTidakSahCell.querySelector('span').textContent = tps.suaraTidakSah;
    
                        const jumlahPenggunaTidakPilihRow = tpsRow.querySelector('td.jumlah-pengguna-tidak-pilih');
                        jumlahPenggunaTidakPilihRow.dataset.value = tps.jumlahPenggunaTidakPilih;
                        jumlahPenggunaTidakPilihRow.textContent = tps.jumlahPenggunaTidakPilih;
    
                        const suaraMasukCell = tpsRow.querySelector('td.suara-masuk');
                        suaraMasukCell.dataset.value = tps.suaraMasuk;
                        suaraMasukCell.textContent = tps.suaraMasuk;

                        const partisipasiCell = tpsRow.querySelector('td.partisipasi span');

                        partisipasiCell.dataset.value = tps.partisipasi;
                        partisipasiCell.textContent = `${tps.partisipasi}%`;

                        if (tps.partisipasi <= 100 && tps.partisipasi >= 80) {
                            partisipasiCell.classList.add('bg-green-400');
                            partisipasiCell.classList.remove('bg-yellow-400');
                            partisipasiCell.classList.remove('bg-red-400');
                        }

                        if (tps.partisipasi < 80 && tps.partisipasi >= 60) {
                            partisipasiCell.classList.remove('bg-green-400');
                            partisipasiCell.classList.add('bg-yellow-400');
                            partisipasiCell.classList.remove('bg-red-400');
                        }

                        if (tps.partisipasi < 60) {
                            partisipasiCell.classList.remove('bg-green-400');
                            partisipasiCell.classList.remove('bg-yellow-400');
                            partisipasiCell.classList.add('bg-red-400');
                        }
                    }
                });
            }
        }

        function syncTableInputWithSelectedTPS() {
            if (isEditMode()) {
                document.querySelectorAll('tr.tps').forEach(tpsRow => {
                    const tpsId = tpsRow.querySelector('td.nomor').dataset.id;
                    const tps = TPS.getById(tpsId);
    
                    if (tps instanceof TPS) {
                        const dptCell = tpsRow.querySelector('td.dpt');
                        const dptInput = dptCell.querySelector('input');
                        dptInput.value = tps.dpt;

                        tps.suaraCalon.forEach(function(sc) {
                            const suaraCalonCell = tpsRow.querySelector(`td.paslon[data-id="${sc.id}"]`);
                            const suaraCalonInput = suaraCalonCell.querySelector('input');
                            suaraCalonInput.value = sc.suara;
                        });
                        
                        const suaraTidakSahCell = tpsRow.querySelector('td.suara-tidak-sah');
                        const suaraTidakSahInput = suaraTidakSahCell.querySelector('input');
                        suaraTidakSahInput.value = tps.suaraTidakSah;
                    }
                });
            }
        }

        function syncEnterEditModeButtonState() {
            const checkedCheckboxesCount = Array.from(document.querySelectorAll('.centang input[type=checkbox]'))
                .filter(checkbox => checkbox.checked)
                .length;

            if (checkedCheckboxesCount >= 1 && !isEditMode()) {
                enableEnterEditModeButton();
            } else {
                disableEnterEditModeButton();
            }
        }

        function syncCheckboxesWithSelectedTPS() {
            checksCheckAllCheckboxes();

            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(function(checkbox) {
                    const isChecked = TPS.exists(checkbox.parentElement.dataset.id);
                    checkbox.checked = isChecked;

                    if (!isChecked) {
                        unchecksCheckAllCheckboxes();
                    }
                });
        }

        function onSubmitClick() {
            if (isEditMode() && confirm('Simpan perubahan data?')) {
                showLoadingMessage();

                const data = TPS.getAllTPS().map(tps => tps.toObject());
                $wire.dispatch('submit-tps', { data });
            }
        }

        function onCancelEditModeButtonClick() {
            if (isEditMode() && confirm('Yakin ingin batalkan pengeditan?')) {
                cancelEditModeState();
                refreshState();
            }
        }

        function onEnterEditModeButtonClick() {
            enableEditModeState();
            refreshState();
        }

        function onCheckAllCheckboxesChange() {
            const isCheckAll = this.checked;
            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(function(checkbox) {
                    checkbox.checked = isCheckAll;
                    checkbox.dispatchEvent(new Event('click'));
                });
        }

        function onCheckboxClick(event) {
            const checkbox = event.target;
            const tpsId = checkbox.parentElement.dataset.id;
            
            if (checkbox.checked) {
                const row = checkbox.parentElement.parentElement;
                
                const dpt = row.querySelector('td.dpt').dataset.value;
                const suaraTidakSah = row.querySelector('td.suara-tidak-sah').dataset.value;
                const suaraCalon = Array.from(row.querySelectorAll('td.paslon'))
                    .map(suara => ({
                        id: suara.dataset.id,
                        suara: suara.dataset.suara
                    }));

                addTPSToLocalStorage(tpsId, dpt, suaraCalon, suaraTidakSah);
            } else {
                TPS.delete(tpsId);
            }

            syncCheckboxesWithSelectedTPS();
            syncActionButtons();
        }

        function resetTableInput() {
            document.querySelectorAll('tr.tps').forEach(function(tpsRow) {
                tpsRow.querySelectorAll('input[type=number]').forEach(function(input) {
                    if (input.dataset.defaultValue) {
                        input.value = input.dataset.defaultValue;
                    } else {
                        input.value = '';
                    }
                });
            });
        }

        function setToInitialState() {
            TPS.deleteAll();
            cancelEditModeState();
            refreshState();
        }

        function refreshState() {
            resetTableInput();

            syncActionButtons();

            syncCheckboxesWithSelectedTPS();
            syncCheckboxesState();

            syncTableDataWithSelectedTPS();
            syncTableInputWithSelectedTPS();

            syncTableMode();

            attachEventToInteractableComponents();
        }

        function onLivewireUpdated() {
            refreshState();
        }

        function onDataStored({ status }) {
            if (status == 'sukses') {
                setToInitialState();
                hideLoadingMessage();
            }
        }

        function onUnloadPage(event) {
            if (isEditMode()) {
                // Cancel the event as stated by the standard.
                event.preventDefault();
                // Chrome requires returnValue to be set.
                event.returnValue = '';
            }
        }

        function attachEventToInteractableComponents() {
            document.getElementById('simpanPerubahanData').onclick = onSubmitClick;

            document.getElementById('batalUbahData').onclick = onCancelEditModeButtonClick;
            
            document.getElementById('ubahDataTercentang').onclick = onEnterEditModeButtonClick;
            
            document.getElementById('checkAll').onchange = onCheckAllCheckboxesChange;

            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(checkbox => checkbox.onclick = onCheckboxClick);

            window.onbeforeunload = onUnloadPage;

            document.body.onkeydown = function(event) {
                if ((event.key === "Enter" || event.key === "s") && event.ctrlKey) {
                    event.preventDefault();
                    onSubmitClick();
                }

                
                if (event.key === "u" && event.ctrlKey) {
                    event.preventDefault();

                    const checkedCheckboxesCount = Array.from(document.querySelectorAll('.centang input[type=checkbox]'))
                        .filter(checkbox => checkbox.checked)
                        .length;
                    
                    if (checkedCheckboxesCount > 0) {
                        onEnterEditModeButtonClick();
                    }
                }

                if (event.key === "Escape") {
                    if (isEditMode()) {
                        onCancelEditModeButtonClick();
                    }
                }
            };
        }

        function initializeHooks() {
            $wire.on('data-stored', onDataStored);

            let timeoutId = null;
            Livewire.hook('morph.updated', ({ component, el }) => {
                clearTimeout(timeoutId);
    
                timeoutId = setTimeout(function() {
                    onLivewireUpdated();
                    timeoutId = null;
                }, 0);
            });
        }

        function initializeFilter() {
            document.getElementById('openFilterPilgub').addEventListener('click', showFilterPilgubModal);
            document.getElementById('cancelFilterPilgub').addEventListener('click', closeFilterPilgubModal);

            document.addEventListener('keyup', function(event) {
                if (event.key === "Escape") {
                    closeFilterPilgubModal();
                }
            });

            document.addEventListener('click', function(event) {
                if (event.target == filterPilgubModal) {
                    closeFilterPilgubModal();
                }
            });
        }

        function showFilterPilgubModal() {
            const filterPilgubModal = document.getElementById('filterPilgubModal');
            filterPilgubModal.classList.remove('hidden');
        }

        function closeFilterPilgubModal() {
            const filterPilgubModal = document.getElementById('filterPilgubModal');
            filterPilgubModal.classList.add('hidden');
        }

        function onApplyFilter() {
            $wire.$refresh();
        }

        setToInitialState();
        initializeHooks();
        initializeFilter();
    </script>
@endscript