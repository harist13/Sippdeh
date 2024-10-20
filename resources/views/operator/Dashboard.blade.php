@include('operator.layout.header')
<style>
            @media (max-width: 640px) {
                .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .text-4xl {
                font-size: 1.5rem;
            }
            .rounded-[20px] {
                border-radius: 10px;
            }
            .p-8 {
                padding: 1rem;
            }
            .mb-8 {
                margin-bottom: 1rem;
            }
            .participation-button {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            .flex-col {
                flex-direction: column;
            }
            .w-[32%] {
                width: 100%;
            }
            .space-x-4 {
                margin-top: 0.5rem;
            }
            .space-x-4 > * {
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-top: 0.5rem;
            }
            .overflow-x-auto {
                overflow-x: scroll;
            }
            table {
                font-size: 0.75rem;
            }
            th, td {
                padding: 0.5rem 0.25rem;
            }
             #candidateSlider {
            width: 300% !important;
            }
            #candidateSlider > div {
                width: 100% !important;
            }
            #candidateSlider > div > div {
                width: 100% !important;
                margin-bottom: 1rem;
            }
        }
</style>
    
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-[20px] p-8 mb-8 shadow-lg">
            <h1 class="text-4xl font-bold text-center bg-[#eceff5] rounded-lg p-4 mb-8">
                Data Perolehan Suara Calon Gubernur dan Wakil Gubernur di Tingkat Provinsi
            </h1>

            <!-- Chart Section -->
            <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                <h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Angka Suara Masuk Kabupaten/Kota</h3>
                <div class="p-4 overflow-x-auto">
                    <div class="min-w-[800px]">
                        <canvas id="voteCountChart" height="300"></canvas>
                    </div>
                </div>
            </section>

            <hr class="border-t border-gray-300 my-10">

            <!-- Data Table Section -->
            <div class="overflow-hidden mb-8">
            <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div class="bg-[#3560a0] text-white py-2 px-4 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                    Daftar 10 Kab/Kota Dengan Partisipasi Tertinggi Se-Kalimantan Timur
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                    <div class="flex items-center w-full sm:w-auto">
                        <span class="mr-2 text-gray-600 whitespace-nowrap">Sort by</span>
                        <select class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                            <option>Samarinda</option>
                        </select>
                    </div>
                    <input type="text" placeholder="Search" class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                    <button class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-[#3560a0] text-white">
                            <tr>
                                <th class="py-3 px-4 text-left">NO</th>
                                <th class="py-3 px-4 text-left">KAB/KOTA</th>
                                <th class="py-3 px-4 text-left">SUARA SAH</th>
                                <th class="py-3 px-4 text-left">SUARA TDK SAH</th>
                                <th class="py-3 px-4 text-left">JML PENG HAK PILIH</th>
                                <th class="py-3 px-4 text-left">JML PENG TDK PILIH</th>
                                <th class="py-3 px-4 text-left">PARTISIPASI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100">
                            <tr class="border-b">
                                <td class="py-3 px-4">01</td>
                                <td class="py-3 px-4">Samarinda</td>
                                <td class="py-3 px-4">455.345</td>
                                <td class="py-3 px-4">2.123</td>
                                <td class="py-3 px-4">582.467</td>
                                <td class="py-3 px-4">124.999</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">78.5%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">02</td>
                                <td class="py-3 px-4">Balikpapan</td>
                                <td class="py-3 px-4">378.912</td>
                                <td class="py-3 px-4">1.876</td>
                                <td class="py-3 px-4">498.234</td>
                                <td class="py-3 px-4">117.446</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-yellow">76.4%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">03</td>
                                <td class="py-3 px-4">Kutai Kartanegara</td>
                                <td class="py-3 px-4">412.567</td>
                                <td class="py-3 px-4">2.345</td>
                                <td class="py-3 px-4">543.210</td>
                                <td class="py-3 px-4">128.298</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">76.2%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">04</td>
                                <td class="py-3 px-4">Bontang</td>
                                <td class="py-3 px-4">98.765</td>
                                <td class="py-3 px-4">543</td>
                                <td class="py-3 px-4">132.456</td>
                                <td class="py-3 px-4">33.148</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-yellow">75.0%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">05</td>
                                <td class="py-3 px-4">Berau</td>
                                <td class="py-3 px-4">145.678</td>
                                <td class="py-3 px-4">876</td>
                                <td class="py-3 px-4">198.765</td>
                                <td class="py-3 px-4">52.211</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-yellow">73.7%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">06</td>
                                <td class="py-3 px-4">Paser</td>
                                <td class="py-3 px-4">132.456</td>
                                <td class="py-3 px-4">765</td>
                                <td class="py-3 px-4">187.654</td>
                                <td class="py-3 px-4">54.433</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-yellow">71.0%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">07</td>
                                <td class="py-3 px-4">Kutai Timur</td>
                                <td class="py-3 px-4">178.901</td>
                                <td class="py-3 px-4">1.234</td>
                                <td class="py-3 px-4">256.789</td>
                                <td class="py-3 px-4">76.654</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-yellow">70.2%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">08</td>
                                <td class="py-3 px-4">Penajam Paser Utara</td>
                                <td class="py-3 px-4">87.654</td>
                                <td class="py-3 px-4">432</td>
                                <td class="py-3 px-4">132.456</td>
                                <td class="py-3 px-4">44.370</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-red">66.5%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">09</td>
                                <td class="py-3 px-4">Kutai Barat</td>
                                <td class="py-3 px-4">76.543</td>
                                <td class="py-3 px-4">321</td>
                                <td class="py-3 px-4">121.234</td>
                                <td class="py-3 px-4">44.370</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-red">63.4%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">10</td>
                                <td class="py-3 px-4">Mahakam Ulu</td>
                                <td class="py-3 px-4">23.456</td>
                                <td class="py-3 px-4">123</td>
                                <td class="py-3 px-4">43.210</td>
                                <td class="py-3 px-4">19.631</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-red">54.6%</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <hr class="border-t border-gray-300 my-10">

            <!-- Candidate Cards Section -->
              <div class="relative overflow-hidden w-[1080px] mx-auto">
            <div id="candidateSlider" class="flex transition-transform duration-500 ease-in-out" style="width: 2160px;">
                <!-- First set of cards -->
                <div class="flex justify-center gap-[45px] w-[1080px]">
                    <!-- Andi Harun / Saefuddin Zuhri -->
                    <div class="w-[330px] flex flex-col">
                        <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] rounded-t-2xl overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{asset('assets/contoh.png')}}" alt="Andi Harun / Saefuddin Zuhri">
                        </div>
                        <div class="bg-[#3560a0] text-white text-center py-2 px-4 rounded-md inline-block -mt-12 ml-20 mr-20 z-10">
                            Samarinda
                        </div>
                        <div class="bg-white rounded-b-2xl p-4 shadow">
                            <h4 class="text-[#52526c] text-center font-bold mb-1">Andi Harun / Saefuddin Zuhri</h4>
                            <p class="text-[#6b6b6b] text-center text-sm mb-2">PASLON 1</p>
                            <div class="flex justify-center items-center text-[#008bf9]">
                                <span class="font-medium">21,69%</span>
                                <div class="mx-2 h-4 w-px bg-[#008bf9] opacity-80"></div>
                                <span class="font-medium">288.131 Suara</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kotak Kosong -->
                    <div class="w-[330px] flex flex-col">
                        <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] rounded-t-2xl"></div>
                        <div class="bg-[#3560a0] text-white text-center py-2 px-4 rounded-md inline-block -mt-12 ml-20 mr-20 z-10">
                            Samarinda
                        </div>
                        <div class="bg-white rounded-b-2xl p-4 shadow">
                            <h4 class="text-[#52526c] text-center font-bold mb-1">Kotak Kosong</h4>
                            <p class="text-[#6b6b6b] text-center text-sm mb-2">PASLON 2</p>
                            <div class="flex justify-center items-center text-[#008bf9]">
                                <span class="font-medium">21,69%</span>
                                <div class="mx-2 h-4 w-px bg-[#008bf9] opacity-80"></div>
                                <span class="font-medium">288.131 Suara</span>
                            </div>
                        </div>
                    </div>

                    <!-- Muhammad Sabani / Syukri Wahid -->
                    <div class="w-[330px] flex flex-col">
                        <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] rounded-t-2xl overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{asset('assets/contoh.png')}}" alt="Muhammad Sabani / Syukri Wahid">
                        </div>
                        <div class="bg-[#3560a0] text-white text-center py-2 px-4 rounded-md inline-block -mt-12 ml-20 mr-20 z-10">
                            Samarinda
                        </div>
                        <div class="bg-white rounded-b-2xl p-4 shadow">
                            <h4 class="text-[#52526c] text-center font-bold mb-1">Muhammad Sabani / Syukri Wahid</h4>
                            <p class="text-[#6b6b6b] text-center text-sm mb-2">PASLON 1</p>
                            <div class="flex justify-center items-center text-[#008bf9]">
                                <span class="font-medium">21,69%</span>
                                <div class="mx-2 h-4 w-px bg-[#008bf9] opacity-80"></div>
                                <span class="font-medium">288.131 Suara</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Duplicate set of cards for continuous sliding -->
                <div class="flex justify-center gap-[45px] w-[1080px]">
                    <!-- Andi Harun / Saefuddin Zuhri -->
                    <div class="w-[330px] flex flex-col">
                        <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] rounded-t-2xl overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{asset('assets/contoh.png')}}" alt="Andi Harun / Saefuddin Zuhri">
                        </div>
                        <div class="bg-[#3560a0] text-white text-center py-2 px-4 rounded-md inline-block -mt-12 ml-20 mr-20 z-10">
                            Samarinda
                        </div>
                        <div class="bg-white rounded-b-2xl p-4 shadow">
                            <h4 class="text-[#52526c] text-center font-bold mb-1">Andi Harun / Saefuddin Zuhri</h4>
                            <p class="text-[#6b6b6b] text-center text-sm mb-2">PASLON 1</p>
                            <div class="flex justify-center items-center text-[#008bf9]">
                                <span class="font-medium">21,69%</span>
                                <div class="mx-2 h-4 w-px bg-[#008bf9] opacity-80"></div>
                                <span class="font-medium">288.131 Suara</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kotak Kosong -->
                    <div class="w-[330px] flex flex-col">
                        <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] rounded-t-2xl"></div>
                        <div class="bg-[#3560a0] text-white text-center py-2 px-4 rounded-md inline-block -mt-12 ml-20 mr-20 z-10">
                            Samarinda
                        </div>
                        <div class="bg-white rounded-b-2xl p-4 shadow">
                            <h4 class="text-[#52526c] text-center font-bold mb-1">Kotak Kosong</h4>
                            <p class="text-[#6b6b6b] text-center text-sm mb-2">PASLON 2</p>
                            <div class="flex justify-center items-center text-[#008bf9]">
                                <span class="font-medium">21,69%</span>
                                <div class="mx-2 h-4 w-px bg-[#008bf9] opacity-80"></div>
                                <span class="font-medium">288.131 Suara</span>
                            </div>
                        </div>
                    </div>

                    <!-- Muhammad Sabani / Syukri Wahid -->
                    <div class="w-[330px] flex flex-col">
                        <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] rounded-t-2xl overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{asset('assets/contoh.png')}}" alt="Muhammad Sabani / Syukri Wahid">
                        </div>
                        <div class="bg-[#3560a0] text-white text-center py-2 px-4 rounded-md inline-block -mt-12 ml-20 mr-20 z-10">
                            Samarinda
                        </div>
                        <div class="bg-white rounded-b-2xl p-4 shadow">
                            <h4 class="text-[#52526c] text-center font-bold mb-1">Muhammad Sabani / Syukri Wahid</h4>
                            <p class="text-[#6b6b6b] text-center text-sm mb-2">PASLON 1</p>
                            <div class="flex justify-center items-center text-[#008bf9]">
                                <span class="font-medium">21,69%</span>
                                <div class="mx-2 h-4 w-px bg-[#008bf9] opacity-80"></div>
                                <span class="font-medium">288.131 Suara</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


                <div class="flex justify-center mt-4">
                    <button id="prevBtn" class="w-[61px] h-[11px] rounded-full bg-[#3560A0] mx-1"></button>
                    <button id="nextBtn" class="w-[11px] h-[11px] rounded-full bg-[#b8bcc2] mx-1"></button>
                </div>
            </div>

            <hr class="border-t border-gray-300 my-10">

            <!-- Paslon Data Table Section -->
            <div class="overflow-hidden mb-8">
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div class="bg-[#3560a0] text-white py-2 px-4 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                        Daftar 10 Kab/Kota Dengan Partisipasi Tertinggi Se-Kalimantan Timur
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <div class="flex items-center w-full sm:w-auto">
                            <span class="mr-2 text-gray-600 whitespace-nowrap">Sort by</span>
                            <select class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                                <option>Samarinda</option>
                            </select>
                        </div>
                        <input type="text" placeholder="Search" class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                        <button class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                            <thead class="bg-[#3560a0] text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left">NO</th>
                                    <th class="py-3 px-4 text-left">NAMA PASLON</th>
                                    <th class="py-3 px-4 text-left">KABUPATEN/KOTA</th>
                                    <th class="py-3 px-4 text-left">TOTAL DPT</th>
                                    <th class="py-3 px-4 text-left">SUARA SAH</th>
                                    <th class="py-3 px-4 text-left">SUARA TIDAK SAH</th>
                                    <th class="py-3 px-4 text-left">PARTISIPASI</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-100">
                                <tr class="border-b">
                                    <td class="py-3 px-4">01</td>
                                    <td class="py-3 px-4">Andi Harun / Rusmadi</td>
                                    <td class="py-3 px-4">Samarinda</td>
                                    <td class="py-3 px-4">582.467</td>
                                    <td class="py-3 px-4">255.345</td>
                                    <td class="py-3 px-4">2.123</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-green">78.5%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">02</td>
                                    <td class="py-3 px-4">Isran Noor / Hadi Mulyadi</td>
                                    <td class="py-3 px-4">Balikpapan</td>
                                    <td class="py-3 px-4">498.234</td>
                                    <td class="py-3 px-4">228.912</td>
                                    <td class="py-3 px-4">1.876</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-yellow">76.4%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">03</td>
                                    <td class="py-3 px-4">Andi Harun / Rusmadi</td>
                                    <td class="py-3 px-4">Kutai Kartanegara</td>
                                    <td class="py-3 px-4">543.210</td>
                                    <td class="py-3 px-4">242.567</td>
                                    <td class="py-3 px-4">2.345</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-green">77.2%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">04</td>
                                    <td class="py-3 px-4">Isran Noor / Hadi Mulyadi</td>
                                    <td class="py-3 px-4">Bontang</td>
                                    <td class="py-3 px-4">132.456</td>
                                    <td class="py-3 px-4">88.765</td>
                                    <td class="py-3 px-4">543</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-yellow">75.0%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">05</td>
                                    <td class="py-3 px-4">Andi Harun / Rusmadi</td>
                                    <td class="py-3 px-4">Berau</td>
                                    <td class="py-3 px-4">198.765</td>
                                    <td class="py-3 px-4">135.678</td>
                                    <td class="py-3 px-4">876</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-yellow">73.7%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">06</td>
                                    <td class="py-3 px-4">Isran Noor / Hadi Mulyadi</td>
                                    <td class="py-3 px-4">Paser</td>
                                    <td class="py-3 px-4">187.654</td>
                                    <td class="py-3 px-4">122.456</td>
                                    <td class="py-3 px-4">765</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-yellow">71.0%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">07</td>
                                    <td class="py-3 px-4">Andi Harun / Rusmadi</td>
                                    <td class="py-3 px-4">Kutai Timur</td>
                                    <td class="py-3 px-4">256.789</td>
                                    <td class="py-3 px-4">168.901</td>
                                    <td class="py-3 px-4">1.234</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-yellow">70.2%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">08</td>
                                    <td class="py-3 px-4">Isran Noor / Hadi Mulyadi</td>
                                    <td class="py-3 px-4">Penajam Paser Utara</td>
                                    <td class="py-3 px-4">132.456</td>
                                    <td class="py-3 px-4">77.654</td>
                                    <td class="py-3 px-4">432</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-red">66.5%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">09</td>
                                    <td class="py-3 px-4">Andi Harun / Rusmadi</td>
                                    <td class="py-3 px-4">Kutai Barat</td>
                                    <td class="py-3 px-4">121.234</td>
                                    <td class="py-3 px-4">66.543</td>
                                    <td class="py-3 px-4">321</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-red">63.4%</div>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 px-4">10</td>
                                    <td class="py-3 px-4">Isran Noor / Hadi Mulyadi</td>
                                    <td class="py-3 px-4">Mahakam Ulu</td>
                                    <td class="py-3 px-4">43.210</td>
                                    <td class="py-3 px-4">21.456</td>
                                    <td class="py-3 px-4">123</td>
                                    <td class="py-3 px-4">
                                        <div class="participation-button participation-red">54.6%</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>

            <hr class="border-t border-gray-300 my-10">

        <!-- TPS Data Table Section -->
          <div class="overflow-hidden mb-8">
            <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div class="bg-[#3560a0] text-white py-2 px-4 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                    Daftar 10 Kab/Kota Dengan Partisipasi Tertinggi Se-Kalimantan Timur
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                    <div class="flex items-center w-full sm:w-auto">
                        <span class="mr-2 text-gray-600 whitespace-nowrap">Sort by</span>
                        <select class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                            <option>Samarinda</option>
                        </select>
                    </div>
                    <input type="text" placeholder="Search" class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                    <button class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-[#3560a0] text-white">
                            <tr>
                                <th class="py-3 px-4 text-left">NO</th>
                                <th class="py-3 px-4 text-left">KAB/KOTA</th>
                                <th class="py-3 px-4 text-left">KECAMATAN</th>
                                <th class="py-3 px-4 text-left">KELURAHAN</th>
                                <th class="py-3 px-4 text-left">TPS</th>
                                <th class="py-3 px-4 text-left">DPT</th>
                                <th class="py-3 px-4 text-left">PARTISIPASI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100">
                            <tr class="border-b">
                                <td class="py-3 px-4">01</td>
                                <td class="py-3 px-4">Samarinda</td>
                                <td class="py-3 px-4">Samarinda Ulu</td>
                                <td class="py-3 px-4">Jawa</td>
                                <td class="py-3 px-4">TPS 01</td>
                                <td class="py-3 px-4">300</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">98.7%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">02</td>
                                <td class="py-3 px-4">Balikpapan</td>
                                <td class="py-3 px-4">Balikpapan Selatan</td>
                                <td class="py-3 px-4">Sepinggan</td>
                                <td class="py-3 px-4">TPS 03</td>
                                <td class="py-3 px-4">285</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">97.9%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">03</td>
                                <td class="py-3 px-4">Kutai Kartanegara</td>
                                <td class="py-3 px-4">Tenggarong</td>
                                <td class="py-3 px-4">Melayu</td>
                                <td class="py-3 px-4">TPS 05</td>
                                <td class="py-3 px-4">310</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">97.4%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">04</td>
                                <td class="py-3 px-4">Bontang</td>
                                <td class="py-3 px-4">Bontang Utara</td>
                                <td class="py-3 px-4">Guntung</td>
                                <td class="py-3 px-4">TPS 02</td>
                                <td class="py-3 px-4">295</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">96.9%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">05</td>
                                <td class="py-3 px-4">Berau</td>
                                <td class="py-3 px-4">Tanjung Redeb</td>
                                <td class="py-3 px-4">Bugis</td>
                                <td class="py-3 px-4">TPS 04</td>
                                <td class="py-3 px-4">280</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">96.4%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">06</td>
                                <td class="py-3 px-4">Paser</td>
                                <td class="py-3 px-4">Tanah Grogot</td>
                                <td class="py-3 px-4">Tanah Grogot</td>
                                <td class="py-3 px-4">TPS 01</td>
                                <td class="py-3 px-4">305</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">95.7%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">07</td>
                                <td class="py-3 px-4">Kutai Timur</td>
                                <td class="py-3 px-4">Sangatta Utara</td>
                                <td class="py-3 px-4">Teluk Lingga</td>
                                <td class="py-3 px-4">TPS 03</td>
                                <td class="py-3 px-4">290</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">95.2%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">08</td>
                                <td class="py-3 px-4">Penajam Paser Utara</td>
                                <td class="py-3 px-4">Penajam</td>
                                <td class="py-3 px-4">Nipah-nipah</td>
                                <td class="py-3 px-4">TPS 02</td>
                                <td class="py-3 px-4">275</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">94.9%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">09</td>
                                <td class="py-3 px-4">Kutai Barat</td>
                                <td class="py-3 px-4">Melak</td>
                                <td class="py-3 px-4">Melak Ulu</td>
                                <td class="py-3 px-4">TPS 01</td>
                                <td class="py-3 px-4">270</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">94.4%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">10</td>
                                <td class="py-3 px-4">Mahakam Ulu</td>
                                <td class="py-3 px-4">Long Bagun</td>
                                <td class="py-3 px-4">Long Bagun Ilir</td>
                                <td class="py-3 px-4">TPS 01</td>
                                <td class="py-3 px-4">260</td>
                                <td class="py-3 px-4">
                                    <div class="participation-button participation-green">93.8%</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

@include('operator.layout.footer')
<script>

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('voteCountChart').getContext('2d');
    
    function createChart(isMobile) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Berau', 'Kota Balikpapan', 'Kota Bontang', 'Kota Samarinda', 'Kutai Barat', 'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser', 'Penajam Paser Utara'],
                datasets: [
                    {
                        label: 'Suara Masuk',
                        data: [158000, 256867, 132472, 145392, 112213, 176394, 163091, 245086, 167015, 128826],
                        backgroundColor: '#3560A0',
                        barPercentage: 0.98,
                        categoryPercentage: 0.5,
                    },
                    {
                        label: 'DPT',
                        data: [179000, 324534, 169432, 155372, 179193, 213285, 103193, 320193, 178456, 156183],
                        backgroundColor: '#99C9FF',
                        barPercentage: 0.98,
                        categoryPercentage: 0.5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: isMobile ? 8 : 12
                            },
                            maxRotation: isMobile ? 0 : 0,
                            minRotation: isMobile ? 0 : 0,
                            autoSkip: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 500000,
                        ticks: {
                            stepSize: 100000,
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        },
                        grid: {
                            color: '#E0E0E0'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            boxWidth: 15,
                            padding: isMobile ? 10 : 15,
                            font: {
                                size: isMobile ? 10 : 12
                            }
                        }
                    },
                    title: {
                        display: false
                    }
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10
                    }
                }
            }
        });
    }

    let chart;
    let isMobile = window.innerWidth < 768;

    function updateChart() {
        if (chart) {
            chart.destroy();
        }
        chart = createChart(isMobile);
    }

    updateChart();

    window.addEventListener('resize', function() {
        let newIsMobile = window.innerWidth < 768;
        if (newIsMobile !== isMobile) {
            isMobile = newIsMobile;
            updateChart();
        }
    });
});




