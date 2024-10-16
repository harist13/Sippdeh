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
        <!-- Table Header -->
        <div class="flex flex-wrap-mobile justify-between items-center mb-4">
            <div class="flex items-center space-x-2 w-full-mobile mb-4 sm:mb-0">
                <div class="flex border border-gray-300 rounded-lg overflow-hidden flex-grow">
                    <button id="tpsBtn" class="px-4 py-2 bg-[#3560A0] text-white rounded-l-lg border-r border-gray-300 flex-grow">TPS</button>
                    <button id="suaraBtn" class="px-4 py-2 bg-gray-200 text-gray-700 border-r border-gray-300 flex-grow">SUARA</button>
                    <button id="pilgubBtn" class="px-4 py-2 bg-gray-200 text-gray-700 border-r border-gray-300 flex-grow">PILGUB</button>
                    <button id="pilkadaBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-r-lg flex-grow">PILKADA</button>
                </div>
               <button id="exportBtn" class="px-4 py-2 bg-[#ee3c46] text-white rounded-lg whitespace-nowrap flex items-center space-x-2">
                    <img src="{{ asset('assets/icon/download.png')}}">
                    <span>Export</span>
                </button>
            </div>


          
            

            <div class="flex flex-wrap-mobile items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full-mobile sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <button id="dropdownButton" class="flex items-center justify-between bg-gray-100 rounded-md px-3 py-2 w-full sm:w-auto">
                        <span class="text-gray-700 mr-2">Pilih Kab/Kota</span>
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </button>
                    <div id="dropdownMenu" class="absolute mt-2 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden z-10">
                        <ul class="py-1 text-gray-700">
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Samarinda</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Balikpapan</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Bontang</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Kutai Kartanegara</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Kutai Timur</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Kutai Barat</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Berau</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Paser</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Penajam Paser Utara</li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Mahakam Ulu</li>
                        </ul>
                    </div>
                </div>
                <div class="relative w-full sm:w-auto">
                    <input type="text" placeholder="Search" class="bg-gray-100 rounded-md px-3 py-2 pl-8 w-full">
                    <i class="fas fa-search absolute left-2 top-3 text-gray-400"></i>
                </div>
                <div class="relative w-full sm:w-auto">
                    <button id="filterButton" class="flex items-center justify-between bg-gray-100 rounded-md px-3 py-2 w-full sm:w-auto">
                        <i class="fas fa-filter text-gray-600 mr-2"></i>
                        <span class="text-gray-700">Filter</span>
                        <i class="fas fa-chevron-down text-gray-400 ml-2"></i>
                    </button>
                    <div id="filterMenu" class="absolute mt-2 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden z-10">
                        <ul class="py-1 text-gray-700">
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                <span class="text-green-500 font-semibold">Hijau</span>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                <span class="text-yellow-500 font-semibold">Kuning</span>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                <span class="text-red-500 font-semibold">Merah</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table TPS (initially visible) -->
        <div id="tpsTable" class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">KAB/KOTA</th>
                        <th class="px-4 py-2 text-left">KECAMATAN</th>
                        <th class="px-4 py-2 text-left">KELURAHAN</th>
                        <th class="px-4 py-2 text-left">TPS</th>
                        <th class="px-4 py-2 text-left">DPT</th>
                        <th class="px-4 py-2 text-left">PARTISIPASI</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
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
        <div id="suaraTable" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">KAB/KOTA</th>
                        <th class="px-4 py-2 text-left">SUARA SAH</th>
                        <th class="px-4 py-2 text-left">SUARA TDK SAH</th>
                        <th class="px-4 py-2 text-left">JML PENG HAK PILIH</th>
                        <th class="px-4 py-2 text-left">JML PENG TDK PILIH</th>
                        <th class="px-4 py-2 text-left">PARTISIPASI</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
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

        <!-- Table pilgub (initially hidden) -->
        <div id="pilgubTable" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">NAMA PASLON</th>
                        <th class="px-4 py-2 text-left">KABUPATEN/KOTA</th>
                        <th class="px-4 py-2 text-left">TOTAL DPT</th>
                        <th class="px-4 py-2 text-left">SUARA SAH</th>
                        <th class="px-4 py-2 text-left">SUARA TIDAK SAH</th>
                        <th class="px-4 py-2 text-left">PARTISIPASI</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
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


        <!-- Table pilkada (initially hidden) -->
        <div id="pilkadaTable" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#3560A0] text-white">
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">NAMA PASLON</th>
                        <th class="px-4 py-2 text-left">KABUPATEN/KOTA</th>
                        <th class="px-4 py-2 text-left">TOTAL DPT</th>
                        <th class="px-4 py-2 text-left">SUARA SAH</th>
                        <th class="px-4 py-2 text-left">SUARA TIDAK SAH</th>
                        <th class="px-4 py-2 text-left">PARTISIPASI</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
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
        <div class="flex flex-wrap-mobile justify-between items-center mt-4">
            <span class="text-gray-600 w-full sm:w-auto text-center sm:text-left mb-2 sm:mb-0">1 - 10 dari 40 tabel</span>
            <div class="flex space-x-2 w-full sm:w-auto justify-center sm:justify-end">
                <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded">Previous</button>
                <button class="px-3 py-1 bg-[#3560A0] text-white rounded">1</button>
                <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded">2</button>
                <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded">Next</button>
            </div>
        </div>
    </div>
