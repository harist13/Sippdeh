class TPS {
    constructor(id, dpt, suaraSah, suaraTidakSah, oldSuaraCalon = 0) {
        this.id = id;
        this.dpt = dpt;
        this.suaraCalon = [];
        this.suaraSah = suaraSah;
        this.suaraTidakSah = suaraTidakSah;
        this.oldSuaraCalon = oldSuaraCalon;
    }

    addSuaraCalon(id, suara, setOldSuara = true) {
        this.suaraCalon.push({ id, suara });

        if (setOldSuara) {
            this.oldSuaraCalon += suara;
        }
    }

    static updateSuaraCalon(tpsId, calonId, newSuara) {
        const data = JSON.parse(localStorage.getItem('tps_data')) || [];

        const tpsIndex = data.findIndex(tps => tps.id === tpsId);
        if (tpsIndex === -1) {
            console.error(`TPS with id ${tpsId} not found.`);
            return;
        }

        const calonIndex = data[tpsIndex].suara_calon.findIndex(calon => calon.id === calonId);
        if (calonIndex === -1) {
            console.error(`Calon with id ${calonId} not found in TPS ${tpsId}.`);
            return;
        }

        data[tpsIndex].suara_calon[calonIndex].suara = newSuara;

        localStorage.setItem('tps_data', JSON.stringify(data));
    }

    get newSuaraCalon() {
        return this.suaraCalon.reduce(function (acc, sc) {
            return acc + sc.suara;
        }, 0);
    }

    get calculatedSuaraSah() {
        return (this.suaraSah - this.oldSuaraCalon) + this.newSuaraCalon;
    }

    get abstain() {
		return this.dpt - this.suaraMasuk;
    }

    get suaraMasuk() {
		return this.calculatedSuaraSah + (this.suaraTidakSah || 0);
    }

    get partisipasi() {
        if (this.dpt == 0 || this.dpt == '') {
            return 0;
        }
		
		return parseFloat(((this.suaraMasuk / this.dpt) * 100).toFixed(1));
    }

    toObject() {
        return {
            id: this.id,
            dpt: this.dpt,
            suara_calon: this.suaraCalon,
            old_suara_calon: this.oldSuaraCalon,
            suara_sah: this.suaraSah,
            suara_tidak_sah: this.suaraTidakSah,
            abstain: this.abstain,
            suara_masuk: this.suaraMasuk,
            partisipasi: this.partisipasi
        };
    }

    static fromObject(obj) {
        const tps = new TPS(
            obj.id,
            obj.dpt,
            obj.suara_sah,
            obj.suara_tidak_sah,
            obj.old_suara_calon,
        );

        obj.suara_calon.forEach(sc => tps.addSuaraCalon(sc.id, parseInt(sc.suara), false));

        return tps;
    }

    static getAll() {
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

        const data = TPS.getAll();
        data.push(this);
        localStorage.setItem('tps_data', JSON.stringify(data.map(tps => tps.toObject())));
    }

    static update(id, updatedData) {
        const data = TPS.getAll();
        const index = data.findIndex(item => item.id === id);

        if (index !== -1) {
            Object.assign(data[index], updatedData);
            localStorage.setItem('tps_data', JSON.stringify(data.map(tps => tps.toObject())));
        } else {
            console.error(`TPS dengan id ${id} tidak ditemukan.`);
        }
    }

    static delete(id) {
        const data = TPS.getAll();
        const updatedData = data.filter(item => item.id !== id);
        localStorage.setItem('tps_data', JSON.stringify(updatedData.map(tps => tps.toObject())));
    }

    static deleteAll() {
        localStorage.removeItem('tps_data');
    }

    static getById(id) {
        const data = TPS.getAll();
        const tps = data.find(item => item.id === id) || null;

        if (tps == null) {
            return null;
        }

        return tps;
    }

    static exists(id) {
        return TPS.getAll().some(item => item.id === id);
    }
}

class InputSuaraUIManager {
	constructor($wire) {
		this.$wire = $wire;
	}

	caches = {
        components: {
            saveButton: null,
            cancelEditButton: null,
            enterEditButton: null,
            checkAllCheckbox: null,
            saveLoading: null,
            rows: [],
            checkboxes: [],
            inputs: [],
            activeInputs: []
        },
    };

