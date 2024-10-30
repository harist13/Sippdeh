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
                                    <th class="py-3 px-4 border-r border-white">Kab/Kota</th>
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
                                    <td class="py-3 px-4 border-r">Samarinda</td>
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
    // JavaScript to toggle between TPS, SUARA, and pilgub tables
 

   

  

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