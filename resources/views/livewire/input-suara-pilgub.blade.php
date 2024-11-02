<div>
    <div class="bg-white rounded-[20px] mb-8 shadow-lg">
        <div class="bg-white sticky top-20 p-4 z-10 rounded-t-[20px] shadow-lg">
            <div class="container mx-auto">
                <div class="flex flex-col gap-5 lg:flex-row lg:space-x-2 lg:items-center lg:justify-between">
                    {{-- Simpan, Batal Edit, dan Masuk Edit Mode --}}
                    @include('operator.pilgub.action-buttons')
                    
                    {{-- Cari dan Filter --}}
                    @include('operator.pilgub.search-filter')
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

                {{-- Loading --}}
                @include('operator.pilgub.loading-alert')
            </div>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg relative">
                    <!-- Loading Overlay -->
                    <div wire:loading.delay wire:target.except="applyFilter" class="absolute inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-10"></div>

                    <div class="px-4">
                        @include('operator.pilgub.table', compact('tps', 'paslon', 'includedColumns'))
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4 px-6">
            {{ $tps->links('vendor.livewire.simple', data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Filter Pilgub Modal -->
    @include('operator.pilgub.filter-modal', [
        'includedColumns' => $includedColumns,
        'partisipasi' => $partisipasi
    ])
</div>

@script
    <script type="text/javascript">
        console.log('Input Suara Pemilihan');

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

                    input.onkeyup = event => onChange(tpsId, cellDataset, event.target.value);

                    input.onkeydown = function(event) {
                        if (event.key == 'Tab') {
                            const inputs = Array.from(document.querySelectorAll('input[type=number]:not(.hidden)'));
                            
                            if (inputs.length) {
                                const firstInput = inputs[0];
                                const lastInput = inputs.pop();

                                if (event.target == lastInput) {
                                    event.preventDefault();
                                    firstInput.focus();
                                    firstInput.select();
                                }
                            }
                        }
                    }
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

        function focusToFirstEditableCellInput() {
            if (isEditMode()) {
                const firstInput = document.querySelector('input[type=number]:not(.hidden)');
                if (firstInput) {
                    firstInput.focus();
                    firstInput.select();
                }
            }
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
            focusToFirstEditableCellInput();

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