    lastCheckedCheckbox = null;

    saveActiveEditableCellInputs = () =>
        this.caches.components.activeInputs = Array.from(document.querySelectorAll('input[type=number]:not(.hidden)'));

    checksCheckAllCheckboxes = () => this.caches.components.checkAllCheckbox.checked = true;
    unchecksCheckAllCheckboxes = () => this.caches.components.checkAllCheckbox.checked = false;

    enableSaveButton = () => this.caches.components.saveButton.disabled = false;
    disableSaveButton = () => this.caches.components.saveButton.disabled = true;

    enableCancelEditButton = () => this.caches.components.cancelEditButton.disabled = false;
    disableCancelEditButton = () => this.caches.components.cancelEditButton.disabled = true;

    enableEnterEditButton = () => this.caches.components.enterEditButton.disabled = false;
    disableEnterEditButton = () => this.caches.components.enterEditButton.disabled = true;

    showSaveLoadingMessage() {
        this.caches.components.saveLoading.classList.remove('hidden');
        this.caches.components.saveLoading.classList.add('flex');
    }

    hideSaveLoadingMessage() {
        this.caches.components.saveLoading.classList.add('hidden');
        this.caches.components.saveLoading.classList.remove('flex');
    }

    enableCheckboxes() {
        this.caches.components.checkAllCheckbox.disabled = false;
        this.caches.components.checkboxes
            .forEach(checkbox => checkbox.disabled = false);
    }

    disableCheckboxes() {
        this.caches.components.checkAllCheckbox.disabled = true;
        this.caches.components.checkboxes
            .forEach(checkbox => checkbox.disabled = true);
    }

    updateCaches() {
        this.caches.components.saveButton = document.getElementById('simpanPerubahanData');
        this.caches.components.cancelEditButton = document.getElementById('batalUbahData');
        this.caches.components.enterEditButton = document.getElementById('ubahDataTercentang');

        this.caches.components.saveLoading = document.getElementById('loading');

        this.caches.components.checkAllCheckbox = document.getElementById('checkAll');
        this.caches.components.rows = Array.from(document.querySelectorAll('tr.tps'));

        if (this.caches.components.rows.length > 0) {
            this.caches.components.checkboxes = this.caches.components.rows
                .map(row => row.querySelector('td.centang input[type=checkbox]'));

            this.caches.components.inputs = this.caches.components.rows
                .map(row => row.querySelector('td input[type=number]'));
        } else {
            this.caches.components.checkboxes = [];
            this.caches.components.inputs = [];
        }
    }

    isEditMode() {
        const isIt = localStorage.getItem('is_edit_mode') || null;
        return isIt == '1';
    }

    enableEditModeState = () => localStorage.setItem('is_edit_mode', '1');
    cancelEditModeState = () => localStorage.removeItem('is_edit_mode');

    addTPS(id, dpt, suaraSah, suaraTidakSah, suaraCalon) {
        const tps = new TPS(id, parseInt(dpt), parseInt(suaraSah), parseInt(suaraTidakSah));
        suaraCalon.forEach(sc => tps.addSuaraCalon(sc.id, parseInt(sc.suara)));
        tps.save();
    }

    onRowClick(event) {
        event.stopPropagation();

        if (!this.isEditMode()) {
            const row = event.target.parentElement;
            const checkbox = this.caches.components.checkboxes
                .find(_checkbox => _checkbox.parentElement.dataset.id == row.dataset.id);

            if (event.shiftKey && this.lastCheckedCheckbox != null) {
                this.checkIntermediateCheckboxes(this.lastCheckedCheckbox, checkbox);
            } else {
                this.dispatchCheckboxClickEvent(checkbox, !checkbox.checked);
            }

            this.lastCheckedCheckbox = checkbox;
        }
    }