</main>

<script>
    // JavaScript to toggle between TPS, SUARA, and pilgub tables
    document.getElementById('tpsBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.remove('hidden');
        document.getElementById('suaraTable').classList.add('hidden');
        document.getElementById('pilgubTable').classList.add('hidden');
        document.getElementById('pilkadaTable').classList.add('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-[#3560A0]', 'text-white');
        document.getElementById('suaraBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('suaraBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('pilgubBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('pilgubBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('pilkadaBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('pilkadaBtn').classList.add('bg-gray-200', 'text-gray-700');
    });

    document.getElementById('suaraBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.add('hidden');
        document.getElementById('suaraTable').classList.remove('hidden');
        document.getElementById('pilgubTable').classList.add('hidden');
        document.getElementById('pilkadaTable').classList.add('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-[#3560A0]', 'text-white');
        document.getElementById('tpsBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('tpsBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('pilgubBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('pilgubBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('pilkadaBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('pilkadaBtn').classList.add('bg-gray-200', 'text-gray-700');
    });

    document.getElementById('pilgubBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.add('hidden');
        document.getElementById('suaraTable').classList.add('hidden');
        document.getElementById('pilgubTable').classList.remove('hidden');
        document.getElementById('pilkadaTable').classList.add('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-[#3560A0]', 'text-white');
        document.getElementById('tpsBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('tpsBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('suaraBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('suaraBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('pilkadaBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('pilkadaBtn').classList.add('bg-gray-200', 'text-gray-700');
    });

     document.getElementById('pilkadaBtn').addEventListener('click', function() {
        document.getElementById('tpsTable').classList.add('hidden');
        document.getElementById('suaraTable').classList.add('hidden');
        document.getElementById('pilgubTable').classList.add('hidden');
        document.getElementById('pilkadaTable').classList.remove('hidden');
        this.classList.remove('bg-gray-200', 'text-gray-700');
        this.classList.add('bg-[#3560A0]', 'text-white');
        document.getElementById('tpsBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('tpsBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('pilgubBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('pilgubBtn').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('suaraBtn').classList.remove('bg-[#3560A0]', 'text-white');
        document.getElementById('suaraBtn').classList.add('bg-gray-200', 'text-gray-700');
    });

            // Dropdown functionality
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownButton.addEventListener('click', function() {
            dropdownMenu.classList.toggle('hidden');
        });

        // Close the dropdown when clicking outside of it
        document.addEventListener('click', function(event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // Optional: Update button text when an option is selected
        const dropdownItems = dropdownMenu.querySelectorAll('li');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                dropdownButton.querySelector('span').textContent = this.textContent;
                dropdownMenu.classList.add('hidden');
            });
        });
            
            // Filter dropdown functionality
        const filterButton = document.getElementById('filterButton');
        const filterMenu = document.getElementById('filterMenu');

        filterButton.addEventListener('click', function() {
            filterMenu.classList.toggle('hidden');
        });

        // Close the filter dropdown when clicking outside of it
        document.addEventListener('click', function(event) {
            if (!filterButton.contains(event.target) && !filterMenu.contains(event.target)) {
                filterMenu.classList.add('hidden');
            }
        });

        // Update filter button text when an option is selected
        const filterItems = filterMenu.querySelectorAll('li');
        filterItems.forEach(item => {
            item.addEventListener('click', function() {
                const colorName = this.textContent.trim();
                filterButton.querySelector('span').textContent = colorName;
                filterButton.querySelector('span').className = this.querySelector('span').className;
                filterMenu.classList.add('hidden');
                // You can add filtering logic here based on the selected color
            });
        });
</script>

@include('admin.layout.footer')