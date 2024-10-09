@include('operator.layout.header')
 <style>
        .table-separator {
            height: 3px;
            background-color: #d9d9d9;
            margin: 2rem 0;
        }
        .custom-table th, .custom-table td {
            padding: 0.75rem 1rem;
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
        .participation-button.green {
            background-color: #10B981;
        }
        .participation-button.yellow {
            background-color: #F59E0B;
        }
        .participation-button.red {
            background-color: #EF4444;
        }
    </style>
<body class="bg-gray-100 font-sans">
   

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
           <div class="custom-title-container">
    <h2 class="custom-title">
        Data Perolehan Suara Calon Gubernur dan Wakil Gubernur<br>
        di Tingkat Provinsi
    </h2>
</div>
            
            <!-- Table 1: Partisipasi TPS Terbaik -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-sm font-medium">Partisipasi TPS Terbaik 1-10 Sekaltim</span>
                    </div>
                    <div class="flex space-x-2">
                        <select class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm">
                            <option>Pilih Kab/Kota</option>
                        </select>
                        <input type="text" placeholder="Search" class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm">
                        <button class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full custom-table">
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
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>Samarinda</td>
                                <td>Palaran</td>
                                <td>Bantuas</td>
                                <td>TPS 1</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button green">88%</div>
                                </td>
                            </tr>
                            <!-- More rows would be added here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="flex justify-between items-center mt-4 text-sm">
                    <span>1 - 10 dari 40 tabel</span>
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
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-sm font-medium">Suara Terbanyak Kabupaten Kota Sekaltim</span>
                    </div>
                    <div class="flex space-x-2">
                        <select class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm">
                            <option>Pilih Kab/Kota</option>
                        </select>
                        <input type="text" placeholder="Search" class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm">
                        <button class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full custom-table">
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
                        <tbody>
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
                            <!-- More rows would be added here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="flex justify-between items-center mt-4 text-sm">
                    <span>1 - 10 dari 40 tabel</span>
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
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-sm font-medium">Paslon Dengan Suara Terbanyak 1-10 Sekaltim</span>
                    </div>
                    <div class="flex space-x-2">
                        <select class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm">
                            <option>Pilih Kab/Kota</option>
                        </select>
                        <input type="text" placeholder="Search" class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm">
                        <button class="bg-gray-100 text-gray-700 rounded px-3 py-2 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full custom-table">
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
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>Andi Harun<br/>Saefuddin Zuhri</td>
                                <td>Samarinda</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td>55,345</td>
                                <td class="participation-cell">
                                    <div class="participation-button yellow">60%</div>
                                </td>
                            </tr>
                            <!-- More rows would be added here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="flex justify-between items-center mt-4 text-sm">
                    <span>1 - 10 dari 3 tabel</span>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 rounded bg-blue-500 text-white">1</button>
                        <button class="px-3 py-1 rounded">2</button>
                        <button class="px-3 py-1 rounded">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('operator.layout.footer')