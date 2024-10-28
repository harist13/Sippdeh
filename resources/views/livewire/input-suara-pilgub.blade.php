<div class="bg-white rounded-[20px] p-4 mb-8 shadow-lg">
    <div class="container mx-auto p-7">
      <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between mb-4">
        {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
        <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-2 lg:order-1">
            <button class="bg-[#58DA91] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                <i class="fas fa-check mr-3"></i>
                Simpan Perubahan Data
            </button>
            <button class="bg-[#EE3C46] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                <i class="fas fa-times mr-3"></i>
                Batal Ubah Data
            </button>
            <button class="bg-[#0070FF] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto">
                <i class="fas fa-plus mr-3"></i>
                Ubah Data Tercentang
            </button>
        </div>

        {{-- Cari dan Filter --}}
        <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-1 lg:order-2">
            <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                </svg>
                <input type="search" placeholder="Cari" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="{{ request()->get('cari') }}">
            </div>
            <button class="flex items-center justify-center bg-[#ECEFF5] text-white text-sm font-medium px-4 py-2 rounded-lg sm:w-auto w-full" id="openFilterPilgub">
                <img src="{{ asset('assets/icon/filter-lines.png') }}" alt="Filter" class="w-4 h-4 mr-2">
                <span class="text-[#344054]">Filter</span>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto mb-5 -mx-4 sm:mx-0">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#3560A0] text-white">
                        <tr>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">NO</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">
                                <input type="checkbox" id="checkAll" class="form-checkbox h-5 w-5 text-white border-white rounded focus:ring-blue-500 focus:ring-2 checked:bg-blue-500 checked:border-blue-500 transition duration-200">
                            </th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Kecamatan</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Kelurahan</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">TPS</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 100px;">DPT</th>
                            @foreach ($paslon as $calon)
                                <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 300px;">
                                    {{-- Rahmad Mas'ud/<br>Bagus Susetyo --}}
                                    {{ $calon->nama }}/<br>{{ $calon->nama_wakil }}
                                </th>
                            @endforeach
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Calon</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Sah</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Tidak Sah</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Jumlah Pengguna<br>Tidak Pilih</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 200px;">Suara Masuk</th>
                            <th class="py-4 px-2 text-center font-semibold text-sm border border-white" style="min-width: 50px;">Partisipasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                        @forelse ($tps as $t)
                            <tr class="border-b text-center">
                                <td class="py-3 px-4 border nomor" data-id="{{ $t->id }}">
                                    {{ $t->getThreeDigitsId() }}
                                </td>
                                <td class="py-3 px-4 border centang" data-id="{{ $t->id }}">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                </td>
                                <td class="py-3 px-4 border kecamatan" data-kecamatan-id="{{ $t->kelurahan->kecamatan->id }}">
                                    {{ $t->kelurahan->kecamatan->nama }}
                                </td>
                                <td class="py-3 px-4 border kelurahan" data-kelurahan-id="{{ $t->kelurahan->id }}">
                                    {{ $t->kelurahan->nama }}
                                </td>
                                <td class="py-3 px-4 border tps">{{ $t->nama }}</td>
                                <td class="py-3 px-4 border dpt" data-value="{{ $t->suara ? $t->suara->dpt : 0 }}">
                                    {{ $t->suara ? $t->suara->dpt : 0 }}
                                </td>
                                @foreach ($paslon as $calon)
                                    <td class="py-3 px-4 border paslon" data-calon-id="{{ $calon->id }}">
                                        0
                                    </td>
                                @endforeach
                                <td class="py-3 px-4 border posisi">
                                    Gubernur/<br>Wakil Gubernur
                                </td>
                                <td class="py-3 px-4 border suara-sah">
                                    0
                                </td>
                                <td class="py-3 px-4 border suara-tidak-sah" data-value="{{ $t->suara ? $t->suara->suara_tidak_sah : 0 }}">
                                    {{ $t->suara ? $t->suara->suara_tidak_sah : 0 }}
                                </td>
                                <td class="py-3 px-4 border jumlah-pengguna-tidak-pilih">
                                    0
                                </td>
                                <td class="py-3 px-4 border suara-masuk">
                                    0
                                </td>
                                <td class="text-center py-3 px-4 border partisipasi">
                                    <span class="bg-green-400 text-white py-1 px-7 rounded text-xs">90%</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center p-6" colspan="100">Belum ada TPS.</td>
                            </tr>
                        @endforelse
                        {{-- <tr class="border-b text-center">
                            <td class="py-3 px-4 border">02</td>
                            <td class="py-3 px-4 border">
                                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                            </td>
                            <td class="py-3 px-4 border">
                                <p>Samarinda Kota</p>
                            </td>
                            <td class="py-3 px-4 border">Palaran</td>
                            <td class="py-3 px-4 border">2370750016-TPS 016</td>
                            <td class="py-3 px-4 border">
                                <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none">
                            </td>
                            <td class="py-3 px-4 border">
                                <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none">
                            </td>
                            <td class="py-3 px-4 border">
                                <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none">
                            </td>
                            <td class="py-3 px-4 border">Gubernur/<br>Wakil Gubernur</td>
                            <td class="py-3 px-4 border">
                                123
                            </td>
                            <td class="py-3 px-4 border">
                                <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none">
                            </td>
                            <td class="py-3 px-4 border">
                                123
                            </td>
                            <td class="py-3 px-4 border">
                                123
                            </td>
                            <td class="py-3 px-4 border text-center">
                                <span class="bg-green-400 text-white py-1 px-7 rounded text-xs">90%</span>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $tps->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
</div>

@script
    <script>
        class Paslon {
            constructor(id, suara) {
                this.id = id;
                this.suara = suara;
            }

            // Convert Paslon instance to plain object
            toObject() {
                return { id: this.id, suara: this.suara };
            }
        }

        class TPS {
            constructor(id, suaraTidakSah) {
                this.id = id;
                this.paslonList = [];
                this.suaraTidakSah = suaraTidakSah;
            }

            addPaslon(paslon) {
                this.paslonList.push(paslon);
            }

            get dpt() {
                return 0;
            }

            get suaraSah() {
                return 0;
            }

            get jumlahPenggunaTidakPilih() {
                return 0;
            }

            get suaraMasuk() {
                return 0;
            }

            get partisipasi() {
                return 0;
            }

            // Convert TPS instance to plain object for saving in localStorage
            toObject() {
                return {
                    id: this.id,
                    dpt: this.dpt,
                    paslonList: this.paslonList.map(p => p instanceof Paslon ? p.toObject() : p), // Convert each Paslon to object
                    suara_sah: this.suaraSah,
                    suara_tidak_sah: this.suaraTidakSah,
                    jumlah_pengguna_tidak_pilih: this.jumlahPenggunaTidakPilih,
                    suara_masuk: this.suaraMasuk,
                    partisipasi: this.partisipasi
                };
            }

            // Static method to retrieve all TPS data from localStorage
            static getAllTPS() {
                try {
                    const data = JSON.parse(localStorage.getItem('tps_data')) || [];
                    return Array.isArray(data) ? data.map(item => TPS.fromObject(item)) : [];
                } catch {
                    return [];
                }
            }

            // Method to save the current TPS instance to localStorage
            save() {
                if (TPS.exists(this.id)) {
                    return;
                }
                
                const data = TPS.getAllTPS();
                data.push(this);
                localStorage.setItem('tps_data', JSON.stringify(data.map(tps => tps.toObject())));
            }

            // Static method to create a TPS instance from plain object
            static fromObject(obj) {
                return new TPS(
                    obj.id,
                    obj.dpt,
                    obj.paslonList,
                    obj.suara_sah,
                    obj.suara_tidak_sah,
                    obj.jumlah_pengguna_tidak_pilih,
                    obj.suara_masuk,
                    obj.partisipasi
                );
            }

            // Static method to update an existing TPS by `id`
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

            // Static method to delete a TPS by `id`
            static delete(id) {
                const data = TPS.getAllTPS();
                const updatedData = data.filter(item => item.id !== id);
                localStorage.setItem('tps_data', JSON.stringify(updatedData.map(tps => tps.toObject())));
            }

            // Static method to retrieve a TPS by `id`
            static getById(id) {
                const data = TPS.getAllTPS();
                return data.find(item => item.id === id) || null;
            }

            // Static method to check if a TPS with the given `id` exists
            static exists(id) {
                return TPS.getAllTPS().some(item => item.id === id);
            }
        }

        const checksCheckAllCheckboxes = () => document.getElementById('checkAll').checked = true;

        const unchecksCheckAllCheckboxes = () => document.getElementById('checkAll').checked = false;

        const syncCheckedCheckboxes = () => {
            checksCheckAllCheckboxes();

            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(checkbox => {
                    const isChecked = TPS.exists(checkbox.parentElement.dataset.id);
                    checkbox.checked = isChecked;

                    if (!isChecked) unchecksCheckAllCheckboxes();
                });
        };

        function onCheckAllCheckboxesChange() {
            const isCheckAll = this.checked;
            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(checkbox => {
                    checkbox.checked = isCheckAll;
                    checkbox.dispatchEvent(new Event('change'));
                });
        }

        const addTPS = (id, suaraTidakSah) => {
            const tps = new TPS(id, parseInt(suaraTidakSah));
            tps.save();
        };

        const onCheckboxChange = event => {
            const checkbox = event.target;
            const tpsId = checkbox.parentElement.dataset.id;
            
            if (checkbox.checked) {
                const tr = checkbox.parentElement.parentElement;
                const suaraTidakSah = tr.querySelector('.suara-tidak-sah').dataset.value;

                addTPS(tpsId, suaraTidakSah);
            } else {
                TPS.delete(tpsId);
            }

            syncCheckedCheckboxes();
        };

        const onPageChange = () => {
            syncCheckedCheckboxes();
        };

        syncCheckedCheckboxes();

        document.getElementById('checkAll')
            .addEventListener('change', onCheckAllCheckboxesChange);

        document.querySelectorAll('.centang input[type=checkbox]')
            .forEach(checkbox => checkbox.addEventListener('change', onCheckboxChange))

        Livewire.hook('morph.updated', onPageChange);
    </script>
@endscript