@extends('admin.layout.app')

@push('head-scripts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush

@push('styles')
    <style>
        /* Style tambahan untuk select disabled */
        select:disabled {
            @apply bg-gray-100 cursor-not-allowed;
        }

        /* Style untuk partisipasi labels */
        .partisipasi-label {
            @apply transition-all duration-200 ease-in-out;
        }

        /* Loading spinner untuk select */
        .select-loading::after {
            content: "";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3560a0;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translateY(-50%) rotate(0deg);
            }

            100% {
                transform: translateY(-50%) rotate(360deg);
            }
        }


        #tpsBtn,
        #suaraBtn,
        #pilgubBtn {
            margin-right: 1px;
        }

        .btn-group button {
            margin: 0;
            padding: 10px 20px;
            border: none;
        }

        .btn-group button:not(:last-child) {
            border-right: 1px solid #d1d5db;
        }

        .participation-button {
            display: inline-block;
            width: 100px;
            padding: 3px 0;
            font-size: 14px;
            text-align: center;
            border-radius: 6px;
            font-weight: 500;
            color: white;
        }

        .participation-red {
            background-color: #ff7675;
        }

        .participation-yellow {
            background-color: #feca57;
        }

        .participation-green {
            background-color: #69d788;
        }

        @media (max-width: 640px) {
            .container {
                padding: 1rem;
            }

            .flex-wrap-mobile {
                flex-wrap: wrap;
            }

            .w-full-mobile {
                width: 100%;
            }

            .mt-4-mobile {
                margin-top: 1rem;
            }

            .overflow-x-auto {
                overflow-x: auto;
            }

            .btn-group button {
                padding: 8px 12px;
                font-size: 0.875rem;
            }
        }

    </style>
@endpush

@section('content')
<main class="container flex-grow px-4 mx-auto mt-6">
    <div class="container mx-auto p-4 sm:p-6 bg-white rounded-lg shadow-md">
        <livewire:admin.rangkuman-table />
        <livewire:admin.bupati-table />
        <livewire:admin.walikota-table />
    </div>