    onCheckboxClick(event) {
        event.stopPropagation();

        const checkbox = event.target;
        const row = checkbox.parentElement;
        const tpsId = row.dataset.id;

        if (checkbox.checked) {
            if (event.shiftKey && this.lastCheckedCheckbox != null) {
                this.checkIntermediateCheckboxes(this.lastCheckedCheckbox, checkbox);
            } else {
                const row = checkbox.parentElement.parentElement;

                const dpt = row.querySelector('td.dpt').dataset.value;
                const suaraSah = row.querySelector('td.suara-sah').dataset.value;
                const suaraTidakSah = row.querySelector('td.suara-tidak-sah').dataset.value;
                const suaraCalon = Array.from(row.querySelectorAll('td.paslon'))
                    .map(suara => ({
                        id: suara.dataset.id,
                        suara: suara.dataset.suara
                    }));

                this.addTPS(tpsId, dpt, suaraSah, suaraTidakSah, suaraCalon);
            }

            this.lastCheckedCheckbox = event.target;
        } else {
            TPS.delete(tpsId);
        }

        this.refreshState();
    }

    dispatchCheckboxClickEvent(checkboxElement, isChecked) {
        checkboxElement.checked = isChecked;
        checkboxElement.dispatchEvent(new Event('click'));
    }

    checkIntermediateCheckboxes(startCheckbox, endCheckbox) {
        const checkboxes = this.caches.components.checkboxes;
        const start = checkboxes.indexOf(startCheckbox);
        const end = checkboxes.indexOf(endCheckbox);

        checkboxes.slice(Math.min(start, end), Math.max(start, end) + 1)
            .forEach(checkbox => this.dispatchCheckboxClickEvent(checkbox, true));
    }

    getCheckedCheckboxesCount() {
        return this.caches.components.checkboxes
            .filter(checkbox => checkbox.checked)
            .length;
    }

    repairInputValues() {
        this.caches.components.inputs.forEach(function(input) {
            if (input.value < 0 || input.value == '' || isNaN(input.value)) {
                input.value = input.dataset.defaultValue || 0;
            }
            
            input.dispatchEvent(new Event('keyup'));
        });
    }

    onSaveClick() {
        if (this.isEditMode() && confirm('Simpan perubahan data?')) {
            this.showSaveLoadingMessage();
            this.repairInputValues();

            const data = TPS.getAll().map(tps => tps.toObject());
            this.$wire.dispatch('submit-tps', { data });
        }
    }

    onDataStored({
        status
    }) {
        if (status == 'sukses') {
            this.initialize();
            this.hideSaveLoadingMessage();
        }
    }

    onCancelEditButtonClick() {
        if (this.isEditMode() && confirm('Yakin ingin batalkan pengeditan?')) {
            this.cancelEditModeState();
            this.refreshState();
        }
    }

    onEnterEditButtonClick() {
        this.enableEditModeState();
        this.refreshState();
        this.saveActiveEditableCellInputs();
        this.focusToFirstEditableCellInput();
    }

    checkAllCheckboxes(isChecked) {
        this.caches.components.checkboxes
            .forEach(checkbox => this.dispatchCheckboxClickEvent(checkbox, isChecked));
    }

    onCheckAllCheckboxesClick(event) {
        const isCheckAll = event.target.checked;
        this.checkAllCheckboxes(isCheckAll);
    }

    onUnloadPage(event) {
        if (this.isEditMode()) {
            // Cancel the event as stated by the standard.
            event.preventDefault();
            // Chrome requires returnValue to be set.
            event.returnValue = '';
        }
    }

