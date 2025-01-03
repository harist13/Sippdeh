class DaftarPemilih {
    constructor(id, dptb, dpk, kotakKosong, suaraSah, suaraTidakSah, oldSuaraCalon = 0, oldKotakKosong = null) {
        this.id = id;
        this.dptb = dptb;
        this.dpk = dpk;
        this.kotakKosong = kotakKosong;
        this.suaraCalon = [];
        this.suaraSah = suaraSah;
        this.suaraTidakSah = suaraTidakSah;

        this.oldKotakKosong = oldKotakKosong != null ? oldKotakKosong : kotakKosong;
        this.oldSuaraCalon = oldSuaraCalon;
    }

    addSuaraCalon(id, suara, setOldSuara = true) {
        this.suaraCalon.push({ id, suara });

        if (setOldSuara) {
            this.oldSuaraCalon += suara;
        }
    }

    static updateSuaraCalon(daftarPemilihId, calonId, newSuara) {
        const data = JSON.parse(localStorage.getItem('daftar_pemilih_data')) || [];

        const daftarPemilihIndex = data.findIndex(daftarPemilih => daftarPemilih.id === daftarPemilihId);
        if (daftarPemilihIndex === -1) {
            console.error(`DaftarPemilih with id ${daftarPemilihId} not found.`);
            return;
        }

        const calonIndex = data[daftarPemilihIndex].suara_calon.findIndex(calon => calon.id === calonId);
        if (calonIndex === -1) {
            console.error(`Calon with id ${calonId} not found in DaftarPemilih ${daftarPemilihId}.`);
            return;
        }

        data[daftarPemilihIndex].suara_calon[calonIndex].suara = newSuara;

        localStorage.setItem('daftar_pemilih_data', JSON.stringify(data));
    }

    get newSuaraCalon() {
        return this.suaraCalon.reduce(function (acc, sc) {
            return acc + sc.suara;
        }, 0);
    }

    get calculatedSuaraSah() {
        return (this.suaraSah - (this.oldSuaraCalon + this.oldKotakKosong)) + (this.newSuaraCalon + this.kotakKosong);
    }

    get abstain() {
		return (this.dptb + this.dpk) - this.suaraMasuk;
    }

    get suaraMasuk() {
		return this.calculatedSuaraSah + (this.suaraTidakSah || 0);
    }

    get partisipasi() {
        if (this.dptb == 0 || this.dpk == '') {
            return 0;
        }
		
		return parseFloat(((this.suaraMasuk / (this.dptb + this.dpk)) * 100).toFixed(1));
    }

    toObject() {
        return {
            id: this.id,
            dptb: this.dptb,
            dpk: this.dpk,
            kotak_kosong: this.kotakKosong,
            suara_calon: this.suaraCalon,
            suara_sah: this.suaraSah,
            suara_tidak_sah: this.suaraTidakSah,
            abstain: this.abstain,
            suara_masuk: this.suaraMasuk,
            partisipasi: this.partisipasi,

            old_kotak_kosong: this.oldKotakKosong,
            old_suara_calon: this.oldSuaraCalon,
        };
    }

    static fromObject(obj) {
        const daftarPemilih = new DaftarPemilih(
            obj.id,
            obj.dptb,
            obj.dpk,
            obj.kotak_kosong,
            obj.suara_sah,
            obj.suara_tidak_sah,
            obj.old_suara_calon,
            obj.old_kotak_kosong,
        );

        obj.suara_calon.forEach(sc => daftarPemilih.addSuaraCalon(sc.id, parseInt(sc.suara), false));

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

    addDaftarPemilih(id, dptb, dpk, kotakKosong, suaraSah, suaraTidakSah, suaraCalon) {
        const daftarPemilih = new DaftarPemilih(
            id,
            parseInt(dptb),
            parseInt(dpk),
            parseInt(kotakKosong),
            parseInt(suaraSah),
            parseInt(suaraTidakSah)
        );

        suaraCalon.forEach(sc => daftarPemilih.addSuaraCalon(sc.id, parseInt(sc.suara)));
        
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
                const kotakKosong = row.querySelector('td.kotak-kosong').dataset.value;
                const suaraSah = row.querySelector('td.suara-sah').dataset.value;
                const suaraTidakSah = row.querySelector('td.suara-tidak-sah').dataset.value;
                const suaraCalon = Array.from(row.querySelectorAll('td.paslon'))
                    .map(suara => ({ id: suara.dataset.id, suara: suara.dataset.suara }));

                this.addDaftarPemilih(daftarPemilihId, dptb, dpk, kotakKosong, suaraSah, suaraTidakSah, suaraCalon);
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
                    daftarPemilih.suaraCalon.forEach(function (sc) {
                        const suaraCalonCell = row.querySelector(`td.paslon[data-id="${sc.id}"]`);
                        const suaraCalonValue = suaraCalonCell.querySelector('span');
                        suaraCalonValue.value = sc.suara;
                    });

                    const dptbCell = row.querySelector('td.dptb');
                    // dptbCell.dataset.value = daftarPemilih.dptb;
                    dptbCell.querySelector('span').textContent = daftarPemilih.dptb;

                    const dpkCell = row.querySelector('td.dpk');
                    // dpkCell.dataset.value = daftarPemilih.dpk;
                    dpkCell.querySelector('span').textContent = daftarPemilih.dpk;

                    const kotakKosongCell = row.querySelector('td.kotak-kosong');
                    // kotakKosongCell.dataset.value = daftarPemilih.kotakKosong;
                    kotakKosongCell.querySelector('span').textContent = daftarPemilih.kotakKosong;

                    const suaraSahCell = row.querySelector('td.suara-sah');
                    // suaraSahCell.dataset.value = daftarPemilih.suaraSah;
                    suaraSahCell.textContent = daftarPemilih.calculatedSuaraSah;

                    const suaraTidakSahCell = row.querySelector('td.suara-tidak-sah');
                    // suaraTidakSahCell.dataset.value = daftarPemilih.suaraTidakSah;
                    suaraTidakSahCell.querySelector('span').textContent = daftarPemilih.suaraTidakSah;

                    const abstainRow = row.querySelector('td.abstain');
                    // abstainRow.dataset.value = daftarPemilih.abstain;
                    abstainRow.textContent = daftarPemilih.abstain;

                    const suaraMasukCell = row.querySelector('td.suara-masuk');
                    // suaraMasukCell.dataset.value = daftarPemilih.suaraMasuk;
                    suaraMasukCell.textContent = daftarPemilih.suaraMasuk;

                    const partisipasiCell = row.querySelector('td.partisipasi span');

                    // partisipasiCell.dataset.value = daftarPemilih.partisipasi;
                    partisipasiCell.textContent = `${daftarPemilih.partisipasi}%`;

                    if (daftarPemilih.partisipasi >= 77.5) {
                        partisipasiCell.classList.add('bg-green-400');
                        partisipasiCell.classList.remove('bg-yellow-400');
                        partisipasiCell.classList.remove('bg-red-400');
                    } else {
                        partisipasiCell.classList.remove('bg-green-400');
                        partisipasiCell.classList.remove('bg-yellow-400');
                        partisipasiCell.classList.add('bg-red-400');
                    }
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
                suaraSah: 0,
                suaraTidakSah: 0,
                suaraMasuk: 0,
                abstain: 0,
                partisipasiTotal: 0, // Changed to track total partisipasi
                rowCount: 0, // To track number of rows for average calculation
                calonVotes: {} // To store votes per candidate
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

                    // Add DPK
                    const dpk = parseInt(row.querySelector('.dpk').dataset.value) || 0;
                    totals.dpk += dpk;
    
                    // Add Suara Sah
                    const suaraSah = parseInt(row.querySelector('.suara-sah').dataset.value) || 0;
                    totals.suaraSah += suaraSah;
    
                    // Add Suara Tidak Sah
                    const suaraTidakSah = parseInt(row.querySelector('.suara-tidak-sah').dataset.value) || 0;
                    totals.suaraTidakSah += suaraTidakSah;
    
                    // Add Suara Masuk
                    const suaraMasuk = parseInt(row.querySelector('.suara-masuk').dataset.value) || 0;
                    totals.suaraMasuk += suaraMasuk;
    
                    // Add Abstain
                    const abstain = parseInt(row.querySelector('.abstain').dataset.value) || 0;
                    totals.abstain += abstain;
    
                    // Calculate and add individual row's participation to total
                    if ((dptb + dpk) > 0) {
                        const rowPartisipasi = (suaraMasuk / (dptb + dpk)) * 100;
                        totals.partisipasiTotal += rowPartisipasi;
                    }
    
                    // Add candidate votes
                    row.querySelectorAll('.paslon').forEach(paslonCell => {
                        const calonId = paslonCell.dataset.id;
                        const suara = parseInt(paslonCell.dataset.suara) || 0;
                        totals.calonVotes[calonId] = (totals.calonVotes[calonId] || 0) + suara;
                    });
    
                    // Add kotak kosong if exists
                    const kotakKosongCell = row.querySelector('.kotak-kosong');
                    if (kotakKosongCell && !kotakKosongCell.hasAttribute('hidden')) {
                        const kotakKosong = parseInt(kotakKosongCell.dataset.value) || 0;
                        totals.calonVotes['kotak_kosong'] = (totals.calonVotes['kotak_kosong'] || 0) + kotakKosong;
                    }
                }
            });
    
            // Add values from edited DaftarPemilih data
            DaftarPemilih.getAll().forEach(daftarPemilih => {
                totals.rowCount++; // Increment row counter
                
                totals.dptb += parseInt(daftarPemilih.dptb) || 0;
                totals.dpk += parseInt(daftarPemilih.dpk) || 0;
                totals.suaraSah += parseInt(daftarPemilih.calculatedSuaraSah) || 0;
                totals.suaraTidakSah += parseInt(daftarPemilih.suaraTidakSah) || 0;
                totals.suaraMasuk += parseInt(daftarPemilih.suaraMasuk) || 0;
                totals.abstain += parseInt(daftarPemilih.abstain) || 0;
    
                // Add this DaftarPemilih's participation to total
                if ((parseInt(daftarPemilih.dptb) + parseInt(daftarPemilih.dpk)) > 0) {
                    const daftarPemilihPartisipasi = (parseInt(daftarPemilih.suaraMasuk) / (parseInt(daftarPemilih.dptb) + parseInt(daftarPemilih.dpk))) * 100;
                    totals.partisipasiTotal += daftarPemilihPartisipasi;
                }
    
                // Add candidate votes from DaftarPemilih
                daftarPemilih.suaraCalon.forEach(sc => {
                    totals.calonVotes[sc.id] = (totals.calonVotes[sc.id] || 0) + (parseInt(sc.suara) || 0);
                });
    
                // Add kotak kosong if it exists
                if (daftarPemilih.kotakKosong !== undefined) {
                    totals.calonVotes['kotak_kosong'] = (totals.calonVotes['kotak_kosong'] || 0) + (parseInt(daftarPemilih.kotakKosong) || 0);
                }
            });
    
            // Calculate average participation
            totals.partisipasi = totals.rowCount > 0 
                ? (totals.partisipasiTotal / totals.rowCount).toFixed(1) 
                : "0.0";
    
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
    
            // Update candidate totals
            Object.entries(totals.calonVotes).forEach(([calonId, total]) => {
                if (calonId === 'kotak_kosong') {
                    const kotakKosongTotal = headerContainer.querySelector('.total-kotak-kosong');
                    if (kotakKosongTotal) kotakKosongTotal.textContent = this.formatNumber(total);
                } else {
                    const calonTotal = headerContainer.querySelector(`th[wire\\:key="total-${calonId}"].total-calon`);
                    if (calonTotal) calonTotal.textContent = this.formatNumber(total);
                }
            });
    
            // Update other totals
            const suaraSahTotal = headerContainer.querySelector('.total-suara-sah');
            if (suaraSahTotal) suaraSahTotal.textContent = this.formatNumber(totals.suaraSah);
    
            const suaraTidakSahTotal = headerContainer.querySelector('.total-suara-tidak-sah');
            if (suaraTidakSahTotal) suaraTidakSahTotal.textContent = this.formatNumber(totals.suaraTidakSah);
    
            const suaraMasukTotal = headerContainer.querySelector('.total-suara-masuk');
            if (suaraMasukTotal) suaraMasukTotal.textContent = this.formatNumber(totals.suaraMasuk);
    
            const abstainTotal = headerContainer.querySelector('.total-abstain');
            if (abstainTotal) abstainTotal.textContent = this.formatNumber(totals.abstain);
    
            const partisipasiTotal = headerContainer.querySelector('.rata-rata-paritisipasi');
            if (partisipasiTotal) partisipasiTotal.textContent = `${totals.partisipasi}%`;
    
            // For totals that don't have specific classes, update by position
            const secondRow = headerContainer.querySelector('tr:last-child');
            if (secondRow) {
                // Update all numeric cells in the second row that don't have specific classes
                const updateCell = (cell, value) => {
                    if (cell && !cell.classList.contains('total-dptb') && !cell.classList.contains('total-dpk') && 
                        !cell.classList.contains('total-calon') && 
                        !cell.classList.contains('total-kotak-kosong') && 
                        !cell.classList.contains('total-suara-sah') && 
                        !cell.classList.contains('total-suara-tidak-sah') && 
                        !cell.classList.contains('total-suara-masuk') && 
                        !cell.classList.contains('total-abstain') && 
                        !cell.classList.contains('rata-rata-paritisipasi')) {
                        
                        cell.textContent = this.formatNumber(value);
                    }
                };
    
                // Update cells in order
                const cells = Array.from(secondRow.querySelectorAll('th')).filter(cell => !cell.hasAttribute('rowspan'));
                
                // Update candidate totals that might not have classes
                Object.values(totals.calonVotes).forEach((total, index) => {
                    const cell = cells[index + 1]; // +1 to skip DPT
                    if (cell) updateCell(cell, total);
                });
            }
        };
    
        // Update main table headers
        updateHeaders(document.querySelector('.input-suara-table thead'));
    
        // Update sticky reference headers
        // updateHeaders(document.querySelector('#stickyReferenceHeader thead'));
    }
    
    // Helper method to format numbers with thousand separators
    formatNumber(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    syncTableInputWithSelectedDaftarPemilih() {
        if (this.isEditMode()) {
            this.caches.components.rows.forEach(row => {
                const daftarPemilihId = row.dataset.id;
                const daftarPemilih = DaftarPemilih.getById(daftarPemilihId);

                if (daftarPemilih instanceof DaftarPemilih) {
                    daftarPemilih.suaraCalon.forEach(function (sc) {
                        const suaraCalonCell = row.querySelector(`td.paslon[data-id="${sc.id}"]`);
                        const suaraCalonInput = suaraCalonCell.querySelector('input');
                        suaraCalonInput.value = sc.suara;
                    });

                    const dptbCell = row.querySelector('td.dptb');
                    const dptbInput = dptbCell.querySelector('input');
                    dptbInput.value = daftarPemilih.dptb;

                    const dpkCell = row.querySelector('td.dpk');
                    const dpkInput = dpkCell.querySelector('input');
                    dpkInput.value = daftarPemilih.dpk;

                    const kotakKosongCell = row.querySelector('td.kotak-kosong');
                    const kotakKosongInput = kotakKosongCell.querySelector('input');
                    kotakKosongInput.value = daftarPemilih.kotakKosong;

                    const suaraTidakSahCell = row.querySelector('td.suara-tidak-sah');
                    const suaraTidakSahInput = suaraTidakSahCell.querySelector('input');
                    suaraTidakSahInput.value = daftarPemilih.suaraTidakSah;
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
                onChange: (daftarPemilihId, _, value) => {
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
                onChange: (daftarPemilihId, _, value) => {
                    if (value == '' || isNaN(value)) {
                        return;
                    }
                    
                    DaftarPemilih.update(daftarPemilihId, { dpk: parseInt(value) });
                    this.syncTableDataWithSelectedDaftarPemilih();
                    this.syncTableHeadersWithSelectedDaftarPemilih();
                }
            });

            this.syncEditableCellMode({
                row,
                cellQuery: 'td:not([hidden]).kotak-kosong',
                onChange: (daftarPemilihId, _, value) => {
                    if (value == '' || isNaN(value)) {
                        return;
                    }

                    DaftarPemilih.update(daftarPemilihId, { kotakKosong: parseInt(value) });
                    this.syncTableDataWithSelectedDaftarPemilih();
                    this.syncTableHeadersWithSelectedDaftarPemilih();
                }
            });

            this.syncEditableCellMode({
                row,
                cellQuery: 'td.paslon',
                onChange: (daftarPemilihId, cellDataset, value) => {
                    if (value == '' || isNaN(value)) {
                        return;
                    }

                    DaftarPemilih.updateSuaraCalon(daftarPemilihId, cellDataset.id, value);
                    this.syncTableDataWithSelectedDaftarPemilih();
                    this.syncTableHeadersWithSelectedDaftarPemilih();
                }
            });

            this.syncEditableCellMode({
                row,
                cellQuery: 'td.suara-tidak-sah',
                onChange: (daftarPemilihId, _, value) => {
                    if (value == '' || isNaN(value)) {
                        return;
                    }
                    
                    DaftarPemilih.update(daftarPemilihId, { suaraTidakSah: parseInt(value) });
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