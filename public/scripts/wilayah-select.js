class WilayahSelect {
    constructor(container) {
        this.container = container;
        this.elements = this.getElements();

        this.state = {
            selectedValues: new Set(),
            isOpen: false
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
            options: this.container.querySelectorAll('.option'),
            applyButton: this.container.querySelector('.apply-button')
        };
    }

    initializeEventListeners() {
        // Button click
        this.elements.button.addEventListener('click', () => this.toggleDropdown());

        // Search functionality
        this.initializeSearch();

        // Select all functionality
        this.initializeSelectAll();

        // Options selection
        this.initializeOptions();

        // Apply button
        this.elements.applyButton.addEventListener('click', () => this.applySelection());

        // Outside click
        this.initializeOutsideClick();
    }

    initializeSearch() {
        const {
            searchInput,
            options
        } = this.elements;

        // Prevent Ctrl+A propagation
        searchInput.addEventListener('keydown', (event) => {
            if (event.ctrlKey && event.key === "a") {
                event.stopPropagation();
            }
        });

        // Search functionality
        searchInput.addEventListener('keyup', (event) => {
            this.filterOptions(event.target.value);
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
        const {
            selectAllButton
        } = this.elements;
        if (!selectAllButton) return;

        selectAllButton.addEventListener('click', (event) => {
            event.stopPropagation();
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
    }

    initializeOptions() {
        this.elements.options.forEach(option => {
            option.addEventListener('click', () => {
                this.toggleOption(option);
                this.updateSelectAllButton();
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

        this.state.isOpen = !this.state.isOpen;
        WilayahSelect.closeAllDropdowns();

        if (this.state.isOpen) {
            this.elements.optionsContainer.classList.remove('hidden');
            this.elements.optionsContainer.classList.add('block');
        }
    }

    initializeOutsideClick() {
        const handleOutsideClick = (e) => {
            if (!this.container.contains(e.target)) {
                this.closeDropdown();
            }
        };

        document.addEventListener('click', handleOutsideClick);
    }

    closeDropdown() {
        this.state.isOpen = false;
        this.elements.optionsContainer.classList.remove('block');
        this.elements.optionsContainer.classList.add('hidden');
    }

    applySelection() {
        this.updateSelectedText();
        this.updateHiddenSelect();
        this.closeDropdown();
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

        Array.from(this.elements.hiddenSelect.selectedOptions).forEach(option => {
            this.state.selectedValues.add(option.value);

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

    static closeAllDropdowns() {
        document.querySelectorAll('.options-container').forEach(container => {
            container.classList.remove('block');
            container.classList.add('hidden');
        });
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
    Livewire.hook('morph.updated', ({
        el
    }) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            initializeSelects();
            timeoutId = null;
        }, 100);
    });

    // Re-initialize on Livewire initialization
    document.addEventListener('livewire:initialized', initializeSelects);
}