</main>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterKabupaten = document.getElementById('filterKabupaten');
            const filterKecamatan = document.getElementById('filterKecamatan');
            const filterKelurahan = document.getElementById('filterKelurahan');
            const partisipasiCheckboxes = document.querySelectorAll('.partisipasi-checkbox');

            // Handle partisipasi checkbox changes
            partisipasiCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const label = this.nextElementSibling;
                    if (this.checked) {
                        label.classList.add('bg-[#3560a0]');
                        switch (this.value) {
                            case 'hijau':
                                label.classList.add('text-[#69d788]');
                                break;
                            case 'kuning':
                                label.classList.add('text-[#ffe608]');
                                break;
                            case 'merah':
                                label.classList.add('text-[#fe756c]');
                                break;
                        }
                    } else {
                        label.classList.remove('bg-[#3560a0]', 'text-[#69d788]', 'text-[#ffe608]',
                            'text-[#fe756c]');
                        label.classList.add('text-gray-600');
                    }
                });
            });

            // Kabupaten change handler
            filterKabupaten.addEventListener('change', async function () {
                const kabupatenId = this.value;
                filterKecamatan.disabled = !kabupatenId;
                filterKelurahan.disabled = true;

                // Reset dropdowns
                filterKecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
                filterKelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';

                if (kabupatenId) {
                    try {
                        filterKecamatan.parentElement.classList.add('select-loading');
                        const response = await fetch(`/api/kecamatan/${kabupatenId}`);
                        const kecamatans = await response.json();

                        kecamatans.forEach(kecamatan => {
                            const option = new Option(kecamatan.nama, kecamatan.id);
                            filterKecamatan.add(option);
                        });
                    } catch (error) {
                        console.error('Error fetching kecamatan:', error);
                    } finally {
                        filterKecamatan.parentElement.classList.remove('select-loading');
                    }
                }
            });

            // Kecamatan change handler
            filterKecamatan.addEventListener('change', async function () {
                const kecamatanId = this.value;
                filterKelurahan.disabled = !kecamatanId;
                filterKelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';

                if (kecamatanId) {
                    try {
                        filterKelurahan.parentElement.classList.add('select-loading');
                        const response = await fetch(`/api/kelurahan/${kecamatanId}`);
                        const kelurahans = await response.json();

                        kelurahans.forEach(kelurahan => {
                            const option = new Option(kelurahan.nama, kelurahan.id);
                            filterKelurahan.add(option);
                        });
                    } catch (error) {
                        console.error('Error fetching kelurahan:', error);
                    } finally {
                        filterKelurahan.parentElement.classList.remove('select-loading');
                    }
                }
            });

            // Reset filter
            window.resetFilter = function () {
                filterKabupaten.value = '';
                filterKecamatan.value = '';
                filterKelurahan.value = '';
                filterKecamatan.disabled = true;
                filterKelurahan.disabled = true;

                partisipasiCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    const label = checkbox.nextElementSibling;
                    label.classList.remove('bg-[#3560a0]', 'text-[#69d788]', 'text-[#ffe608]',
                        'text-[#fe756c]');
                    label.classList.add('text-gray-600');
                });
            };

            // Apply filter
            window.applyFilter = function () {
                const filters = {
                    kabupaten_id: filterKabupaten.value,
                    kecamatan_id: filterKecamatan.value,
                    kelurahan_id: filterKelurahan.value,
                    partisipasi: Array.from(partisipasiCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value)
                        .join(',')
                };

                const url = new URL(window.location.href);
                Object.keys(filters).forEach(key => {
                    if (filters[key]) {
                        url.searchParams.set(key, filters[key]);
                    } else {
                        url.searchParams.delete(key);
                    }
                });

                window.location.href = url.toString();
            };
        });

        function toggleModal() {
            const modal = document.getElementById('filterModal');
            modal.classList.toggle('hidden');
        }


        document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const searchInput = document.getElementById('searchInput');
        const dataTable = document.getElementById('dataTable');
        const tbody = dataTable.querySelector('tbody');
        let searchTimeout;

        // Set initial search value from URL if exists
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }

        // Function to show "No Data" message
        function showNoDataMessage() {
            const noDataRow = document.createElement('tr');
            noDataRow.id = 'noDataMessage';
            noDataRow.innerHTML = `
                <td colspan="100%" class="py-4 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <p class="text-lg font-medium">Data tidak ditemukan</p>
                    </div>
                </td>
            `;
            tbody.innerHTML = '';
            tbody.appendChild(noDataRow);
        }

        // Function to perform search
        function performSearch() {
            const searchTerm = searchInput.value.trim();
            const url = new URL(window.location.href);
            
            // Update search parameter
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            
            // Reset to first page
            url.searchParams.delete('page');

            // Add loading state
            document.body.classList.add('cursor-wait');
            tbody.style.opacity = '0.5';

            // Fetch results
            fetch(url.toString())
                .then(response => response.text())
                .then(html => {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    
                    const newTbody = tempDiv.querySelector('#dataTable tbody');
                    
                    if (newTbody && newTbody.children.length > 0 && !newTbody.querySelector('#noDataMessage')) {
                        tbody.innerHTML = newTbody.innerHTML;
                    } else {
                        showNoDataMessage();
                    }

                    // Update pagination if exists
                    const paginationContainer = document.querySelector('.pagination-container');
                    const newPagination = tempDiv.querySelector('.pagination-container');
                    if (paginationContainer && newPagination) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    showNoDataMessage();
                })
                .finally(() => {
                    document.body.classList.remove('cursor-wait');
                    tbody.style.opacity = '1';
                });
        }

        // Event listener for search input with 1 second delay
        searchInput.addEventListener('keyup', function(e) {
            clearTimeout(searchTimeout);

            // Immediate search on Enter key
            if (e.key === 'Enter') {
                performSearch();
                return;
            }

            // 1 second delay for normal typing
            searchTimeout = setTimeout(performSearch, 1000);
        });

        // Clear button (x) handler
        searchInput.addEventListener('search', function() {
            if (this.value === '') {
                performSearch();
            }
        });

        // Browser back/forward handler
        window.addEventListener('popstate', function() {
            const urlParams = new URLSearchParams(window.location.search);
            searchInput.value = urlParams.get('search') || '';
            performSearch();
        });

        // Export button handler
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                const exportUrl = new URL('/admin/rangkuman/export', window.location.origin);
                
                if (searchTerm) {
                    exportUrl.searchParams.set('search', searchTerm);
                }
                
                // Preserve other active filters
                const currentParams = new URLSearchParams(window.location.search);
                for (const [key, value] of currentParams) {
                    if (key !== 'page' && key !== 'search') {
                        exportUrl.searchParams.set(key, value);
                    }
                }

                window.location.href = exportUrl.toString();
            });
        }
    });

        document.addEventListener('DOMContentLoaded', function () {
            const exportModal = document.getElementById('exportModal');
            const exportBtn = document.getElementById('exportBtn');
            const cancelExport = document.getElementById('cancelExport');
            const confirmExport = document.getElementById('confirmExport');
            const exportKabupatenSelect = document.getElementById('exportKabupatenSelect');

            // Show modal when export button is clicked
            exportBtn.addEventListener('click', function () {
                exportModal.classList.remove('hidden');
            });

            // Hide modal when cancel button is clicked
            cancelExport.addEventListener('click', function () {
                exportModal.classList.add('hidden');
            });

            // Hide modal when clicking outside
            exportModal.addEventListener('click', function (e) {
                if (e.target === exportModal) {
                    exportModal.classList.add('hidden');
                }
            });

            // Handle export confirmation
            confirmExport.addEventListener('click', function () {
                const selectedKabupaten = exportKabupatenSelect.value;

                // Build export URL with selected kabupaten
                let exportUrl = new URL('/admin/rangkuman/export', window.location.origin);
                let params = new URLSearchParams();

                if (selectedKabupaten) {
                    params.append('kabupaten_id', selectedKabupaten);
                }

                exportUrl.search = params.toString();

                // Start download
                window.location.href = exportUrl.toString();

                // Hide modal after export starts
                exportModal.classList.add('hidden');
            });

            // Close modal with ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !exportModal.classList.contains('hidden')) {
                    exportModal.classList.add('hidden');
                }
            });
        });
    </script>
@endpush