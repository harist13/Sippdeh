@include('operator.layout.header')
<style>
    .table-separator {
        height: 3px;
        background-color: #d9d9d9;
        margin: 2rem 0;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .custom-table th, .custom-table td {
        padding: 0.75rem 1rem;
        text-align: left;
        white-space: nowrap;
    }
    .custom-title-container {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }
    .custom-title {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
        text-align: center;
        line-height: 1.4;
        margin: 0;
    }
    .custom-table th {
        background-color: #3560a0;
        color: white;
        font-weight: 600;
    }
    .custom-table tr {
        border-bottom: 1px solid #e5e7eb;
    }
    .participation-cell {
        width: 120px;
        text-align: center;
    }
    .participation-button {
        width: 100%;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: bold;
        text-align: center;
        color: white;
    }
    .participation-button.green { background-color: #10B981; }
    .participation-button.yellow { background-color: #F59E0B; }
    .participation-button.red { background-color: #EF4444; }

    /* Responsive styles */
    @media (max-width: 768px) {
        .custom-title {
            font-size: 1.5rem;
        }
        .custom-table {
            font-size: 0.875rem;
        }
        .custom-table th, .custom-table td {
            padding: 0.5rem;
        }
        .filter-container {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-container > * {
            margin-bottom: 0.5rem;
        }
        .pagination-container {
            flex-direction: column;
            align-items: center;
        }
        .pagination-container > * {
            margin-bottom: 0.5rem;
        }
    }
</style>
<body class="bg-gray-100 font-sans">
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-4 sm:p-6 mb-8">
            <div class="custom-title-container">
                <h2 class="custom-title">
                    Data Perolehan Suara Calon Gubernur dan Wakil Gubernur<br>
                    di Tingkat Provinsi
                </h2>
            </div>
            
            <!-- Table 1: Partisipasi TPS Terbaik -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('assets/Archive.png')}}" alt="">
                        <span class="text-sm font-medium">Partisipasi TPS Terbaik 1-10 Sekaltim</span>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                        <select class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm w-full sm:w-auto">
                            <option>Pilih Kab/Kota</option>
                        </select>
                        <input type="text" placeholder="Search" class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm w-full sm:w-auto">
                        <button class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm flex items-center justify-center w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KAB/KOTA</th>
                                <th>KECAMATAN</th>
                                <th>KELURAHAN</th>
                                <th>TPS</th>
                                <th>DPT</th>
                                <th>PARTISIPASI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100">
                            <tr>
                                <td>01</td>
                                <td>Samarinda</td>
                                <td>Palaran</td>
                                <td>Bantuas</td>
                                <td>TPS 1</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button green">Hijau</div>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>Samarinda</td>
                                <td>Palaran</td>
                                <td>Bantuas</td>
                                <td>TPS 1</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button green">Hijau</div>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>Samarinda</td>
                                <td>Palaran</td>
                                <td>Bantuas</td>
                                <td>TPS 1</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button green">Hijau</div>
                                </td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>Samarinda</td>
                                <td>Palaran</td>
                                <td>Bantuas</td>
                                <td>TPS 1</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button green">Hijau</div>
                                </td>
                            </tr>
                            <tr>
                                <td>05</td>
                                <td>Samarinda</td>
                                <td>Palaran</td>
                                <td>Bantuas</td>
                                <td>TPS 1</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button green">Hijau</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-sm">
                    <span class="mb-2 sm:mb-0">1 - 10 dari 40 tabel</span>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded bg-blue-500 text-white">1</button>
                        <button class="px-3 py-1 rounded">2</button>
                        <button class="px-3 py-1 rounded">Next</button>
                    </div>
                </div>
            </div>

            <div class="table-separator"></div>

            <!-- Table 2: Suara Terbanyak Kabupaten Kota Sekaltim -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('assets/dokumen.png')}}" alt="">
                        <span class="text-sm font-medium">Suara Terbanyak Kabupaten Kota Sekaltim</span>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                        <select class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm w-full sm:w-auto">
                            <option>Pilih Kab/Kota</option>
                        </select>
                        <input type="text" placeholder="Search" class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm w-full sm:w-auto">
                        <button class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm flex items-center justify-center w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KAB/KOTA</th>
                                <th>SUARA SAH</th>
                                <th>SUARA TDK SAH</th>
                                <th>JML PENG HAK PILIH</th>
                                <th>JML PENG TDK PILIH</th>
                                <th>PARTISIPASI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100">
                            <tr>
                                <td>01</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button red">30%</div>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button yellow">50%</div>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button green">70%</div>
                                </td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button red">30%</div>
                                </td>
                            </tr>
                            <tr>
                                <td>05</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button red">30%</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-sm">
                    <span class="mb-2 sm:mb-0">1 - 10 dari 40 tabel</span>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded bg-blue-500 text-white">1</button>
                        <button class="px-3 py-1 rounded">2</button>
                        <button class="px-3 py-1 rounded">Next</button>
                    </div>
                </div>
            </div>

            <div class="table-separator"></div>

            <!-- Table 3: Paslon Dengan Suara Terbanyak -->
            <div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('assets/users.png')}}" alt="">
                        <span class="text-sm font-medium">Paslon Dengan Suara Terbanyak 1-10 Sekaltim</span>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                        <select class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm w-full sm:w-auto">
                            <option>Pilih Kab/Kota</option>
                        </select>
                        <input type="text" placeholder="Search" class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm w-full sm:w-auto">
                        <button class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm flex items-center justify-center w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA PASLON</th>
                                <th>KABUPATEN/KOTA</th>
                                <th>TOTAL DPT</th>
                                <th>SUARA SAH</th>
                                <th>SUARA TIDAK SAH</th>
                                <th>PARTISIPASI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100">
                            <tr>
                                <td>01</td>
                                <td>Andi Harun<br>Saefuddin Zuhri</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button yellow">60%</div>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>Andi Harun<br>Saefuddin Zuhri</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button yellow">60%</div>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>Andi Harun<br>Saefuddin Zuhri</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button yellow">60%</div>
                                </td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>Andi Harun<br>Saefuddin Zuhri</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button yellow">60%</div>
                                </td>
                            </tr>
                            <tr>
                                <td>05</td>
                                <td>Andi Harun<br>Saefuddin Zuhri</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button yellow">60%</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-between items-center mt-4 text-sm">
                    <span class="mb-2 sm:mb-0">1 - 10 dari 3 tabel</span>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded bg-blue-500 text-white">1</button>
                        <button class="px-3 py-1 rounded">2</button>
                        <button class="px-3 py-1 rounded">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
@include('operator.layout.footer')