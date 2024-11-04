@extends('operator.layout.app')

@push('styles')
    <style>
        /* slide kusus diagram bar */
        .chart-container {
            position: relative;
            width: 100%;
            padding: 20px;
        }

        /* Navigation Buttons */
        .nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            padding: 8px;
            background-color: #1e3a8a;
            color: white;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .nav-button:hover {
            background-color: #1e40af;
        }

        .nav-button-left {
            left: 10px;
        }

        .nav-button-right {
            right: 10px;
        }

        .canvas-wrapper {
            padding: 0 50px;
            height: 400px;
        }

        .chart-title {
            transition: opacity 0.3s ease;
        }

        /* slide partisipasi */

        .slide101 {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .slide101.active {
            display: block;
            opacity: 1;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in forwards;
        }

        .fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .chart-container {
                padding: 15px 10px;
            }

            .canvas-wrapper {
                padding: 0 35px;
                height: 350px;
            }

            .nav-button {
                padding: 6px;
            }

            .nav-button svg {
                width: 20px;
                height: 20px;
            }

            .chart-title {
                font-size: 14px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .chart-container {
                padding: 10px 5px;
            }

            .canvas-wrapper {
                padding: 0 25px;
                height: 300px;
            }

            .nav-button {
                padding: 4px;
            }

            .nav-button svg {
                width: 16px;
                height: 16px;
            }

            .chart-title {
                font-size: 12px;
                padding: 6px;
            }
        }

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

            .space-x-4>* {
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

            th,
            td {
                padding: 0.5rem 0.25rem;
            }

            #candidateSlider {
                width: 200% !important;
            }

            #candidateSlider>div {
                width: 100% !important;
            }

            #candidateSlider .candidate-card {
                width: calc(50% - 10px) !important;
            }
        }

    </style>
@endpush

