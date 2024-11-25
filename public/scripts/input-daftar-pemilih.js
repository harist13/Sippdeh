class DaftarPemilih {
    constructor(id, dptb, dpk) {
        this.id = id; // ID Kecamatan
        this.dptb = dptb;
        this.dpk = dpk;
    }

    toObject() {
        return {
            id: this.id,
            dptb: this.dptb,
            dpk: this.dpk,
        };
    }

    static fromObject(obj) {
        const daftarPemilih = new DaftarPemilih(
            obj.id,
            obj.dptb,
            obj.dpk,
        );

        return daftarPemilih;
    }

    static getAll() {
        try {
            const data = JSON.parse(localStorage.getItem('daftar_pemilih_data')) || [];
            return Array.isArray(data) ? data.map(item => DaftarPemilih.fromObject(item)) : [];
        } catch {
            return [];
        }
    }

    save() {
        if (DaftarPemilih.exists(this.id)) {
            return;
        }

        const data = DaftarPemilih.getAll();
        data.push(this);
        localStorage.setItem('daftar_pemilih_data', JSON.stringify(data.map(daftarPemilih => daftarPemilih.toObject())));
    }

    static update(id, updatedData) {
        const data = DaftarPemilih.getAll();
        const index = data.findIndex(item => item.id === id);

        if (index !== -1) {
            Object.assign(data[index], updatedData);
            localStorage.setItem('daftar_pemilih_data', JSON.stringify(data.map(daftarPemilih => daftarPemilih.toObject())));
        } else {
            console.error(`DaftarPemilih dengan id ${id} tidak ditemukan.`);
        }
    }

    static delete(id) {
        const data = DaftarPemilih.getAll();
        const updatedData = data.filter(item => item.id !== id);
        localStorage.setItem('daftar_pemilih_data', JSON.stringify(updatedData.map(daftarPemilih => daftarPemilih.toObject())));
    }

    static deleteAll() {
        localStorage.removeItem('daftar_pemilih_data');
    }

    static getById(id) {
        const data = DaftarPemilih.getAll();
        const daftarPemilih = data.find(item => item.id === id) || null;

        if (daftarPemilih == null) {
            return null;
        }

        return daftarPemilih;
    }

    static exists(id) {
        return DaftarPemilih.getAll().some(item => item.id === id);
    }
}

class InputDaftarPemilihUIManager {
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
        this.caches.components.activeInputs = Array.from(document.querySelectorAll('table.input-suara-table input[type=number]:not(.hidden)'));

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
        this.caches.components.rows = Array.from(document.querySelectorAll('tr.daftar-pemilih'));

