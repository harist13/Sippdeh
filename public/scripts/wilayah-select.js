class WilayahSelect {
    constructor(container) {
        this.container = container;
        this.elements = this.getElements();

        this.state = {
            selectedValues: new Set(),
            isOpen: false,
            originalValues: new Set()
        };

        this.initializeEventListeners();
        this.initializeSelectedOptions();
    }

    getElements() {
        return {
            button: this.container.querySelector('.select-button'),
            hiddenSelect: this.container.querySelector('select'),
            selectedText: this.container.querySelector('.selected-text'),
            optionsContainer: this.container.querySelector('.options-container'),
            searchInput: this.container.querySelector('.search-input'),
            selectAllButton: this.container.querySelector('.select-all-button'),
            options: this.container.querySelectorAll('.option')
        };
    }

    initializeEventListeners() {
        this.elements.button.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleDropdown();
        });

        this.initializeSearch();
        this.initializeSelectAll();
        this.initializeOptions();
        this.initializeOutsideClick();
    }

    initializeSearch() {
        const { searchInput } = this.elements;

        searchInput.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === "a") {
                e.stopPropagation();
            }
        });

        searchInput.addEventListener('keyup', (e) => {
            this.filterOptions(e.target.value);
        });
    }

    filterOptions(searchText) {
        this.elements.options.forEach(option => {
            const isVisible = option.dataset.name.toLowerCase()
                .includes(searchText.toLowerCase());
            option.classList.toggle('hidden', !isVisible);
        });

        this.updateSelectAllButton();
    }

    initializeSelectAll() {
        const { selectAllButton } = this.elements;
        if (!selectAllButton) return;

        selectAllButton.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleSelectAll();
        });
    }

    toggleSelectAll() {
        const visibleOptions = this.getVisibleOptions();
        const allSelected = this.areAllVisibleOptionsSelected(visibleOptions);

        visibleOptions.forEach(option => {
            this.toggleOption(option, !allSelected);
        });

        this.updateSelectAllButton();
        this.updateSelectedText();
    }

    initializeOptions() {
        this.elements.options.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleOption(option);
                this.updateSelectAllButton();
                this.updateSelectedText();
            });
        });
    }

    toggleOption(option, forcedState = null) {
        const value = option.dataset.value;
        const checkmark = option.querySelector('.checkmark');
        const isSelected = forcedState ?? !this.state.selectedValues.has(value);

        if (isSelected) {
            this.state.selectedValues.add(value);
            option.classList.add('bg-blue-100');
            checkmark.classList.remove('hidden');
            checkmark.classList.add('flex');
        } else {
            this.state.selectedValues.delete(value);
            option.classList.remove('bg-blue-100');
            checkmark.classList.remove('flex');
            checkmark.classList.add('hidden');
        }
    }

    toggleDropdown() {
        if (this.elements.button.disabled) return;

        if (!this.state.isOpen) {
            // Opening dropdown
            this.state.originalValues = new Set(this.state.selectedValues);
            this.openDropdown();
        } else {
            // Closing dropdown
            this.closeDropdown();
        }

        this.state.isOpen = !this.state.isOpen;
    }

    openDropdown() {
        // Close other dropdowns first
        document.querySelectorAll('.options-container').forEach(container => {
            if (container !== this.elements.optionsContainer) {
                container.classList.remove('block');
                container.classList.add('hidden');
            }
        });

        // Open this dropdown
        this.elements.optionsContainer.classList.remove('hidden');
        this.elements.optionsContainer.classList.add('block');

        // Reset search
        if (this.elements.searchInput) {
            this.elements.searchInput.value = '';
            this.filterOptions('');
        }
    }

    initializeOutsideClick() {
        document.addEventListener('click', (e) => {
            if (this.state.isOpen && !this.container.contains(e.target)) {
                this.closeDropdown();
                this.state.isOpen = false;
            }
        });
    }

    closeDropdown() {
        const hasChanges = this.hasSelectionChanged();
        
        this.elements.optionsContainer.classList.remove('block');
        this.elements.optionsContainer.classList.add('hidden');
        
        if (hasChanges) {
            this.updateHiddenSelect();
        }
    }

    hasSelectionChanged() {
        if (this.state.selectedValues.size !== this.state.originalValues.size) {
            return true;
        }

        for (const value of this.state.selectedValues) {
            if (!this.state.originalValues.has(value)) {
                return true;
            }
        }

        return false;
    }

    updateSelectedText() {
        const text = this.state.selectedValues.size === 0 ?
            'Pilih...' :
            Array.from(this.elements.options)
            .filter(option => this.state.selectedValues.has(option.dataset.value))
            .map(option => option.dataset.name)
            .join(', ');

        this.elements.selectedText.textContent = text;
    }

    updateHiddenSelect() {
        Array.from(this.elements.hiddenSelect.options).forEach(option => {
            option.selected = this.state.selectedValues.has(option.value);
        });

        this.elements.hiddenSelect.dispatchEvent(new Event('change'));
    }

    initializeSelectedOptions() {
        this.state.selectedValues.clear();
        this.state.originalValues.clear();

        Array.from(this.elements.hiddenSelect.selectedOptions).forEach(option => {
            this.state.selectedValues.add(option.value);
            this.state.originalValues.add(option.value);

            const optionEl = this.elements.optionsContainer
                .querySelector(`.option[data-value="${option.value}"]`);

            if (optionEl) {
                this.toggleOption(optionEl, true);
            }
        });

        this.updateSelectedText();
        this.updateSelectAllButton();
    }

    getVisibleOptions() {
        return Array.from(this.elements.options)
            .filter(option => !option.classList.contains('hidden'));
    }

    areAllVisibleOptionsSelected(visibleOptions) {
        return visibleOptions.every(option =>
            this.state.selectedValues.has(option.dataset.value)
        );
    }

    updateSelectAllButton() {
        if (!this.elements.selectAllButton) return;

        const visibleOptions = this.getVisibleOptions();
        const allSelected = this.areAllVisibleOptionsSelected(visibleOptions);

        this.elements.selectAllButton.textContent = 
            allSelected ? 'Batal Pilih Semua' : 'Pilih Semua';
    }
}

function initializeWilayahSelects(selectQuery) {
    let timeoutId = null;
    const selects = new Map();

    function initializeSelects() {
        document.querySelectorAll(selectQuery).forEach(container => {
            selects.set(container, new WilayahSelect(container));
        });
    }

    // Initial initialization
    initializeSelects();

    // Re-initialize on Livewire updates
    Livewire.hook('morph.updated', ({ el }) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            initializeSelects();
            timeoutId = null;
        }, 100);
    });

    // Re-initialize on Livewire initialization
    document.addEventListener('livewire:initialized', initializeSelects);
}