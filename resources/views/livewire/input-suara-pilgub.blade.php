<div class="bg-white rounded-[20px] p-4 mb-8 shadow-lg">
    <div class="container mx-auto p-7">
      <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between mb-4">
        {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
        <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-2 lg:order-1">
            <button class="bg-[#58DA91] disabled:bg-[#58da906c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="simpanPerubahanData">
                <i class="fas fa-check mr-3"></i>
                Simpan Perubahan Data
            </button>
            <button class="bg-[#EE3C46] disabled:bg-[#EE3C406c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="batalUbahData">
                <i class="fas fa-times mr-3"></i>
                Batal Ubah Data
            </button>
            <button class="bg-[#0070FF] disabled:bg-[#0070F06c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="ubahDataTercentang">
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
                            <tr class="border-b text-center tps" data-id="{{ $t->id }}">
                                {{-- ID TPS --}}
                                <td class="py-3 px-4 border nomor" data-id="{{ $t->id }}">
                                    {{ $t->getThreeDigitsId() }}
                                </td>

                                {{-- Checkbox --}}
                                <td class="py-3 px-4 border centang" data-id="{{ $t->id }}">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                                </td>

                                {{-- Kecamatan --}}
                                <td class="py-3 px-4 border kecamatan" data-kecamatan-id="{{ $t->kelurahan->kecamatan->id }}">
                                    {{ $t->kelurahan->kecamatan->nama }}
                                </td>

                                {{-- Kelurahan --}}
                                <td class="py-3 px-4 border kelurahan" data-kelurahan-id="{{ $t->kelurahan->id }}">
                                    {{ $t->kelurahan->nama }}
                                </td>

                                {{-- Nama TPS --}}
                                <td class="py-3 px-4 border tps">{{ $t->nama }}</td>

                                {{-- DPT --}}
                                <td class="py-3 px-4 border dpt" data-value="{{ $t->suara ? $t->suara->dpt() : 0 }}">
                                    {{ $t->suara ? $t->suara->dpt : 0 }}
                                </td>

                                {{-- Calon-calon --}}
                                @foreach ($t->suaraCalon as $suaraCalon)
                                    <td class="py-3 px-4 border paslon" data-id="{{ $suaraCalon->id }}" data-calon-id="{{ $suaraCalon->calon->id }}">
                                        {{ $suaraCalon->suara }}
                                    </td>
                                @endforeach

                                {{-- Posisi --}}
                                <td class="py-3 px-4 border posisi">
                                    Gubernur/<br>Wakil Gubernur
                                </td>

                                {{-- Suara Sah --}}
                                <td class="py-3 px-4 border suara-sah" data-value="{{ $t->suara ? $t->suara->suaraSah() : 0 }}">
                                    {{ $t->suara ? $t->suara->suaraSah() : 0 }}
                                </td>

                                {{-- Suara Tidak Sah (Editable) --}}
                                <td class="py-3 px-4 border suara-tidak-sah" data-value="{{ $t->suara ? $t->suara->suara_tidak_sah : 0 }}">
                                    <span class="value hidden">{{ $t->suara ? $t->suara->suara_tidak_sah : 0 }}</span>
                                    <input type="number" placeholder="Jumlah" class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none hidden">
                                </td>

                                {{-- Jumlah Pengguna yang Tidak Pilih --}}
                                <td class="py-3 px-4 border jumlah-pengguna-tidak-pilih" data-value="{{ $t->suara ? $t->suara->jumlahPenggunaTidakPilih() : 0 }}">
                                    {{ $t->suara ? $t->suara->jumlahPenggunaTidakPilih() : 0 }}
                                </td>

                                {{-- Suara Masuk --}}
                                <td class="py-3 px-4 border suara-masuk" data-value="{{ $t->suara ? $t->suara->suaraMasuk() : 0 }}">
                                    {{ $t->suara ? $t->suara->suaraMasuk() : 0 }}
                                </td>

                                {{-- Partisipasi --}}
                                <td class="text-center py-3 px-4 border partisipasi" data-value="{{ $t->suara ? $t->suara->partisipasi() : 0 }}">
                                    <span class="bg-green-400 text-white py-1 px-7 rounded text-xs">{{ $t->suara ? $t->suara->partisipasi() : 0 }}%</span>
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

    {{ $tps->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
</div>

@script
    <script>
        class Paslon {
            constructor(id, suara) {
                this.id = id;
                this.suara = suara;
            }

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
                return this.suaraTidakSah;
            }

            get suaraSah() {
                return this.suaraTidakSah;
            }

            get jumlahPenggunaTidakPilih() {
                return this.suaraTidakSah;
            }

            get suaraMasuk() {
                return this.suaraTidakSah;
            }

            get partisipasi() {
                return this.suaraTidakSah;
            }

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

            static fromObject(obj) {
                return new TPS(
                    obj.id,
                    obj.suara_tidak_sah
                );
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

            static syncCalculations() {
                document.querySelectorAll('tr.tps').forEach(tpsRow => {
                    const tpsId = tpsRow.querySelector('td.nomor').dataset.id;
                    const tps = TPS.getById(tpsId);

                    if (tps instanceof TPS) {
                        const dptCell = tpsRow.querySelector('td.dpt');
                        dptCell.dataset.value = tps.dpt;
                        dptCell.textContent = tps.dpt;
    
                        const suaraSahCell = tpsRow.querySelector('td.suara-sah');
                        suaraSahCell.dataset.value = tps.suaraSah;
                        suaraSahCell.textContent = tps.suaraSah;
    
                        const jumlahPenggunaTidakPilihRow = tpsRow.querySelector('td.jumlah-pengguna-tidak-pilih');
                        jumlahPenggunaTidakPilihRow.dataset.value = tps.jumlahPenggunaTidakPilih;
                        jumlahPenggunaTidakPilihRow.textContent = tps.jumlahPenggunaTidakPilih;

                        const suaraMasukCell = tpsRow.querySelector('td.suara-masuk');
                        suaraMasukCell.dataset.value = tps.suaraMasuk;
                        suaraMasukCell.textContent = tps.suaraMasuk;

                        const partisipasiCell = tpsRow.querySelector('td.partisipasi span');
                        partisipasiCell.dataset.value = tps.partisipasi;
                        partisipasiCell.textContent = `${tps.partisipasi}%`;
                    }
                });
            }
        }

        const checksCheckAllCheckboxes = () => document.getElementById('checkAll').checked = true;
        const unchecksCheckAllCheckboxes = () => document.getElementById('checkAll').checked = false;

        function syncCheckboxes() {
            checksCheckAllCheckboxes();

            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(function(checkbox) {
                    const isChecked = TPS.exists(checkbox.parentElement.dataset.id);
                    checkbox.checked = isChecked;

                    if (!isChecked) {
                        unchecksCheckAllCheckboxes();
                    }
                });
        };

        function onCheckAllCheckboxesChange() {
            const isCheckAll = this.checked;
            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(function(checkbox) {
                    checkbox.checked = isCheckAll;
                    checkbox.dispatchEvent(new Event('change'));
                });
        }

        function addTPS(id, suaraTidakSah) {
            const tps = new TPS(id, parseInt(suaraTidakSah));
            tps.save();
        }

        function onCheckboxChange(event) {
            const checkbox = event.target;
            const tpsId = checkbox.parentElement.dataset.id;
            
            if (checkbox.checked) {
                const row = checkbox.parentElement.parentElement;
                const suaraTidakSah = row.querySelector('td.suara-tidak-sah').dataset.value;

                addTPS(tpsId, suaraTidakSah);
            } else {
                TPS.delete(tpsId);
            }

            syncCheckboxes();
        }

        const enableEditModeState = () => localStorage.setItem('is_edit_mode', '1');
        const cancelEditModeState = () => localStorage.removeItem('is_edit_mode');

        function isEditMode() {
            const isIt = localStorage.getItem('is_edit_mode') || 0;
            return isIt == '1';
        }

        const enableSaveChangeButton = () => document.getElementById('simpanPerubahanData').disabled = false;
        const disableSaveChangeButton = () => document.getElementById('simpanPerubahanData').disabled = true;

        const enableCancelChangeButton = () => document.getElementById('batalUbahData').disabled = false;
        const disableCancelChangeButton = () => document.getElementById('batalUbahData').disabled = true;

        const enableEnterEditModeButton = () => document.getElementById('ubahDataTercentang').disabled = false;
        const disableEnterEditModeButton = () => document.getElementById('ubahDataTercentang').disabled = true;

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

        function setUIToEditMode() {
            // Set the action buttons
            enableSaveChangeButton();
            enableCancelChangeButton();
            disableEnterEditModeButton();

            // Set checkboxes and each row
            disableCheckboxes();
            setEachRowMode();
        }

        function setUIToReadMode() {
            // Set the action buttons
            disableSaveChangeButton();
            disableCancelChangeButton();
            enableEnterEditModeButton();

            // Set checkboxes and each row
            enableCheckboxes();
            setEachRowMode();
        }

        function setCellMode(tpsRow, cellQuery) {
            const tpsId = tpsRow.querySelector('td.nomor').dataset.id;

            const cell = tpsRow.querySelector(cellQuery);
            const value = cell.querySelector('span');
            const input = cell.querySelector('input');

            input.onchange = function(event) {
                TPS.update(tpsId, { suaraTidakSah: event.target.value });
                TPS.syncCalculations();
            };

            if (isEditMode() && TPS.exists(tpsId)) {
                // Change to input
                value.classList.add('hidden');
                input.classList.remove('hidden');
            } else {
                // Change to value
                value.classList.remove('hidden');
                input.classList.add('hidden');
            }
        }

        function setEachRowMode() {
            document.querySelectorAll('tr.tps').forEach(tpsRow => {
                setCellMode(tpsRow, 'td.suara-tidak-sah');
            });
        }

        function syncUI() {
            syncCheckboxes();
            
            if (isEditMode()) {
                setUIToEditMode();
            } else {
                setUIToReadMode();
            }

            TPS.syncCalculations();
        };

        function onLivewireUpdated() {
            syncUI();
        }

        function onInitialPageLoad() {
            TPS.deleteAll();
            cancelEditModeState();
            onLivewireUpdated();
        }

        function preventReloadPage(event) {
            if (isEditMode()) {
                // Cancel the event as stated by the standard.
                event.preventDefault();
                // Chrome requires returnValue to be set.
                event.returnValue = '';
            }
        }

        function onEnterEditModeButtonClick() {
            enableEditModeState();
            $wire.$refresh();
        }

        function resetEditableCells() {
            document.querySelectorAll('tr.tps').forEach(function(tpsRow) {
                tpsRow.querySelectorAll('input').forEach(function(input) {
                    input.value = '';
                    input.classList.add('hidden');
                });

                tpsRow.querySelectorAll('span.value').forEach(function(spanValue) {
                    spanValue.classList.remove('hidden');
                });
            });
        }

        function onCancelEditModeButtonClick() {
            if (isEditMode()) {
                if (confirm('Yakin ingin batalkan pengeditan?')) {
                    onInitialPageLoad();
                    resetEditableCells();
                    $wire.$refresh();
                }
            }
        }

        onInitialPageLoad();

        Livewire.hook('morph.updated', onLivewireUpdated);
        
        document.getElementById('checkAll')
            .addEventListener('change', onCheckAllCheckboxesChange);

        document.querySelectorAll('.centang input[type=checkbox]')
            .forEach(checkbox => checkbox.addEventListener('change', onCheckboxChange));

        document.getElementById('batalUbahData')
            .addEventListener('click', onCancelEditModeButtonClick);
        
        document.getElementById('ubahDataTercentang')
            .addEventListener('click', onEnterEditModeButtonClick);

        window.addEventListener('beforeunload', preventReloadPage);
    </script>
@endscript