        if (this.caches.components.rows.length > 0) {
            this.caches.components.checkboxes = this.caches.components.rows
                .map(row => row.querySelector('td.centang input[type=checkbox]'));

            this.caches.components.rows.forEach(row => {
                row.querySelectorAll('td input[type=number]')
                    .forEach(input => this.caches.components.inputs.push(input));
            });
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

    addDaftarPemilih(id, dptb, dpk) {
        const daftarPemilih = new DaftarPemilih(id, parseInt(dptb), parseInt(dpk));
        daftarPemilih.save();
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
        const daftarPemilihId = row.dataset.id;

        if (checkbox.checked) {
            if (event.shiftKey && this.lastCheckedCheckbox != null) {
                this.checkIntermediateCheckboxes(this.lastCheckedCheckbox, checkbox);
            } else {
                const row = checkbox.parentElement.parentElement;

                const dptb = row.querySelector('td.dptb').dataset.value;
                const dpk = row.querySelector('td.dpk').dataset.value;

                this.addDaftarPemilih(daftarPemilihId, dptb, dpk);
            }

            this.lastCheckedCheckbox = event.target;
        } else {
            DaftarPemilih.delete(daftarPemilihId);
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

            const data = DaftarPemilih.getAll().map(daftarPemilih => daftarPemilih.toObject());
            this.$wire.dispatch('submit-kecamatan', { data });
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
        if (this.isEditMode()) {
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

    syncCheckboxesWithSelectedDaftarPemilih() {
        this.checksCheckAllCheckboxes();

        this.caches.components.checkboxes
            .forEach(checkbox => {
                const isChecked = DaftarPemilih.exists(checkbox.parentElement.dataset.id);
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

    syncTableDataWithSelectedDaftarPemilih() {
        if (this.isEditMode()) {
            this.caches.components.rows.forEach(row => {
                const daftarPemilihId = row.dataset.id;
                const daftarPemilih = DaftarPemilih.getById(daftarPemilihId);

                if (daftarPemilih instanceof DaftarPemilih) {
                    const dptbCell = row.querySelector('td.dptb');
                    dptbCell.querySelector('span').textContent = daftarPemilih.dptb;

                    const dpkCell = row.querySelector('td.dpk');
                    dpkCell.querySelector('span').textContent = daftarPemilih.dpk;
                }
            });
        }
    }

    syncTableHeadersWithSelectedDaftarPemilih() {
        if (this.isEditMode()) {
            // Initialize totals
            let totals = {
                dptb: 0,
                dpk: 0,
            };
    
            // Get all rows except those being edited
            document.querySelectorAll('.input-suara-table tbody tr.daftar-pemilih').forEach(row => {
                const daftarPemilihId = row.dataset.id;
                
                // Skip if this DaftarPemilih is being edited
                if (!DaftarPemilih.exists(daftarPemilihId)) {
                    totals.rowCount++; // Increment row counter
    
                    // Add DPTb
                    const dptb = parseInt(row.querySelector('.dptb').dataset.value) || 0;
                    totals.dptb += dptb;

                    // Add DPTb
                    const dpk = parseInt(row.querySelector('.dpk').dataset.value) || 0;
                    totals.dpk += dpk;
                }
            });
    
            // Add values from edited DaftarPemilih data
            DaftarPemilih.getAll().forEach(daftarPemilih => {
                totals.rowCount++; // Increment row counter
                
                totals.dptb += parseInt(daftarPemilih.dptb) || 0;
                totals.dpk += parseInt(daftarPemilih.dpk) || 0;
            });
    
            // Update the header cells
            this.updateHeaderTotals(totals);
        }
    }
    
    updateHeaderTotals(totals) {
        // Function to update a set of header cells
        const updateHeaders = (headerContainer) => {
            if (!headerContainer) return;
    
            // Update DPTb
            const dptbTotal = headerContainer.querySelector('.total-dptb');
            if (dptbTotal) dptbTotal.textContent = this.formatNumber(totals.dptb);

            // Update DPK
            const dpkTotal = headerContainer.querySelector('.total-dpk');
            if (dpkTotal) dpkTotal.textContent = this.formatNumber(totals.dpk);
        };
    
        // Update main table headers
        updateHeaders(document.querySelector('.input-suara-table thead'));
    
        // Update sticky reference headers
        updateHeaders(document.querySelector('#stickyReferenceHeader thead'));
    }
    
    // Helper method to format numbers with thousand separators
    formatNumber(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    syncTableDataWithSelectedDaftarPemilih() {
        if (this.isEditMode()) {
            this.caches.components.rows.forEach(row => {
                const daftarPemilihId = row.dataset.id;
                const daftarPemilih = DaftarPemilih.getById(daftarPemilihId);

                if (daftarPemilih instanceof DaftarPemilih) {
                    const dptbCell = row.querySelector('td.dptb');
                    dptbCell.querySelector('span').textContent = daftarPemilih.dptb;

                    const dpkCell = row.querySelector('td.dpk');
                    dpkCell.querySelector('span').textContent = daftarPemilih.dpk;
                }
            });
        }
    }

    syncTableInputWithSelectedDaftarPemilih() {
        if (this.isEditMode()) {
            this.caches.components.rows.forEach(row => {
                const daftarPemilihId = row.dataset.id;
                const daftarPemilih = DaftarPemilih.getById(daftarPemilihId);

                if (daftarPemilih instanceof DaftarPemilih) {
                    // DPTb
                    const dptbCell = row.querySelector('td.dptb');
                    const dptbInput = dptbCell.querySelector('input');
                    dptbInput.value = daftarPemilih.dptb;

                    // DPK
                    const dpkCell = row.querySelector('td.dpk');
                    const dpkInput = dpkCell.querySelector('input');
                    dpkInput.value = daftarPemilih.dpk;
                }
            });
        }
    }

    syncSelectedRowsBackgroundColor() {
        this.caches.components.rows.forEach(function (row) {
            if (DaftarPemilih.exists(row.dataset.id)) {
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
        const rowDataset = row.dataset;
        const daftarPemilihId = rowDataset.id;

        row.querySelectorAll(cellQuery).forEach(cell => {
            const value = cell.querySelector('span');
            const input = cell.querySelector('input');

            if (this.isEditMode() && DaftarPemilih.exists(daftarPemilihId)) {
                // Change to input
                value.classList.add('hidden');
                input.classList.remove('hidden');

                const cellDataset = cell.dataset;

                input.onkeyup = event => onChange(daftarPemilihId, cellDataset, event.target.value);
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
                cellQuery: 'td.dptb',
                onChange: (daftarPemilihId, cellDataset, value) => {
                    if (value == '' || isNaN(value)) {
                        return;
                    }

                    DaftarPemilih.update(daftarPemilihId, { dptb: parseInt(value) });
                    this.syncTableDataWithSelectedDaftarPemilih();
                    this.syncTableHeadersWithSelectedDaftarPemilih();
                }
            });

            this.syncEditableCellMode({
                row,
                cellQuery: 'td.dpk',
                onChange: (daftarPemilihId, cellDataset, value) => {
                    if (value == '' || isNaN(value)) {
                        return;
                    }

                    DaftarPemilih.update(daftarPemilihId, { dpk: parseInt(value) });
                    this.syncTableDataWithSelectedDaftarPemilih();
                    this.syncTableHeadersWithSelectedDaftarPemilih();
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

        this.syncCheckboxesWithSelectedDaftarPemilih();
        this.syncCheckboxesState();

        this.syncTableDataWithSelectedDaftarPemilih();
        this.syncTableInputWithSelectedDaftarPemilih();
        this.syncSelectedRowsBackgroundColor();
        this.syncTableHeadersWithSelectedDaftarPemilih();

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
        DaftarPemilih.deleteAll();
        this.cancelEditModeState();
        this.updateCaches();
        this.refreshState();
    }
}
