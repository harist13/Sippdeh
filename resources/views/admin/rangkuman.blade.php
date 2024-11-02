@include('admin.layout.header')

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<style>
    
    #tpsBtn, #suaraBtn, #pilgubBtn {
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
        .participation-red { background-color: #ff7675; }
        .participation-yellow { background-color: #feca57; }
        .participation-green { background-color: #69d788; }
    
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

<main class="container flex-grow px-4 mx-auto mt-6">
    <div class="container mx-auto p-4 sm:p-6 bg-white rounded-lg shadow-md">
       

      <!-- Paslon Data Table Section -->
       <!-- Export Modal -->
            <div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-20 hidden">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-5">Ekspor Resume Pilgub</h3>
                        
                        <div class="mb-4">
                            <div class="text-left mb-2">Kabupaten/Kota</div>
                            <select id="exportKabupatenSelect" class="w-full p-2 border rounded">
                                <option value="">Semua</option>
                                @foreach($kabupatens as $kabupaten)
                                    <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4 flex justify-center gap-4">
                            <button id="cancelExport" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-28 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Batalkan
                            </button>
                            <button id="confirmExport" class="px-4 py-2 bg-[#3560A0] text-white text-base font-medium rounded-md w-28 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                Ekspor
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden mb-8">
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div class="text-black py-2 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                        Daftar Paslon Gubernur Dengan Partisipasi Se-Kalimantan Timur
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <div class="flex items-center w-full sm:w-auto">
                            <select id="kabupatenFilter" class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                                <option value="">Semua Kabupaten</option>
                                @foreach($kabupatens as $kabupaten)
                                    <option value="{{ $kabupaten->nama }}">{{ $kabupaten->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                            </svg>
                            <input type="search" id="searchInput" placeholder="Cari..." name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600">
                        </div>
                        <button onclick="toggleModal()" class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                            <img src="{{ asset('assets/icon/filter.svg') }}" alt="">
                            Filter
                        </button>
                        <button id="exportBtn" class="px-4 py-2 bg-[#ee3c46] text-white rounded-lg whitespace-nowrap flex items-center space-x-2">
                            <img src="{{ asset('assets/icon/download.png')}}">
                            <span>Export</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border-collapse text-center" id="dataTable">
                        <thead class="bg-[#3560a0] text-white">
                            <tr>
                                <th class="py-3 px-4 border-r border-white">No</th>
                                <th class="py-3 px-4 border-r border-white">Kab/Kota</th>
                                <th class="py-3 px-4 border-r border-white">Kec.</th>
                                <th class="py-3 px-4 border-r border-white">Kel.</th>
                                <th class="py-3 px-4 border-r border-white">DPT</th>
                                @foreach($paslon as $calon)
                                    <th class="py-3 px-4 border-r border-white">
                                        {{ $calon->nama }}/{{ $calon->nama_wakil }}
                                    </th>
                                @endforeach
                                <th class="py-3 px-4 border-r border-white">Abstain</th>
                                <th class="py-3 px-4 border-r border-white">Tingkat Partisipasi (%)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100">
                            @foreach($summaryData as $index => $data)
                                <tr class="border-b search-row">
                                    <td class="py-3 px-4 border-r">
                                        {{ str_pad(($summaryData->currentPage() - 1) * $summaryData->perPage() + $index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-3 px-4 border-r kabupaten-cell">{{ $data->kabupaten_nama }}</td>
                                    <td class="py-3 px-4 border-r kecamatan-cell">{{ $data->kecamatan_nama }}</td>
                                    <td class="py-3 px-4 border-r kelurahan-cell">{{ $data->kelurahan_nama }}</td>
                                    <td class="py-3 px-4 border-r">{{ $data->suara->dpt ?? 0 }}</td>
                                    
                                    @foreach($paslon as $calon)
                                        <td class="py-3 px-4 border-r">
                                            {{ $data->suaraCalon->where('calon_id', $calon->id)->first()->suara ?? 0 }}
                                        </td>
                                    @endforeach

                                    <td class="py-3 px-4 border-r">{{ $data->jumlah_pengguna_tidak_pilih ?? 0 }}</td>
                                    <td class="py-3 px-4 border-r">
                                        @php
                                            $partisipasi = $data->partisipasi ?? 0;
                                            $colorClass = $partisipasi >= 80 ? 'bg-green-400' : ($partisipasi >= 60 ? 'bg-yellow-400' : 'bg-red-400');
                                        @endphp
                                        <div class="participation-button {{ $colorClass }} text-white py-1 px-7 rounded text-xs">
                                            {{ number_format($partisipasi, 1) }}%
                                        </div>
                                    </td>
                                </tr>
                                <tr id="noDataRow" class="hidden">
                                    <td colspan="100%" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <p class="text-lg">Data yang dicari tidak ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $summaryData->links('vendor.pagination.custom') }}
                </div>
            </div>

            <hr class="border-t border-gray-300 my-10">

        <!-- TPS Data Table Section -->
          <div class="overflow-hidden mb-8">
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div class=" text-black py-2 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                        Daftar 5 Paslon Dengan Partisipasi Tertinggi Se-Kalimantan Timur
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <div class="flex items-center w-full sm:w-auto">
                            <select class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                                <option>Samarinda</option>
                            </select>
                        </div>
                        <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z" clip-rule="evenodd" />
                            </svg>
                            <input type="search" placeholder="Cari" name="cari" class="ml-2 bg-transparent focus:outline-none text-gray-600" value="">
                        </div>
                    <button onclick="toggleModal()" class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                        <img src="{{ asset('assets/icon/filter.svg') }}" alt="">
                        Filter
                    </button>
                     <button id="exportBtn" class="px-4 py-2 bg-[#ee3c46] text-white rounded-lg whitespace-nowrap flex items-center space-x-2">
                    <img src="{{ asset('assets/icon/download.png')}}">
                    <span>Export</span>
                </button>

                    </div>
                    
                </div>
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border-collapse text-center">
                            <thead class="bg-[#3560a0] text-white">
                                <tr>
                                    <th class="py-3 px-4 border-r border-white">No</th>
                                    <th class="py-3 px-4 border-r border-white">Kec.</th>
                                    <th class="py-3 px-4 border-r border-white">Kel.</th>
                                    <th class="py-3 px-4 border-r border-white">DPT</th>
                                    <th class="py-3 px-4 border-r border-white">Paslon 1</th>
                                    <th class="py-3 px-4 border-r border-white">Paslon 2</th>
                                    <th class="py-3 px-4 border-r border-white">Abstai</th>
                                    <th class="py-3 px-4 border-r border-white">Tingkat Partisipasi (%)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-100">
                                <tr class="border-b">
                                    <td class="py-3 px-4 border-r">01</td>
                                    <td class="py-3 px-4 border-r">Kecamatan 1</td> <!-- Replace with actual district -->
                                    <td class="py-3 px-4 border-r">Kelurahan 1</td> <!-- Replace with actual village -->
                                    <td class="py-3 px-4 border-r">582.467</td>
                                    <td class="py-3 px-4 border-r">255.345</td>
                                    <td class="py-3 px-4 border-r">228.912</td>
                                    <td class="py-3 px-4 border-r">2.123</td>
                                    <td class="py-3 px-4 border-r">
                                        <div class="participation-button participation-green">78.5%</div>
                                    </td>
                                </tr>
                                <!-- Add more rows here in the same format, updating with the specific district (Kec.) and village (Kel.) as needed -->
                            </tbody>
                        </table>
                    </div>

            </div>

        

    
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const kabupatenFilter = document.getElementById('kabupatenFilter');
    const dataTable = document.getElementById('dataTable');
    let searchTimeout;

    // Function untuk melakukan pencarian
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedKabupaten = kabupatenFilter.value.toLowerCase();
        const rows = document.querySelectorAll('.search-row');
        const noDataRow = document.getElementById('noDataRow');
        let visibleRows = 0;

        rows.forEach(row => {
            const kabupaten = row.querySelector('.kabupaten-cell').textContent.toLowerCase();
            const kecamatan = row.querySelector('.kecamatan-cell').textContent.toLowerCase();
            const kelurahan = row.querySelector('.kelurahan-cell').textContent.toLowerCase();

            // Filter berdasarkan kabupaten yang dipilih
            const matchesKabupaten = selectedKabupaten === '' || kabupaten === selectedKabupaten;

            // Filter berdasarkan pencarian
            const matchesSearch = 
                kabupaten.includes(searchTerm) || 
                kecamatan.includes(searchTerm) || 
                kelurahan.includes(searchTerm);

            // Tampilkan baris jika memenuhi kriteria filter
            if (matchesKabupaten && matchesSearch) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show or hide the "no data" message
        if (visibleRows === 0) {
            noDataRow.classList.remove('hidden');
        } else {
            noDataRow.classList.add('hidden');
        }

        // Update nomor urut yang ditampilkan
        updateRowNumbers();
    }

    // Function untuk update nomor urut
    function updateRowNumbers() {
        let visibleIndex = 1;
        const rows = document.querySelectorAll('.search-row');
        
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const numberCell = row.querySelector('td:first-child');
                numberCell.textContent = String(visibleIndex).padStart(2, '0');
                visibleIndex++;
            }
        });
    }

    // Event listener untuk input pencarian dengan debounce
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });

    // Event listener untuk filter kabupaten
    kabupatenFilter.addEventListener('change', performSearch);

    // Event listener untuk reset pencarian
    searchInput.addEventListener('search', function() {
        if (this.value === '') {
            performSearch();
        }
    });

    document.getElementById('exportBtn').addEventListener('click', function() {
        const searchTerm = document.getElementById('searchInput').value;
        const selectedKabupaten = document.getElementById('kabupatenFilter').value;
        
        // Build the export URL with current filters
        let exportUrl = new URL('/admin/rangkuman/export', window.location.origin);
        let params = new URLSearchParams();
        
        if (searchTerm) {
            params.append('search', searchTerm);
        }
        if (selectedKabupaten) {
            params.append('kabupaten', selectedKabupaten);
        }
        
        exportUrl.search = params.toString();
        
        // Redirect to export URL
        window.location.href = exportUrl.toString();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const exportModal = document.getElementById('exportModal');
    const exportBtn = document.getElementById('exportBtn');
    const cancelExport = document.getElementById('cancelExport');
    const confirmExport = document.getElementById('confirmExport');
    const exportKabupatenSelect = document.getElementById('exportKabupatenSelect');

    // Show modal when export button is clicked
    exportBtn.addEventListener('click', function() {
        exportModal.classList.remove('hidden');
    });

    // Hide modal when cancel button is clicked
    cancelExport.addEventListener('click', function() {
        exportModal.classList.add('hidden');
    });

    // Hide modal when clicking outside
    exportModal.addEventListener('click', function(e) {
        if (e.target === exportModal) {
            exportModal.classList.add('hidden');
        }
    });

    // Handle export confirmation
    confirmExport.addEventListener('click', function() {
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
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !exportModal.classList.contains('hidden')) {
            exportModal.classList.add('hidden');
        }
    });
});
</script>



@include('admin.layout.footer')