    attachEventToInteractableComponents() {
        this.caches.components.saveButton.onclick = this.onSaveClick.bind(this);
        this.caches.components.cancelEditButton.onclick = this.onCancelEditButtonClick.bind(this);
        this.caches.components.enterEditButton.onclick = this.onEnterEditButtonClick.bind(this);

        this.caches.components.checkAllCheckbox.onclick = this.onCheckAllCheckboxesClick.bind(this);

        this.caches.components.rows.forEach(row => row.onclick = this.onRowClick.bind(this));
        this.caches.components.checkboxes.forEach(checkbox => checkbox.onclick = this.onCheckboxClick.bind(this));

        window.onbeforeunload = this.onUnloadPage.bind(this);

        document.body.onkeydown = event => {
            if (event.ctrlKey && event.key === "s") {
                event.preventDefault();
                this.onSaveClick();
            }

            if (event.ctrlKey && event.key === "Enter") {
                event.preventDefault();
                this.onSaveClick();
            }

            if (event.ctrlKey && event.key === "u") {
                event.preventDefault();
                if (this.getCheckedCheckboxesCount() > 0) {
                    this.onEnterEditButtonClick();
                }
            }

            if (event.key === "Escape") {
                if (this.isEditMode()) {
                    this.onCancelEditButtonClick();
                } else {
                    this.checkAllCheckboxes(false);
                }
            }

            if (event.ctrlKey && event.key === "a") {
                if (!this.isEditMode()) {
                    event.preventDefault();
                    this.checkAllCheckboxes(true);
                }
            }
        };

        document.getElementById('search').addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === "a") {
                event.stopPropagation();
            }
        });
    }

    resetTableInput() {
        this.caches.components.inputs.forEach(function (input) {
            if (input.dataset.defaultValue) {
                input.value = input.dataset.defaultValue;
            } else {
                input.value = '';
            }
        });
    }

    syncEnterEditButtonState() {
        if (this.getCheckedCheckboxesCount() > 0 && !this.isEditMode()) {
            this.enableEnterEditButton();
        } else {
            this.disableEnterEditButton();
        }
    }

    syncActionButtons() {
        if (this.isEditMode()) {
            this.enableSaveButton();
            this.enableCancelEditButton();
            this.syncEnterEditButtonState();
        } else {
            this.disableSaveButton();
            this.disableCancelEditButton();
            this.syncEnterEditButtonState();
        }
    }

    syncCheckboxesWithSelectedTPS() {
        this.checksCheckAllCheckboxes();

        this.caches.components.checkboxes
            .forEach(checkbox => {
                const isChecked = TPS.exists(checkbox.parentElement.dataset.id);
                checkbox.checked = isChecked;

                if (!isChecked) {
                    this.unchecksCheckAllCheckboxes();
                }
            });
    }

    syncCheckboxesState() {
        if (this.isEditMode()) {
            this.disableCheckboxes();
        } else {
            this.enableCheckboxes();
        }
    }

    syncTableDataWithSelectedTPS() {
        if (this.isEditMode()) {
            this.caches.components.rows.forEach(row => {
                const tpsId = row.querySelector('td.nomor').dataset.id;
                const tps = TPS.getById(tpsId);

                if (tps instanceof TPS) {
                    const dptCell = row.querySelector('td.dpt');
                    // dptCell.dataset.value = tps.dpt;
                    dptCell.querySelector('span').textContent = tps.dpt;

                    tps.suaraCalon.forEach(function (sc) {
                        const suaraCalonCell = row.querySelector(`td.paslon[data-id="${sc.id}"]`);
                        const suaraCalonValue = suaraCalonCell.querySelector('span');
                        suaraCalonValue.value = sc.suara;
                    });

                    const suaraSahCell = row.querySelector('td.suara-sah');
                    // suaraSahCell.dataset.value = tps.suaraSah;
                    suaraSahCell.textContent = tps.calculatedSuaraSah;

                    const suaraTidakSahCell = row.querySelector('td.suara-tidak-sah');
                    // suaraTidakSahCell.dataset.value = tps.suaraTidakSah;
                    suaraTidakSahCell.querySelector('span').textContent = tps.suaraTidakSah;

                    const abstainRow = row.querySelector('td.abstain');
                    // abstainRow.dataset.value = tps.abstain;
                    abstainRow.textContent = tps.abstain;

                    const suaraMasukCell = row.querySelector('td.suara-masuk');
                    // suaraMasukCell.dataset.value = tps.suaraMasuk;
                    suaraMasukCell.textContent = tps.suaraMasuk;

                    const partisipasiCell = row.querySelector('td.partisipasi span');

                    // partisipasiCell.dataset.value = tps.partisipasi;
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

    syncTableInputWithSelectedTPS() {
        if (this.isEditMode()) {
            this.caches.components.rows.forEach(row => {
                const tpsId = row.querySelector('td.nomor').dataset.id;
                const tps = TPS.getById(tpsId);

                if (tps instanceof TPS) {
                    const dptCell = row.querySelector('td.dpt');
                    const dptInput = dptCell.querySelector('input');
                    dptInput.value = tps.dpt;

                    tps.suaraCalon.forEach(function (sc) {
                        const suaraCalonCell = row.querySelector(`td.paslon[data-id="${sc.id}"]`);
                        const suaraCalonInput = suaraCalonCell.querySelector('input');
                        suaraCalonInput.value = sc.suara;
                    });

                    const suaraTidakSahCell = row.querySelector('td.suara-tidak-sah');
                    const suaraTidakSahInput = suaraTidakSahCell.querySelector('input');
                    suaraTidakSahInput.value = tps.suaraTidakSah;
                }
            });
        }
    }

    syncSelectedRowsBackgroundColor() {
        this.caches.components.rows.forEach(function (row) {
            if (TPS.exists(row.dataset.id)) {
                row.classList.add('bg-gray-200');
            } else {
                row.classList.remove('bg-gray-200');
            }
        });
    }

    onEditableCellInputTabClick(event) {
        if (event.key == 'Tab') {
            const activeInputs = this.caches.components.activeInputs;

            if (activeInputs.length > 0) {
                const firstInput = activeInputs[0];
                const lastInput = activeInputs[activeInputs.length - 1];

                if (event.target == lastInput) {
                    event.preventDefault();
                    firstInput.focus();
                    firstInput.select();
                }
            }
        }
    }

    onInputLoseFocus(event) {
        const value = event.target.value;
        if (value < 0 || value == '' || isNaN(value)) {
            event.target.value = event.target.dataset.defaultValue || 0;
        }
    }

    syncEditableCellMode({
        row,
        cellQuery,
        onChange
    }) {
        const rowDataset = row.querySelector('td.nomor').dataset;
        const tpsId = rowDataset.id;

        row.querySelectorAll(cellQuery).forEach(cell => {
            const value = cell.querySelector('span');
            const input = cell.querySelector('input');

            if (this.isEditMode() && TPS.exists(tpsId)) {
                // Change to input
                value.classList.add('hidden');
                input.classList.remove('hidden');

                const cellDataset = cell.dataset;

                input.onkeyup = event => onChange(tpsId, cellDataset, event.target.value);
                input.onkeydown = this.onEditableCellInputTabClick.bind(this);
                input.onblur = this.onInputLoseFocus;
            } else {
                // Change to value
                value.classList.remove('hidden');
                input.classList.add('hidden');
            }
        });
    }

    syncTableMode() {
        this.caches.components.rows.forEach(row => {
            this.syncEditableCellMode({
                row,
                cellQuery: 'td.dpt',
                onChange: (tpsId, _, value) => {
                    TPS.update(tpsId, {
                        dpt: parseInt(value)
                    });
                    this.syncTableDataWithSelectedTPS();
                }
            });

            this.syncEditableCellMode({
                row,
                cellQuery: 'td.suara-tidak-sah',
                onChange: (tpsId, _, value) => {
                    TPS.update(tpsId, {
                        suaraTidakSah: parseInt(value)
                    });
                    this.syncTableDataWithSelectedTPS();
                }
            });

            this.syncEditableCellMode({
                row,
                cellQuery: 'td.paslon',
                onChange: (tpsId, cellDataset, value) => {
                    TPS.updateSuaraCalon(tpsId, cellDataset.id, value);
                    this.syncTableDataWithSelectedTPS();
                }
            });
        });
    }

    focusToFirstEditableCellInput() {
        if (this.isEditMode()) {
            const activeInputs = this.caches.components.activeInputs;
            if (activeInputs.length > 0) {
                activeInputs[0].focus();
                activeInputs[0].select();
            }
        }
    }

    refreshState() {
        this.resetTableInput();

        this.syncActionButtons();

        this.syncCheckboxesWithSelectedTPS();
        this.syncCheckboxesState();

        this.syncTableDataWithSelectedTPS();
        this.syncTableInputWithSelectedTPS();
        this.syncSelectedRowsBackgroundColor();

        this.syncTableMode();

        this.attachEventToInteractableComponents();
    }

    onLivewireAllMorphingFinished() {
        this.updateCaches();
        this.refreshState();
    }

    initializeHooks() {
        this.$wire.on('data-stored', this.onDataStored.bind(this));

        let timeoutId = null;

        Livewire.hook('morph.updated', () => {
            clearTimeout(timeoutId);

            timeoutId = setTimeout(() => {
                this.onLivewireAllMorphingFinished();
                timeoutId = null;
            }, 10);
        });
    }

    initialize() {
        TPS.deleteAll();
        this.cancelEditModeState();
        this.updateCaches();
        this.refreshState();
    }
}
