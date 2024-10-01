 @include('admin.layout.header')
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
   <style>
        .hidden {
            display: none;
        }
        #tpsBtn, #suaraBtn, #paslonBtn {
        margin-right: 1px; /* Adjust the value as needed */
    }
     .btn-group button {
        margin: 0;
        padding: 10px 20px;
        border: none;
    }
    .btn-group button:not(:last-child) {
        border-right: 1px solid #d1d5db; /* Light gray color */
    }
    </style>
 <main class="container flex-grow px-4 mx-auto mt-6">
 <!-- Table Container -->
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <!-- Table Header -->
        <div class="flex justify-between items-center mb-4">
           <div class="flex border border-gray-300 rounded-lg overflow-hidden">
    <button id="tpsBtn" class="px-4 py-2 bg-blue-700 text-white rounded-l-lg border-r border-gray-300">TPS</button>
    <button id="suaraBtn" class="px-4 py-2 bg-gray-200 text-gray-700 border-r border-gray-300">SUARA</button>
    <button id="paslonBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-r-lg">PASLON</button>
</div>


            <div class="flex items-center space-x-4">
                <div class="flex items-center bg-gray-100 rounded-md px-3 py-2">
                    <span class="text-gray-700 mr-2">Pilih Kab/Kota</span>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </div>
                <div class="relative">
                    <input type="text" placeholder="Search" class="bg-gray-100 rounded-md px-3 py-2 pl-8">
                    <i class="fas fa-search absolute left-2 top-3 text-gray-400"></i>
                </div>
                <button class="bg-gray-100 rounded-md px-3 py-2">
                    <i class="fas fa-filter text-gray-600"></i>
                </button>
            </div>
        </div>

        <!-- Table TPS (initially visible) -->
        <div id="tpsTable">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">KAB/KOTA</th>
                        <th class="px-4 py-2 text-left">KECAMATAN</th>
                        <th class="px-4 py-2 text-left">KELURAHAN</th>
                        <th class="px-4 py-2 text-left">TPS</th>
                        <th class="px-4 py-2 text-left">DPT</th>
                        <th class="px-4 py-2 text-left">PARTISIPASI</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows -->
                    <tr class="border-b">
                        <td class="px-4 py-2">01</td>
                        <td class="px-4 py-2">Samarinda</td>
                        <td class="px-4 py-2">Palaran</td>
                        <td class="px-4 py-2">Bantuas</td>
                        <td class="px-4 py-2">TPS 1</td>
                        <td class="px-4 py-2">55,345</td>
                        <td class="px-4 py-2"><span class="bg-green-400 text-white px-2 py-1 rounded">Hijau</span></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>

        <!-- Table SUARA (initially hidden) -->
        <div id="suaraTable" class="hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">KAB/KOTA</th>
                        <th class="px-4 py-2 text-left">SUARA SAH</th>
                        <th class="px-4 py-2 text-left">SUARA TDK SAH</th>
                        <th class="px-4 py-2 text-left">JML PENG HAK PILIH</th>
                        <th class="px-4 py-2 text-left">JML PENG TDK PILIH</th>
                        <th class="px-4 py-2 text-left">PARTISIPASI</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows -->
                    <tr class="border-b">
                        <td class="px-4 py-2">01</td>
                        <td class="px-4 py-2">Samarinda</td>
                        <td class="px-4 py-2">55,345</td>
                        <td class="px-4 py-2">55,345</td>
                        <td class="px-4 py-2">55,345</td>
                        <td class="px-4 py-2">55,345</td>
                        <td class="px-4 py-2"><span class="bg-red-400 text-white px-2 py-1 rounded">30%</span></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>

        <!-- Table PASLON (initially hidden) -->
        <div id="paslonTable" class="hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-700 text-white">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">NAMA PASLON</th>
                        <th class="px-4 py-2 text-left">KABUPATEN/KOTA</th>
                        <th class="px-4 py-2 text-left">TOTAL DPT</th>
                        <th class="px-4 py-2 text-left">SUARA SAH</th>
                        <th class="px-4 py-2 text-left">SUARA TIDAK SAH</th>
                        <th class="px-4 py-2 text-left">PARTISIPASI</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows -->
                    <tr class="border-b">
                        <td class="px-4 py-2">01</td>
                        <td class="px-4 py-2">Andi Harun, Safaruddin Zuhri</td>
                        <td class="px-4 py-2">Samarinda</td>
                        <td class="px-4 py-2">55,345</td>
                        <td class="px-4 py-2">55,345</td>
                        <td class="px-4 py-2">55,345</td>
                        <td class="px-4 py-2"><span class="bg-red-400 text-white px-2 py-1 rounded">30%</span></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2">02</td>
                        <td class="px-4 py-2">Rahmad Mas'ud, Bagus Susetyo</td>
                        <td class="px-4 py-2">Balikpapan</td>
                        <td class="px-4 py-2">70,324</td>
                        <td class="px-4 py-2">70,324</td>
                        <td class="px-4 py-2">70,324</td>
                        <td class="px-4 py-2"><span class="bg-yellow-400 text-white px-2 py-1 rounded">60%</span></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <span class="text-gray-600">1 - 10 dari 40 tabel</span>
        <div class="flex space-x-2">
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded">Previous</button>
            <button class="px-3 py-1 bg-blue-700 text-white rounded">1</button>
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded">2</button>
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded">Next</button>
        </div>
    </div>
</div>

</main>
<script>
    // JavaScript to toggle between TPS, SUARA, and PASLON tables
    document.getElementById('tpsBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.remove('hidden');
        document.getElementById('suaraTable').classList.add('hidden');
        document.getElementById('paslonTable').classList.add('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-blue-700', 'text-white');
        document.getElementById('suaraBtn').classList.remove('bg-blue-700', 'text-white');
        document.getElementById('suaraBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('paslonBtn').classList.remove('bg-blue-700', 'text-white');
        document.getElementById('paslonBtn').classList.add('bg-gray-200', 'text-gray-700');
    });

    document.getElementById('suaraBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.add('hidden');
        document.getElementById('suaraTable').classList.remove('hidden');
        document.getElementById('paslonTable').classList.add('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-blue-700', 'text-white');
        document.getElementById('tpsBtn').classList.remove('bg-blue-700', 'text-white');
        document.getElementById('tpsBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('paslonBtn').classList.remove('bg-blue-700', 'text-white');
        document.getElementById('paslonBtn').classList.add('bg-gray-200', 'text-gray-700');
    });

    document.getElementById('paslonBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.add('hidden');
        document.getElementById('suaraTable').classList.add('hidden');
        document.getElementById('paslonTable').classList.remove('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-blue-700', 'text-white');
        document.getElementById('tpsBtn').classList.remove('bg-blue-700', 'text-white');
        document.getElementById('tpsBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('suaraBtn').classList.remove('bg-blue-700', 'text-white');
        document.getElementById('suaraBtn').classList.add('bg-gray-200', 'text-gray-700');
    });
</script>


 @include('admin.layout.footer')