document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('candidateSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let currentPosition = 0;
    const slideWidth = slider.clientWidth / 2;
    const totalSlides = 2;

    function slideRight() {
        if (currentPosition > -slideWidth * (totalSlides - 1)) {
            currentPosition -= slideWidth;
            updateSliderPosition();
        }
    }

    function slideLeft() {
        if (currentPosition < 0) {
            currentPosition += slideWidth;
            updateSliderPosition();
        }
    }

    function updateSliderPosition() {
        slider.style.transition = 'transform 500ms ease-in-out';
        slider.style.transform = `translateX(${currentPosition}px)`;
        updateButtons();
    }

    function updateButtons() {
        if (currentPosition === 0) {
            prevBtn.classList.add('bg-[#3560A0]', 'w-[61px]');
            prevBtn.classList.remove('bg-[#b8bcc2]', 'w-[11px]');
            nextBtn.classList.add('bg-[#b8bcc2]', 'w-[11px]');
            nextBtn.classList.remove('bg-[#3560A0]', 'w-[61px]');
        } else {
            prevBtn.classList.add('bg-[#b8bcc2]', 'w-[11px]');
            prevBtn.classList.remove('bg-[#3560A0]', 'w-[61px]');
            nextBtn.classList.add('bg-[#3560A0]', 'w-[61px]');
            nextBtn.classList.remove('bg-[#b8bcc2]', 'w-[11px]');
        }
    }

    let autoSlideInterval = setInterval(slideRight, 5000);

    prevBtn.addEventListener('click', () => {
        clearInterval(autoSlideInterval);
        slideLeft();
        autoSlideInterval = setInterval(slideRight, 5000);
    });

    nextBtn.addEventListener('click', () => {
        clearInterval(autoSlideInterval);
        slideRight();
        autoSlideInterval = setInterval(slideRight, 5000);
    });

});

    
</script>