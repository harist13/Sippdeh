<div>
    @php $status = session('pesan_sukses'); @endphp
    @isset ($status)
        @include('components.alert-berhasil', ['message' => $status])
    @endisset

    @php $status = session('pesan_gagal'); @endphp
    @isset ($status)
        @include('components.alert-gagal', ['message' => $status])
    @endisset

    <div class="bg-white rounded-[20px] p-4 mb-8 shadow-lg">
        <div class="container mx-auto p-7">
        <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between mb-4">
            {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
            <div class="flex flex-col space-y-2 sm:space-y-0 sm:space-x-2 sm:flex-row sm:items-center order-2 lg:order-1">
                <button class="bg-[#58DA91] disabled:bg-[#58da906c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="simpanPerubahanData" wire:loading.attr="disabled">
                    <i class="fas fa-check mr-3"></i>
                    Simpan Perubahan Data
                </button>
                <button class="bg-[#EE3C46] disabled:bg-[#EE3C406c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="batalUbahData" wire:loading.attr="disabled">
                    <i class="fas fa-times mr-3"></i>
                    Batal Ubah Data
                </button>
                <button class="bg-[#0070FF] disabled:bg-[#0070F06c] text-white py-2 px-5 rounded-lg flex items-center justify-center text-sm font-medium w-full sm:w-auto" id="ubahDataTercentang" wire:loading.attr="disabled">
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
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg relative">
                    <!-- Loading Overlay -->
                    <div wire:loading.delay class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10">
                        <div class="text-blue-600 text-lg font-semibold">Loading...</div>
                    </div>

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
                                        class="py-3 px-4 border kecamatan"
                                        data-kecamatan-id="{{ $t->kelurahan->kecamatan->id }}"
                                    >
                                        {{ $t->kelurahan->kecamatan->nama }}
                                    </td>

                                    {{-- Kelurahan --}}
                                    <td
                                        class="py-3 px-4 border kelurahan"
                                        data-kelurahan-id="{{ $t->kelurahan->id }}"
                                    >
                                        {{ $t->kelurahan->nama }}
                                    </td>

                                    {{-- Nama TPS --}}
                                    <td class="py-3 px-4 border tps">{{ $t->nama }}</td>

                                    {{-- DPT --}}
                                    <td
                                        class="py-3 px-4 border dpt"
                                        data-value="{{ $t->suara ? $t->suara->dpt() : 0 }}"
                                    >
                                        {{ $t->suara ? $t->suara->dpt() : 0 }}
                                    </td>

                                    {{-- Calon-calon --}}
                                    @foreach ($paslon as $calon)
                                        @php
                                            $suaraCalon = $t->suaraCalonByCalonId($calon->id)->first();
                                            $suara = $suaraCalon != null ? $suaraCalon->suara : 0;
                                        @endphp

                                        <td
                                            class="py-3 px-4 border paslon"
                                            data-id="{{ $calon->id }}"
                                            data-suara="{{ $suara }}"
                                        >
                                            <span class="value hidden">{{ $suara }}</span>
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
                                    <td class="py-3 px-4 border posisi">
                                        Gubernur/<br>Wakil Gubernur
                                    </td>

                                    {{-- Suara Sah --}}
                                    <td
                                        class="py-3 px-4 border suara-sah"
                                        data-value="{{ $t->suara ? $t->suara->suaraSah() : 0 }}"
                                    >
                                        {{ $t->suara ? $t->suara->suaraSah() : 0 }}
                                    </td>

                                    {{-- Suara Tidak Sah (Editable) --}}
                                    <td
                                        class="py-3 px-4 border suara-tidak-sah"
                                        data-value="{{ $t->suara ? $t->suara->suara_tidak_sah : 0 }}"
                                    >
                                        <span class="value hidden">{{ $t->suara ? $t->suara->suara_tidak_sah : 0 }}</span>
                                        <input
                                            type="number"
                                            placeholder="Jumlah"
                                            class="bg-[#ECEFF5] text-gray-600 border border-gray-600 rounded-lg ml-2 px-4 py-2 w-28 focus:outline-none hidden"
                                            data-default-value="{{ $t->suara ? $t->suara->suara_tidak_sah : '' }}"
                                            data-value="{{ $t->suara ? $t->suara->suara_tidak_sah : '' }}"
                                        >
                                    </td>

                                    {{-- Jumlah Pengguna yang Tidak Pilih --}}
                                    <td
                                        class="py-3 px-4 border jumlah-pengguna-tidak-pilih"
                                        data-value="{{ $t->suara ? $t->suara->jumlahPenggunaTidakPilih() : 0 }}"
                                    >
                                        {{ $t->suara ? $t->suara->jumlahPenggunaTidakPilih() : 0 }}
                                    </td>

                                    {{-- Suara Masuk --}}
                                    <td
                                        class="py-3 px-4 border suara-masuk"
                                        data-value="{{ $t->suara ? $t->suara->suaraMasuk() : 0 }}"
                                    >
                                        {{ $t->suara ? $t->suara->suaraMasuk() : 0 }}
                                    </td>

                                    {{-- Partisipasi --}}
                                    <td
                                        class="text-center py-3 px-4 border partisipasi"
                                        data-value="{{ $t->suara ? $t->suara->partisipasi() : 0 }}"
                                    >
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
</div>

@script
    <script type="text/javascript">
        class MyClass {}

        class TPS {
            constructor(id, suaraTidakSah) {
                this.id = id;
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

        function isEditMode() {
            const isIt = localStorage.getItem('is_edit_mode') || 0;
            return isIt == '1';
        }
        
        function addTPSToLocalStorage(id, suaraCalon, suaraTidakSah) {
            const tps = new TPS(id, parseInt(suaraTidakSah));
    
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
                const cellDataset = cell.dataset;
                const value = cell.querySelector('span');
                const input = cell.querySelector('input');
    
                input.onchange = () => onChange(tpsId, cellDataset, event.target.value);
    
                if (isEditMode() && TPS.exists(tpsId)) {
                    // Change to input
                    value.classList.add('hidden');
                    input.classList.remove('hidden');
                } else {
                    // Change to value
                    value.classList.remove('hidden');
                    input.classList.add('hidden');
                }
            });
        }

        function syncRowsMode() {
            document.querySelectorAll('tr.tps').forEach(tpsRow => {
                syncEditableCellMode({
                    tpsRow,
                    cellQuery: 'td.suara-tidak-sah',
                    onChange: function(tpsId, cellDataset, value) {
                        TPS.update(tpsId, { suaraTidakSah: event.target.value });
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
            document.querySelectorAll('tr.tps').forEach(tpsRow => {
                const tpsId = tpsRow.querySelector('td.nomor').dataset.id;
                const tps = TPS.getById(tpsId);

                if (tps instanceof TPS) {
                    const dptCell = tpsRow.querySelector('td.dpt');
                    dptCell.dataset.value = tps.dpt;
                    dptCell.textContent = tps.dpt;

                    tps.suaraCalon.forEach(function(sc) {
                        const suaraCalonValue = tpsRow.querySelector(`td.paslon[data-id="${sc.id}"] span`);
                        suaraCalonValue.value = sc.suara;

                        const suaraCalonInput = tpsRow.querySelector(`td.paslon[data-id="${sc.id}"] input`);
                        suaraCalonInput.value = sc.suara;
                    });

                    const suaraSahCell = tpsRow.querySelector('td.suara-sah');
                    suaraSahCell.dataset.value = tps.suaraSah;
                    suaraSahCell.textContent = tps.suaraSah;

                    const suaraTidakSahCell = tpsRow.querySelector('td.suara-tidak-sah');
                    suaraTidakSahCell.dataset.value = tps.suaraTidakSah;
                    suaraTidakSahCell.querySelector('span').textContent = tps.suaraTidakSah;
                    
                    const suaraTidakSahInput = suaraTidakSahCell.querySelector('input');
                    suaraTidakSahInput.value = tps.suaraTidakSah;

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

            syncEnterEditModeButtonState();
        }

        function onSubmitClick() {
            if (confirm('Simpan perubahan data?')) {
                const data = TPS.getAllTPS().map(tps => tps.toObject());
                $wire.dispatch('submit', { data });
            }
        }

        function onCancelEditModeButtonClick() {
            if (isEditMode()) {
                if (confirm('Yakin ingin batalkan pengeditan?')) {
                    resetToInitialState();
                    $wire.$refresh();
                }
            }
        }

        function onEnterEditModeButtonClick() {
            enableEditModeState();
            $wire.$refresh();
        }

        function onCheckAllCheckboxesChange() {
            const isCheckAll = this.checked;
            document.querySelectorAll('.centang input[type=checkbox]')
                .forEach(function(checkbox) {
                    checkbox.checked = isCheckAll;
                    checkbox.dispatchEvent(new Event('change'));
                });
        }

        function onCheckboxChange(event) {
            const checkbox = event.target;
            const tpsId = checkbox.parentElement.dataset.id;
            
            if (checkbox.checked) {
                const row = checkbox.parentElement.parentElement;
                const suaraTidakSah = row.querySelector('td.suara-tidak-sah').dataset.value;
                const suaraCalon = Array.from(row.querySelectorAll('td.paslon'))
                    .map(suara => ({
                        id: suara.dataset.id,
                        suara: suara.dataset.suara
                    }));

                addTPSToLocalStorage(tpsId, suaraCalon, suaraTidakSah);
            } else {
                TPS.delete(tpsId);
            }

            syncCheckboxesWithSelectedTPS();
        }

        function resetEditableCellsInput() {
            document.querySelectorAll('tr.tps').forEach(function(tpsRow) {
                tpsRow.querySelectorAll('input[type=number]').forEach(function(input) {
                    if (input.dataset.defaultValue) {
                        input.value = input.dataset.defaultValue;
                    } else {
                        input.value = '';
                    }

                    // input.classList.add('hidden');
                });

                // tpsRow.querySelectorAll('span.value').forEach(function(spanValue) {
                //     spanValue.classList.remove('hidden');
                // });
            });
        }

        function onDataStored({ status }) {
            if (status == 'sukses') {
                resetToInitialState();
            }
        }

        function resetToInitialState() {
            setTimeout(function() {
                TPS.deleteAll();

                cancelEditModeState();

                disableSubmitButton();
                disableCancelEditButton();
                syncEnterEditModeButtonState();

                syncRowsMode();
            }, 200);
        }

        function refreshState() {
            setTimeout(function() {
                resetEditableCellsInput();

                syncActionButtons();

                syncCheckboxesWithSelectedTPS();
                syncCheckboxesState();

                syncTableDataWithSelectedTPS();
                syncRowsMode();
            }, 200);
        }

        function onLivewireUpdated() {
            refreshState();
        }

        function onUnloadPage(event) {
            if (isEditMode()) {
                // Cancel the event as stated by the standard.
                event.preventDefault();
                // Chrome requires returnValue to be set.
                event.returnValue = '';
            }
        }

        document.getElementById('simpanPerubahanData')
            .addEventListener('click', onSubmitClick)

        document.getElementById('batalUbahData')
            .addEventListener('click', onCancelEditModeButtonClick);
        
        document.getElementById('ubahDataTercentang')
            .addEventListener('click', onEnterEditModeButtonClick);
        
        document.getElementById('checkAll')
            .addEventListener('change', onCheckAllCheckboxesChange);

        document.querySelectorAll('.centang input[type=checkbox]')
            .forEach(checkbox => checkbox.addEventListener('change', onCheckboxChange));

        window.addEventListener('beforeunload', onUnloadPage);

        $wire.on('data-stored', onDataStored);

        Livewire.hook('morph.updated', onLivewireUpdated);

        resetToInitialState();
    </script>
@endscript