@section('content')
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-[20px] p-8 mb-8 shadow-lg">
            <h1 class="text-4xl font-bold text-center bg-[#eceff5] rounded-lg p-4 mb-8">
                Data Perolehan Suara Calon Gubernur dan Wakil Gubernur di Tingkat Provinsi
            </h1>

            <!-- Chart Section -->
            <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                <h3 id="chartTitle" class="bg-[#3560A0] text-white text-center py-2 chart-title">Jumlah Angka Suara Masuk
                    Kabupaten/Kota</h3>

                <div class="chart-container">
                    <button class="nav-button nav-button-left" id="leftArrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button class="nav-button nav-button-right" id="rightArrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="canvas-wrapper">
                        <canvas id="voteCountChart" width="800" height="300"></canvas>
                    </div>
                </div>
            </section>

            <hr class="border-t border-gray-300 my-10">

            <section class="bg-gray-100 rounded-lg shadow-md p-6 mb-8">
                <div id="slideContainer" class="relative w-full">
                    <div class="flex flex-col">
                        <!-- Container untuk slides -->
                        <div class="flex-grow">
                            <div id="slide1" class="slide101 active">
                                <div class="mb-6 rounded-lg">
                                    <div class="flex items-start mb-6">
                                        <img src="{{ asset('assets/smd.png')}}" alt="Logo Kota" class="mr-8 w-40 h-45">
                                        <div class="flex-grow pl-10">
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Total Suara Sah</h2>
                                                    <p class="text-lg font-bold text-gray-800">2.224.562 Suara</p>
                                                </div>
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah
                                                    </h2>
                                                    <p class="text-lg font-bold text-gray-800">37.251 Suara</p>
                                                </div>
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Jumlah Pengguna Hak
                                                        Pilih</h2>
                                                    <p class="text-lg font-bold text-gray-800">2.261.813 Orang</p>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <h2 class="text-sm font-semibold text-gray-600">Jumlah Tidak Menggunakan
                                                        Hak Pilih</h2>
                                                    <p class="text-lg font-bold text-gray-800">516.831 Orang</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 text-white bg-blue-900 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col items-start w-1/3">
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 mr-2 bg-red-500"></div>
                                                <span>> 90,00% DPT » Merah</span>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 mr-2 bg-yellow-500"></div>
                                                <span>> 80,00% DPT » Kuning</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 mr-2 bg-green-500"></div>
                                                <span>> 70,00% DPT » Hijau</span>
                                            </div>
                                        </div>
                                        <div class="text-center w-1/3">
                                            <h2 class="text-xl font-bold">Tingkat Partisipasi Masyarakat</h2>
                                        </div>
                                        <div class="text-right w-1/3">
                                            <div class="text-4xl font-bold color text-green-400">81.40%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="slide2" class="slide101">
                                <!-- Konten slide 2 sama seperti sebelumnya -->
                                <div class="mb-6 rounded-lg">
                                    <div class="flex items-start mb-6">
                                        <img src="{{ asset('assets/bpp.png')}}" alt="Logo Kota" class="mr-8 w-40 h-45">
                                        <div class="flex-grow pl-10">
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Total Suara Sah</h2>
                                                    <p class="text-lg font-bold text-gray-800">2.224.562 Suara</p>
                                                </div>
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah
                                                    </h2>
                                                    <p class="text-lg font-bold text-gray-800">37.251 Suara</p>
                                                </div>
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Jumlah Pengguna Hak
                                                        Pilih</h2>
                                                    <p class="text-lg font-bold text-gray-800">2.261.813 Orang</p>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <h2 class="text-sm font-semibold text-gray-600">Jumlah Tidak Menggunakan
                                                        Hak Pilih</h2>
                                                    <p class="text-lg font-bold text-gray-800">516.831 Orang</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 text-white bg-blue-900 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col items-start w-1/3">
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 mr-2 bg-red-500"></div>
                                                <span>> 90,00% DPT » Merah</span>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 mr-2 bg-yellow-500"></div>
                                                <span>> 80,00% DPT » Kuning</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 mr-2 bg-green-500"></div>
                                                <span>> 70,00% DPT » Hijau</span>
                                            </div>
                                        </div>
                                        <div class="text-center w-1/3">
                                            <h2 class="text-xl font-bold">Tingkat Partisipasi Masyarakat</h2>
                                        </div>
                                        <div class="text-right w-1/3">
                                            <div class="text-4xl font-bold color text-yellow-400">60.40%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="slide3" class="slide101">
                                <!-- Konten slide 3 sama seperti sebelumnya -->
                                <div class="mb-6 rounded-lg">
                                    <div class="flex items-start mb-6">
                                        <img src="{{ asset('assets/btg.png')}}" alt="Logo Kota" class="mr-8 w-40 h-45">
                                        <div class="flex-grow pl-10">
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Total Suara Sah</h2>
                                                    <p class="text-lg font-bold text-gray-800">2.224.562 Suara</p>
                                                </div>
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah
                                                    </h2>
                                                    <p class="text-lg font-bold text-gray-800">37.251 Suara</p>
                                                </div>
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Jumlah Pengguna Hak
                                                        Pilih</h2>
                                                    <p class="text-lg font-bold text-gray-800">2.261.813 Orang</p>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <h2 class="text-sm font-semibold text-gray-600">Jumlah Tidak Menggunakan
                                                        Hak Pilih</h2>
                                                    <p class="text-lg font-bold text-gray-800">516.831 Orang</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 text-white bg-blue-900 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col items-start w-1/3">
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 mr-2 bg-red-500"></div>
                                                <span>> 90,00% DPT » Merah</span>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 mr-2 bg-yellow-500"></div>
                                                <span>> 80,00% DPT » Kuning</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 mr-2 bg-green-500"></div>
                                                <span>> 70,00% DPT » Hijau</span>
                                            </div>
                                        </div>
                                        <div class="text-center w-1/3">
                                            <h2 class="text-xl font-bold">Tingkat Partisipasi Masyarakat</h2>
                                        </div>
                                        <div class="text-right w-1/3">
                                            <div class="text-4xl font-bold color text-red-400">20.40%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Controls - Centered at bottom -->
                        <div class="flex justify-center items-center w-full mt-6 pb-4">
                            <div class="flex items-center gap-4">
                                <button id="prevSlide101"
                                    class="p-2 bg-blue-900 text-white rounded-full hover:bg-blue-800 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                <button id="playPauseBtn"
                                    class="p-2 bg-blue-900 text-white rounded-full hover:bg-blue-800 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-6 h-6 pause-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 9v6m4-6v6" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-6 h-6 play-icon hidden">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    </svg>
                                </button>

                                <button id="nextSlide101"
                                    class="p-2 bg-blue-900 text-white rounded-full hover:bg-blue-800 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="border-t border-gray-300 my-10">

            <!-- Data Table Section -->
            <div class="overflow-hidden mb-8">
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div class="text-black py-2 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                        Daftar 5 Kab/Kota Dengan Partisipasi Tertinggi Se-Kalimantan Timur
                    </div>
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                        <div class="flex items-center w-full sm:w-auto">

                            <select class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                                <option>Samarinda</option>
                            </select>
                        </div>
                        <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z"
                                    clip-rule="evenodd" />
                            </svg>
                            <input type="search" placeholder="Cari" name="cari"
                                class="ml-2 bg-transparent focus:outline-none text-gray-600"
                                value="{{ request()->get('cari') }}">
                        </div>
                        <button onclick="toggleModal()"
                            class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                            <img src="{{ asset('assets/icon/filter.svg') }}" alt="">
                            Filter
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border-collapse text-center">
                        <thead class="bg-[#3560a0] text-white">
                            <tr>
                                <th class="py-3 px-4 border-r border-white">NO</th>
                                <th class="py-3 px-4 border-r border-white">KAB/KOTA</th>
                                <th class="py-3 px-4 border-r border-white">SUARA SAH</th>
                                <th class="py-3 px-4 border-r border-white">SUARA TDK SAH</th>
                                <th class="py-3 px-4 border-r border-white">JML PENG HAK PILIH</th>
                                <th class="py-3 px-4 border-r border-white">JML PENG TDK PILIH</th>
                                <th class="py-3 px-4 border-r border-white">PARTISIPASI</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100">
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">01</td>
                                <td class="py-3 px-4 border-r">Samarinda</td>
                                <td class="py-3 px-4 border-r">455.345</td>
                                <td class="py-3 px-4 border-r">2.123</td>
                                <td class="py-3 px-4 border-r">582.467</td>
                                <td class="py-3 px-4 border-r">124.999</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-green">78.5%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">02</td>
                                <td class="py-3 px-4 border-r">Balikpapan</td>
                                <td class="py-3 px-4 border-r">378.912</td>
                                <td class="py-3 px-4 border-r">1.876</td>
                                <td class="py-3 px-4 border-r">498.234</td>
                                <td class="py-3 px-4 border-r">117.446</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-yellow">76.4%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">03</td>
                                <td class="py-3 px-4 border-r">Kutai Kartanegara</td>
                                <td class="py-3 px-4 border-r">412.567</td>
                                <td class="py-3 px-4 border-r">2.345</td>
                                <td class="py-3 px-4 border-r">543.210</td>
                                <td class="py-3 px-4 border-r">128.298</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-green">76.2%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">04</td>
                                <td class="py-3 px-4 border-r">Bontang</td>
                                <td class="py-3 px-4 border-r">98.765</td>
                                <td class="py-3 px-4 border-r">543</td>
                                <td class="py-3 px-4 border-r">132.456</td>
                                <td class="py-3 px-4 border-r">33.148</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-yellow">75.0%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">05</td>
                                <td class="py-3 px-4 border-r">Berau</td>
                                <td class="py-3 px-4 border-r">145.678</td>
                                <td class="py-3 px-4 border-r">876</td>
                                <td class="py-3 px-4 border-r">198.765</td>
                                <td class="py-3 px-4 border-r">52.211</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-yellow">73.7%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">06</td>
                                <td class="py-3 px-4 border-r">Paser</td>
                                <td class="py-3 px-4 border-r">132.456</td>
                                <td class="py-3 px-4 border-r">765</td>
                                <td class="py-3 px-4 border-r">187.654</td>
                                <td class="py-3 px-4 border-r">54.433</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-yellow">71.0%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">07</td>
                                <td class="py-3 px-4 border-r">Kutai Timur</td>
                                <td class="py-3 px-4 border-r">178.901</td>
                                <td class="py-3 px-4 border-r">1.234</td>
                                <td class="py-3 px-4 border-r">256.789</td>
                                <td class="py-3 px-4 border-r">76.654</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-yellow">70.2%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">08</td>
                                <td class="py-3 px-4 border-r">Penajam Paser Utara</td>
                                <td class="py-3 px-4 border-r">87.654</td>
                                <td class="py-3 px-4 border-r">432</td>
                                <td class="py-3 px-4 border-r">132.456</td>
                                <td class="py-3 px-4 border-r">44.370</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-red">66.5%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">09</td>
                                <td class="py-3 px-4 border-r">Kutai Barat</td>
                                <td class="py-3 px-4 border-r">76.543</td>
                                <td class="py-3 px-4 border-r">321</td>
                                <td class="py-3 px-4 border-r">121.234</td>
                                <td class="py-3 px-4 border-r">44.370</td>
                                <td class="py-3 px-4 border-r">
                                    <div class="participation-button participation-red">63.4%</div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 border-r">10</td>
                                <td class="py-3 px-4 border-r">Mahakam Ulu</td>
                                <td class="py-3 px-4 border-r">23.456</td>
                                <td class="py-3 px-4 border-r">123</td>
                                <td class="py-3 px-4 border-r">43.210</td>
                                <td class="py-3 px-4 border-r">19.631</td>
                                <td class="py-3 px-4 border-r">
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
                <div id="candidateSlider" class="flex transition-transform duration-500 ease-in-out">
                    <!-- Wrapper untuk semua slide -->
                    @php
                    // Hitung jumlah slide yang dibutuhkan (3 kandidat per slide)
                    $totalCandidates = count($calon);
                    $candidatesPerSlide = 3;
                    $totalSlides = ceil($totalCandidates / $candidatesPerSlide);
                    @endphp

                    @for ($slide = 0; $slide < $totalSlides; $slide++) <div
                        class="flex justify-center gap-[45px] min-w-[1080px]">
                        @for ($i = $slide * $candidatesPerSlide; $i < min(($slide + 1) * $candidatesPerSlide,
                            $totalCandidates); $i++) @php $cal=$calon[$i]; @endphp <div class="w-[330px] flex flex-col">
                            <div
                                class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] rounded-t-2xl overflow-hidden">
                                @if ($cal->foto)
                                <img class="w-full h-full object-cover"
                                    src="{{ Storage::disk('foto_calon_lokal')->url($cal->foto) }}"
                                    alt="{{ $cal->nama }} / {{ $cal->nama_wakil }}">
                                @else
                                <img class="w-full h-full object-cover" src="{{ asset('assets/default.png') }}"
                                    alt="Default Image">
                                @endif
                            </div>
                            <div
                                class="bg-[#3560a0] text-white text-center py-2 px-4 rounded-md inline-block -mt-12 ml-20 mr-20 z-10">
                                {{ $cal->posisi == 'GUBERNUR' ? $cal->provinsi->nama : $cal->kabupaten->nama }}
                            </div>
                            <div class="bg-white rounded-b-2xl p-4 shadow">
                                <h4 class="text-[#52526c] text-center font-bold mb-1">{{ $cal->nama }} /
                                    {{ $cal->nama_wakil }}</h4>
                                <p class="text-[#6b6b6b] text-center text-sm mb-2">PASLON {{ $i + 1 }}</p>
                                <div class="flex justify-center items-center text-[#008bf9]">
                                    <span class="font-medium">0%</span>
                                    <div class="mx-2 h-4 w-px bg-[#008bf9] opacity-80"></div>
                                    <span class="font-medium">0 Suara</span>
                                </div>
                            </div>
                </div>
                @endfor
            </div>
            @endfor
        </div>

        <div class="flex justify-center mt-4" id="sliderDots">
            <!-- Dots will be generated dynamically via JavaScript -->
        </div>
        </div>

        <hr class="border-t border-gray-300 my-10">

        <!-- Paslon Data Table Section -->
        <div class="overflow-hidden mb-8">
            <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div class=" text-black py-2 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                    Daftar 5 Paslon Dengan Partisipasi Tertinggi Se-Kalimantan Timur
                </div>
                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                    <div class="flex items-center w-full sm:w-auto">
                        <select class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                            <option>Samarinda</option>
                        </select>
                    </div>
                    <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="search" placeholder="Cari" name="cari"
                            class="ml-2 bg-transparent focus:outline-none text-gray-600" value="">
                    </div>
                    <button onclick="toggleModal()"
                        class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                        <img src="{{ asset('assets/icon/filter.svg') }}" alt="">
                        Filter
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border-collapse text-center">
                    <thead class="bg-[#3560a0] text-white">
                        <tr>
                            <th class="py-3 px-4 border-r border-white">NO</th>
                            <th class="py-3 px-4 border-r border-white">NAMA PASLON</th>
                            <th class="py-3 px-4 border-r border-white">KABUPATEN/KOTA</th>
                            <th class="py-3 px-4 border-r border-white">DPT</th>
                            <th class="py-3 px-4 border-r border-white">SUARA SAH</th>
                            <th class="py-3 px-4 border-r border-white">SUARA TIDAK SAH</th>
                            <th class="py-3 px-4 border-r border-white">PARTISIPASI</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100">
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">01</td>
                            <td class="py-3 px-4 border-r">Andi Harun / Rusmadi</td>
                            <td class="py-3 px-4 border-r">Samarinda</td>
                            <td class="py-3 px-4 border-r">582.467</td>
                            <td class="py-3 px-4 border-r">255.345</td>
                            <td class="py-3 px-4 border-r">2.123</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">78.5%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">02</td>
                            <td class="py-3 px-4 border-r">Isran Noor / Hadi Mulyadi</td>
                            <td class="py-3 px-4 border-r">Balikpapan</td>
                            <td class="py-3 px-4 border-r">498.234</td>
                            <td class="py-3 px-4 border-r">228.912</td>
                            <td class="py-3 px-4 border-r">1.876</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-yellow">76.4%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">03</td>
                            <td class="py-3 px-4 border-r">Andi Harun / Rusmadi</td>
                            <td class="py-3 px-4 border-r">Kutai Kartanegara</td>
                            <td class="py-3 px-4 border-r">543.210</td>
                            <td class="py-3 px-4 border-r">242.567</td>
                            <td class="py-3 px-4 border-r">2.345</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">77.2%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">04</td>
                            <td class="py-3 px-4 border-r">Isran Noor / Hadi Mulyadi</td>
                            <td class="py-3 px-4 border-r">Bontang</td>
                            <td class="py-3 px-4 border-r">132.456</td>
                            <td class="py-3 px-4 border-r">88.765</td>
                            <td class="py-3 px-4 border-r">543</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-yellow">75.0%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">05</td>
                            <td class="py-3 px-4 border-r">Andi Harun / Rusmadi</td>
                            <td class="py-3 px-4 border-r">Berau</td>
                            <td class="py-3 px-4 border-r">198.765</td>
                            <td class="py-3 px-4 border-r">135.678</td>
                            <td class="py-3 px-4 border-r">876</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-yellow">73.7%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">06</td>
                            <td class="py-3 px-4 border-r">Isran Noor / Hadi Mulyadi</td>
                            <td class="py-3 px-4 border-r">Paser</td>
                            <td class="py-3 px-4 border-r">187.654</td>
                            <td class="py-3 px-4 border-r">122.456</td>
                            <td class="py-3 px-4 border-r">765</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-yellow">71.0%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">07</td>
                            <td class="py-3 px-4 border-r">Andi Harun / Rusmadi</td>
                            <td class="py-3 px-4 border-r">Kutai Timur</td>
                            <td class="py-3 px-4 border-r">256.789</td>
                            <td class="py-3 px-4 border-r">168.901</td>
                            <td class="py-3 px-4 border-r">1.234</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-yellow">70.2%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">08</td>
                            <td class="py-3 px-4 border-r">Isran Noor / Hadi Mulyadi</td>
                            <td class="py-3 px-4 border-r">Penajam Paser Utara</td>
                            <td class="py-3 px-4 border-r">132.456</td>
                            <td class="py-3 px-4 border-r">77.654</td>
                            <td class="py-3 px-4 border-r">432</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-red">66.5%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">09</td>
                            <td class="py-3 px-4 border-r">Andi Harun / Rusmadi</td>
                            <td class="py-3 px-4 border-r">Kutai Barat</td>
                            <td class="py-3 px-4 border-r">121.234</td>
                            <td class="py-3 px-4 border-r">66.543</td>
                            <td class="py-3 px-4 border-r">321</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-red">63.4%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">10</td>
                            <td class="py-3 px-4 border-r">Isran Noor / Hadi Mulyadi</td>
                            <td class="py-3 px-4 border-r">Mahakam Ulu</td>
                            <td class="py-3 px-4 border-r">43.210</td>
                            <td class="py-3 px-4 border-r">21.456</td>
                            <td class="py-3 px-4 border-r">123</td>
                            <td class="py-3 px-4 border-r">
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
                <div class=" text-black py-2 rounded-lg mb-4 sm:mb-0 w-full sm:w-auto">
                    Daftar 5 TPS Dengan Partisipasi Tertinggi Se-Kalimantan Timur
                </div>
                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                    <div class="flex items-center w-full sm:w-auto" <select
                        class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 w-full sm:w-auto">
                        <option>Samarinda</option>
                        </select>
                    </div>
                    <div class="flex items-center rounded-lg bg-[#ECEFF5] px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.9 14.32a8 8 0 111.41-1.41l4.1 4.1a1 1 0 11-1.42 1.42l-4.1-4.1zM8 14A6 6 0 108 2a6 6 0 000 12z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="search" placeholder="Cari" name="cari"
                            class="ml-2 bg-transparent focus:outline-none text-gray-600"
                            value="{{ request()->get('cari') }}">
                    </div>
                    <button onclick="toggleModal()"
                        class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1 flex items-center justify-center w-full sm:w-auto">
                        <img src="{{ asset('assets/icon/filter.svg') }}" alt="">
                        Filter
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow-md rounded-lg overflow-hidden border-collapse text-center">
                    <thead class="bg-[#3560a0] text-white">
                        <tr>
                            <th class="py-3 px-4 border-r border-white">NO</th>
                            <th class="py-3 px-4 border-r border-white">KAB/KOTA</th>
                            <th class="py-3 px-4 border-r border-white">KECAMATAN</th>
                            <th class="py-3 px-4 border-r border-white">KELURAHAN</th>
                            <th class="py-3 px-4 border-r border-white">TPS</th>
                            <th class="py-3 px-4 border-r border-white">DPT</th>
                            <th class="py-3 px-4 border-r border-white">Suara Sah</th>
                            <th class="py-3 px-4 border-r border-white">Suara Tidak Sah</th>
                            <th class="py-3 px-4 border-r border-white">Abstain</th>
                            <th class="py-3 px-4 border-r border-white">Suara Masuk</th>
                            <th class="py-3 px-4 border-r border-white">PARTISIPASI</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100">
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">01</td>
                            <td class="py-3 px-4 border-r">Samarinda</td>
                            <td class="py-3 px-4 border-r">Samarinda Ulu</td>
                            <td class="py-3 px-4 border-r">Jawa</td>
                            <td class="py-3 px-4 border-r">TPS 01</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">98.7%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">02</td>
                            <td class="py-3 px-4 border-r">Balikpapan</td>
                            <td class="py-3 px-4 border-r">Balikpapan Selatan</td>
                            <td class="py-3 px-4 border-r">Sepinggan</td>
                            <td class="py-3 px-4 border-r">TPS 03</td>
                            <td class="py-3 px-4 border-r">285</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>

                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">97.9%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">03</td>
                            <td class="py-3 px-4 border-r">Kutai Kartanegara</td>
                            <td class="py-3 px-4 border-r">Tenggarong</td>
                            <td class="py-3 px-4 border-r">Melayu</td>
                            <td class="py-3 px-4 border-r">TPS 05</td>
                            <td class="py-3 px-4 border-r">310</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">97.4%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">04</td>
                            <td class="py-3 px-4 border-r">Bontang</td>
                            <td class="py-3 px-4 border-r">Bontang Utara</td>
                            <td class="py-3 px-4 border-r">Guntung</td>
                            <td class="py-3 px-4 border-r">TPS 02</td>
                            <td class="py-3 px-4 border-r">295</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">96.9%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">05</td>
                            <td class="py-3 px-4 border-r">Berau</td>
                            <td class="py-3 px-4 border-r">Tanjung Redeb</td>
                            <td class="py-3 px-4 border-r">Bugis</td>
                            <td class="py-3 px-4 border-r">TPS 04</td>
                            <td class="py-3 px-4 border-r">280</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">96.4%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">06</td>
                            <td class="py-3 px-4 border-r">Paser</td>
                            <td class="py-3 px-4 border-r">Tanah Grogot</td>
                            <td class="py-3 px-4 border-r">Tanah Grogot</td>
                            <td class="py-3 px-4 border-r">TPS 01</td>
                            <td class="py-3 px-4 border-r">305</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">95.7%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">07</td>
                            <td class="py-3 px-4 border-r">Kutai Timur</td>
                            <td class="py-3 px-4 border-r">Sangatta Utara</td>
                            <td class="py-3 px-4 border-r">Teluk Lingga</td>
                            <td class="py-3 px-4 border-r">TPS 03</td>
                            <td class="py-3 px-4 border-r">290</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">95.2%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">08</td>
                            <td class="py-3 px-4 border-r">Penajam Paser Utara</td>
                            <td class="py-3 px-4 border-r">Penajam</td>
                            <td class="py-3 px-4 border-r">Nipah-nipah</td>
                            <td class="py-3 px-4 border-r">TPS 02</td>
                            <td class="py-3 px-4 border-r">275</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">94.9%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">09</td>
                            <td class="py-3 px-4 border-r">Kutai Barat</td>
                            <td class="py-3 px-4 border-r">Melak</td>
                            <td class="py-3 px-4 border-r">Melak Ulu</td>
                            <td class="py-3 px-4 border-r">TPS 01</td>
                            <td class="py-3 px-4 border-r">270</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">94.4%</div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 px-4 border-r">10</td>
                            <td class="py-3 px-4 border-r">Mahakam Ulu</td>
                            <td class="py-3 px-4 border-r">Long Bagun</td>
                            <td class="py-3 px-4 border-r">Long Bagun Ilir</td>
                            <td class="py-3 px-4 border-r">TPS 01</td>
                            <td class="py-3 px-4 border-r">260</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">300</td>
                            <td class="py-3 px-4 border-r">
                                <div class="participation-button participation-green">93.8%</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Background -->
        <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <!-- Modal Content -->
            <div class="w-[393px] h-[409px] p-4 bg-white rounded-[30px] shadow-md relative">
                <!-- Close Button - Updated -->
                <button onclick="toggleModal()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-3xl font-bold w-8 h-8 flex items-center justify-center">
                    ×
                </button>

                <!-- Filter Header -->
                <div class="flex items-center space-x-2 mb-4">

                    <span class="text-lg font-semibold">Filter</span>
                </div>

                <!-- Jumlah Data Section -->
                <div class="mb-6">
                    <p class="text-sm font-semibold mb-2">Jumlah Data</p>
                    <div class="flex space-x-2">
                        <button
                            class="w-20 h-9 bg-[#3560a0] text-white font-semibold rounded-lg border border-[#c9c9c9]">10</button>
                        <button
                            class="w-20 h-9 bg-white text-[#d5d5d5] font-semibold rounded-lg border border-[#c9c9c9]">20</button>
                        <button
                            class="w-20 h-9 bg-white text-[#d5d5d5] font-semibold rounded-lg border border-[#c9c9c9]">50</button>
                        <button
                            class="w-20 h-9 bg-white text-[#d5d5d5] font-semibold rounded-lg border border-[#c9c9c9]">100</button>
                    </div>
                </div>

                <!-- Kolom Section -->
                <div class="mb-6">
                    <p class="text-sm font-semibold mb-2">Kolom</p>
                    <div class="flex space-x-2">
                        <button
                            class="w-28 h-9 bg-[#3560a0] text-white font-semibold rounded-lg border border-[#c9c9c9]">Kecamatan</button>
                        <button
                            class="w-28 h-9 bg-[#3560a0] text-white font-semibold rounded-lg border border-[#c9c9c9]">Kelurahan</button>
                    </div>
                </div>

                <!-- Tingkat Partisipasi Section -->
                <div class="mb-8">
                    <p class="text-sm font-semibold mb-2">Tingkat Partisipasi</p>
                    <div class="flex space-x-2">
                        <button
                            class="w-20 h-9 bg-[#3560a0] text-[#69d788] font-semibold rounded-lg border border-[#c9c9c9]">Hijau</button>
                        <button
                            class="w-20 h-9 bg-white text-[#ffe608] font-semibold rounded-lg border border-[#c9c9c9]">Kuning</button>
                        <button
                            class="w-20 h-9 bg-white text-[#fe756c] font-semibold rounded-lg border border-[#c9c9c9]">Merah</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between">
                    <button class="w-[177px] py-2 bg-[#3560a0] text-white text-lg font-semibold rounded-full">Reset</button>
                    <button
                        class="w-[177px] py-2 bg-[#3560a0] text-white text-lg font-semibold rounded-full">Simpan</button>
                </div>
            </div>
        </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('voteCountChart').getContext('2d');
            const titleElement = document.getElementById('chartTitle');
            const MAX_VALUE = 500000;
            let currentView = 0;
            let isHovering = false; // Add hover state tracker

            const chartData = [{
                    title: "Jumlah Angka Suara Masuk Kabupaten/Kota",
                    data: {
                        labels: ['Berau', 'Kota Balikpapan', 'Kota Bontang', 'Kota Samarinda', 'Kutai Barat',
                            'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser',
                            'Penajam Paser Utara'
                        ],
                        datasets: [{
                                label: 'Suara Masuk',
                                data: [158000, 256867, 132472, 145392, 112213, 176394, 163091, 245086,
                                    167015, 128826
                                ],
                                backgroundColor: '#3560A0',
                                barPercentage: 0.98,
                                categoryPercentage: 0.5,
                            },
                            {
                                label: 'DPT',
                                data: [179000, 324534, 169432, 155372, 179193, 213285, 103193, 320193,
                                    178456, 156183
                                ],
                                backgroundColor: '#99C9FF',
                                barPercentage: 0.98,
                                categoryPercentage: 0.5,
                            }
                        ]
                    }
                },
                {
                    title: "Jumlah Angka Suara Sah dan Tidak Sah Kabupaten/Kota",
                    data: {
                        labels: ['Berau', 'Kota Balikpapan', 'Kota Bontang', 'Kota Samarinda', 'Kutai Barat',
                            'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser',
                            'Penajam Paser Utara'
                        ],
                        datasets: [{
                                label: 'Suara Sah',
                                data: [125000, 200567, 120000, 132000, 105000, 150000, 143000, 200000,
                                    150000, 120000
                                ],
                                backgroundColor: '#B3E3C1',
                                barPercentage: 0.98,
                                categoryPercentage: 0.5,
                            },
                            {
                                label: 'Suara Tidak Sah',
                                data: [33000, 56300, 12472, 13392, 7213, 26394, 20091, 45086, 17015, 8826],
                                backgroundColor: '#CC6F85',
                                barPercentage: 0.98,
                                categoryPercentage: 0.5,
                            }
                        ]
                    }
                }
            ];

            let chart = new Chart(ctx, {
                type: 'bar',
                data: chartData[0].data,
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
                                    size: 12
                                },
                                maxRotation: 0,
                                minRotation: 0,
                                autoSkip: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: MAX_VALUE,
                            ticks: {
                                stepSize: 100000,
                                callback: function (value) {
                                    return value.toLocaleString();
                                }
                            },
                            grid: {
                                color: '#E0E0E0'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.raw;
                                    return `${context.dataset.label}: ${value.toLocaleString()} suara`;
                                }
                            }
                        },
                        legend: {
                            display: true,
                            position: 'bottom',
                            align: 'center',
                            labels: {
                                boxWidth: 15,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 10
                        }
                    },
                    onHover: (event, activeElements) => {
                        const previousState = isHovering;
                        isHovering = activeElements.length > 0;

                        // Only update if the hover state has changed
                        if (previousState !== isHovering) {
                            chart.update('none'); // Update without animation
                        }
                    },
                    animation: {
                        duration: 1,
                        onComplete: function (animation) {
                            // Don't draw percentages if hovering
                            if (isHovering) return;

                            const chartInstance = animation.chart;
                            const ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.font = 'bold 14px Arial';

                            chartInstance.data.datasets.forEach((dataset, datasetIndex) => {
                                const meta = chartInstance.getDatasetMeta(datasetIndex);

                                meta.data.forEach((bar, index) => {
                                    const data = dataset.data[index];
                                    let percentage;

                                    if (data >= MAX_VALUE) {
                                        percentage = 100;
                                    } else if (data <= 0) {
                                        percentage = 0;
                                    } else {
                                        if (currentView === 0) {
                                            percentage = Math.min(((data / MAX_VALUE) *
                                                100), 100).toFixed(1);
                                        } else {
                                            const totalVotes = chartData[1].data
                                                .datasets[0].data[index] +
                                                chartData[1].data.datasets[1].data[
                                                    index];
                                            if (totalVotes > 0) {
                                                percentage = Math.min(((data /
                                                    MAX_VALUE) * 100), 100).toFixed(
                                                    1);
                                            } else {
                                                percentage = 0;
                                            }
                                        }
                                    }

                                    const barWidth = bar.width;
                                    const barHeight = bar.height;
                                    const barX = bar.x;
                                    const barY = bar.y;

                                    if (barHeight > 30) {
                                        ctx.save();
                                        ctx.translate(barX, barY + barHeight / 2);
                                        ctx.rotate(-Math.PI / 2);
                                        ctx.fillStyle = '#000000';

                                        const percentageText = percentage === 100 ?
                                            '100%' :
                                            percentage === 0 ? '0%' :
                                            `${percentage}%`;

                                        ctx.fillText(percentageText, 0, 0);
                                        ctx.restore();
                                    }
                                });
                            });
                        }
                    }
                }
            });

            function updateView(direction = 'right') {
                titleElement.style.opacity = '0';

                setTimeout(() => {
                    if (direction === 'right') {
                        currentView = (currentView + 1) % chartData.length;
                    } else {
                        currentView = (currentView - 1 + chartData.length) % chartData.length;
                    }

                    titleElement.textContent = chartData[currentView].title;
                    chart.data = chartData[currentView].data;
                    isHovering = false; // Reset hover state on view change
                    chart.update();

                    titleElement.style.opacity = '1';
                }, 300);
            }

            document.getElementById('leftArrow').addEventListener('click', () => updateView('left'));
            document.getElementById('rightArrow').addEventListener('click', () => updateView('right'));

            // Initial setup
            updateView();
        });




        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.getElementById('candidateSlider');
            const dotsContainer = document.getElementById('sliderDots');

            // Calculate total slides based on actual slides in DOM
            const slides = slider.children;
            const totalSlides = slides.length;
            const slideWidth = 1080; // Fixed width of each slide

            let currentPosition = 0;
            let currentSlide = 0;

            // Set initial width of the slider container
            slider.style.width = `${slideWidth * totalSlides}px`;

            // Generate dots dynamically
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('button');
                dot.classList.add('mx-1', 'rounded-full', 'transition-all', 'duration-300');
                updateDotStyle(dot, i === 0);
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }

            function updateDotStyle(dot, isActive) {
                if (isActive) {
                    dot.classList.remove('bg-[#b8bcc2]', 'w-[11px]');
                    dot.classList.add('bg-[#3560A0]', 'w-[61px]');
                } else {
                    dot.classList.remove('bg-[#3560A0]', 'w-[61px]');
                    dot.classList.add('bg-[#b8bcc2]', 'w-[11px]');
                }
                dot.classList.add('h-[11px]');
            }

            function updateDots() {
                const dots = dotsContainer.children;
                for (let i = 0; i < dots.length; i++) {
                    updateDotStyle(dots[i], i === currentSlide);
                }
            }

            function goToSlide(slideIndex) {
                currentSlide = slideIndex;
                currentPosition = -slideWidth * slideIndex;
                updateSliderPosition();
            }

            function slideNext() {
                if (currentSlide < totalSlides - 1) {
                    currentSlide++;
                } else {
                    currentSlide = 0;
                }
                goToSlide(currentSlide);
            }

            function slidePrev() {
                if (currentSlide > 0) {
                    currentSlide--;
                } else {
                    currentSlide = totalSlides - 1;
                }
                goToSlide(currentSlide);
            }

            function updateSliderPosition() {
                slider.style.transition = 'transform 500ms ease-in-out';
                slider.style.transform = `translateX(${currentPosition}px)`;
                updateDots();
            }

            // Touch and drag functionality
            let isDragging = false;
            let startPos = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;

            slider.addEventListener('mousedown', dragStart);
            slider.addEventListener('touchstart', dragStart);
            slider.addEventListener('mouseup', dragEnd);
            slider.addEventListener('touchend', dragEnd);
            slider.addEventListener('mouseleave', dragEnd);
            slider.addEventListener('mousemove', drag);
            slider.addEventListener('touchmove', drag);

            function dragStart(event) {
                isDragging = true;
                startPos = getPositionX(event);
                slider.style.transition = 'none';
            }

            function drag(event) {
                if (!isDragging) return;
                event.preventDefault();
                const currentPosition = getPositionX(event);
                const diff = currentPosition - startPos;
                currentTranslate = prevTranslate + diff;

                // Add boundaries
                const minTranslate = -slideWidth * (totalSlides - 1);
                const maxTranslate = 0;
                currentTranslate = Math.max(Math.min(currentTranslate, maxTranslate), minTranslate);

                slider.style.transform = `translateX(${currentTranslate}px)`;
            }

            function dragEnd() {
                isDragging = false;
                const movedBy = currentTranslate - prevTranslate;

                // Determine which slide to snap to
                if (Math.abs(movedBy) > slideWidth / 4) {
                    if (movedBy > 0) {
                        slidePrev();
                    } else {
                        slideNext();
                    }
                } else {
                    goToSlide(currentSlide);
                }

                prevTranslate = currentTranslate;
            }

            function getPositionX(event) {
                return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
            }

            // Auto slide functionality
            let autoSlideInterval = setInterval(slideNext, 5000);

            // Reset interval on user interaction
            dotsContainer.addEventListener('click', () => {
                clearInterval(autoSlideInterval);
                autoSlideInterval = setInterval(slideNext, 5000);
            });

            slider.addEventListener('mousedown', () => {
                clearInterval(autoSlideInterval);
            });

            slider.addEventListener('touchstart', () => {
                clearInterval(autoSlideInterval);
            });

            slider.addEventListener('mouseup', () => {
                autoSlideInterval = setInterval(slideNext, 5000);
            });

            slider.addEventListener('touchend', () => {
                autoSlideInterval = setInterval(slideNext, 5000);
            });
        });

        //buat animasi sama slide di logo logo
        document.addEventListener('DOMContentLoaded', function () {
            const slideContainer = document.getElementById('slideContainer');
            const slides = document.querySelectorAll('.slide101');
            const prevBtn = document.getElementById('prevSlide101');
            const nextBtn = document.getElementById('nextSlide101');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const pauseIcon = playPauseBtn.querySelector('.pause-icon');
            const playIcon = playPauseBtn.querySelector('.play-icon');

            let currentSlide = 0;
            let isPlaying = true;
            let slideInterval = null;

            // Hide all slides except the first one
            function hideAllSlides() {
                slides.forEach(slide => {
                    slide.classList.remove('active');
                    slide.classList.add('fade-out');
                });
            }

            // Show specific slide
            function showSlide(index) {
                hideAllSlides();
                slides[index].classList.remove('fade-out');
                slides[index].classList.add('active', 'fade-in');
            }

            // Next slide function
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            // Previous slide function
            function prevSlide() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }

            // Start auto-sliding
            function startSlideShow() {
                if (slideInterval) clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            }

            // Toggle play/pause
            function togglePlayPause() {
                isPlaying = !isPlaying;
                if (isPlaying) {
                    startSlideShow();
                    pauseIcon.classList.remove('hidden');
                    playIcon.classList.add('hidden');
                } else {
                    clearInterval(slideInterval);
                    pauseIcon.classList.add('hidden');
                    playIcon.classList.remove('hidden');
                }
            }

            // Initialize slider
            hideAllSlides();
            showSlide(0);
            startSlideShow();

            // Event listeners
            prevBtn.addEventListener('click', () => {
                prevSlide();
                if (isPlaying) {
                    startSlideShow(); // Reset interval after manual navigation
                }
            });

            nextBtn.addEventListener('click', () => {
                nextSlide();
                if (isPlaying) {
                    startSlideShow(); // Reset interval after manual navigation
                }
            });

            playPauseBtn.addEventListener('click', togglePlayPause);

            // Add keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                    if (isPlaying) startSlideShow();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                    if (isPlaying) startSlideShow();
                } else if (e.key === ' ') {
                    // Space bar to toggle play/pause
                    e.preventDefault(); // Prevent page scrolling
                    togglePlayPause();
                }
            });

            // Add swipe support for touch devices
            let touchStartX = 0;
            let touchEndX = 0;

            slideContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });

            slideContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });

            function handleSwipe() {
                const swipeThreshold = 50; // Minimum distance for a swipe
                const difference = touchStartX - touchEndX;

                if (Math.abs(difference) > swipeThreshold) {
                    if (difference > 0) {
                        // Swipe left
                        nextSlide();
                    } else {
                        // Swipe right
                        prevSlide();
                    }
                    if (isPlaying) startSlideShow();
                }
            }

            // Pause on hover (optional)
            slideContainer.addEventListener('mouseenter', () => {
                if (isPlaying) {
                    clearInterval(slideInterval);
                }
            });

            slideContainer.addEventListener('mouseleave', () => {
                if (isPlaying) {
                    startSlideShow();
                }
            });
        });


        function toggleModal() {
            const modal = document.getElementById('filterModal');
            modal.classList.toggle('hidden');
        }

    </script>
@endpush