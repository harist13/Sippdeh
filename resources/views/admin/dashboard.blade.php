@extends('admin.layout.app')

@push('styles')
    <style>
        /* slide kusus diagram bar */
        .chart-container {
                position: relative;
                width: 100%;
                padding: 20px;
            }
            
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
            }

            .chart-title {
                transition: opacity 0.3s ease;
            }

        /* slide kusus paslon */
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            transition: opacity 3s ease-in-out;
        }
        .slide.active {
            opacity: 1;
        }
        #slideContainer {
            max-width: 1100px;
            height: 320px;
            padding-bottom: 2rem;
        }

        .text-right h2 {
            font-size: 1.25rem;
        }

        .text-right .text-4xl {
            font-size: 2rem;
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
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in forwards;
        }

        .fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }
    </style>
@endpush

@push('head-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
@endpush

@section('content')
    <main class="bg-white shadow-lg rounded-lg p-8 max-w-7xl mx-auto my-8">
        <section class="rounded-lg p-4 mb-8">
            @php
                // Urutkan paslon berdasarkan total suara tertinggi
                $paslon_sorted = $calon->sortByDesc('total_suara');
                $paslon1 = $calon->first(); // Selalu ambil paslon pertama
                $paslon2 = $calon->skip(1)->first(); // Selalu ambil paslon kedua
                
                // Jika tidak ada data, berikan nilai default
                if (!$paslon1) {
                    $paslon1 = new stdClass();
                    $paslon1->nama = 'Belum ada data';
                    $paslon1->nama_wakil = '';
                    $paslon1->foto = '/placeholder.jpg';
                    $paslon1->total_suara = 0;
                    $paslon1->persentase = 0;
                }
                if (!$paslon2) {
                    $paslon2 = new stdClass();
                    $paslon2->nama = 'Belum ada data';
                    $paslon2->nama_wakil = '';
                    $paslon2->foto = '/placeholder.jpg';
                    $paslon2->total_suara = 0;
                    $paslon2->persentase = 0;
                }

                // Tentukan arah scorebar berdasarkan perbandingan suara
                $paslon1Wins = $paslon1->total_suara >= $paslon2->total_suara;
            @endphp

            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <img src="{{ asset('storage/' . $paslon1->foto) }}" 
                        alt="{{ $paslon1->nama }}/{{ $paslon1->nama_wakil }}" 
                        class="rounded-full mr-4 w-20 h-20 object-cover">
                    <div class="flex flex-col">
                        <span class="font-semibold text-lg">{{ $paslon1->nama }}/{{ $paslon1->nama_wakil }}</span>
                        <span class="text-sm text-gray-600">{{ number_format($paslon1->persentase, 1) }}%</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex flex-col items-end">
                        <span class="font-semibold text-lg">{{ $paslon2->nama }}/{{ $paslon2->nama_wakil }}</span>
                        <span class="text-sm text-gray-600">{{ number_format($paslon2->persentase, 1) }}%</span>
                    </div>
                    <img src="{{ asset('storage/' . $paslon2->foto) }}" 
                        alt="{{ $paslon2->nama }}/{{ $paslon2->nama_wakil }}" 
                        class="rounded-full ml-4 w-20 h-20 object-cover">
                </div>
            </div>

            <div class="bg-gray-200 h-10 rounded-full overflow-hidden relative">
                @if($paslon1Wins)
                    {{-- Jika paslon 1 menang, warna biru mengisi dari kiri --}}
                    <div class="absolute inset-y-0 left-0 bg-[#3560A0] transition-all duration-500"
                        style="width: {{ $paslon1->persentase }}%">
                        <span class="text-white text-sm font-semibold ml-4 leading-10">
                            {{ number_format($paslon1->total_suara, 0, ',', '.') }} Suara
                        </span>
                    </div>
                    <div class="absolute inset-y-0 right-0 bg-yellow-400 transition-all duration-500"
                        style="width: {{ $paslon2->persentase }}%">
                        <span class="text-white text-sm font-semibold mr-4 leading-10 float-right">
                            {{ number_format($paslon2->total_suara, 0, ',', '.') }} Suara
                        </span>
                    </div>
                @else
                    {{-- Jika paslon 2 menang, warna kuning mengisi dari kanan --}}
                    <div class="absolute inset-y-0 right-0 bg-yellow-400 transition-all duration-500"
                        style="width: {{ $paslon2->persentase }}%">
                        <span class="text-white text-sm font-semibold mr-4 leading-10 float-right">
                            {{ number_format($paslon2->total_suara, 0, ',', '.') }} Suara
                        </span>
                    </div>
                    <div class="absolute inset-y-0 left-0 bg-[#3560A0] transition-all duration-500"
                        style="width: {{ $paslon1->persentase }}%">
                        <span class="text-white text-sm font-semibold ml-4 leading-10">
                            {{ number_format($paslon1->total_suara, 0, ',', '.') }} Suara
                        </span>
                    </div>
                @endif
            </div>
        </section>

        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 gap-8 mb-8">
                <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                    <h3 class="bg-[#3560A0] text-white text-center py-2">Peta Jumlah Suara Masuk Paslon</h3>
                    <div id="map" class="p-4 relative">
                        @include('admin.peta-kaltim.map')
                        <div id="tooltip" class="hidden">
                            <div class="kabupaten-title" id="kabupaten-name"></div>
                            <div class="info-grid">
                                <div class="label">{{ $calon[0]->nama }} / {{ $calon[0]->nama_wakil }} :</div>
                                <div class="value" id="suara-paslon1">0 suara</div>
                                @isset($calon[1])
                                    <div class="label">{{ $calon[1]->nama }} / {{ $calon[1]->nama_wakil }} :</div>
                                    <div class="value" id="suara-paslon2">0 suara</div>
                                @endisset
                            </div>
                        </div>
                        <div class="absolute bottom-2 right-2 bg-white p-2 rounded-lg shadow">
                            <div class="flex flex-col">
                                <div class="flex items-center mb-1">
                                    <div class="w-4 h-4 bg-[#3560A0] mr-2"></div>
                                    <span class="text-sm">{{ $calon[0]->nama }}/{{ $calon[0]->nama_wakil }}</span>
                                </div>
                                @isset($calon[1])
                                    <div class="flex items-center mb-1">
                                        <div class="w-4 h-4 bg-yellow-400 mr-2"></div>
                                        <span class="text-sm">{{ $calon[1]->nama }}/{{ $calon[1]->nama_wakil }}</span>
                                    </div>
                                @endisset
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-[#a6acb4] mr-2"></div>
                                    <span class="text-sm">Belum ada suara masuk</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                    <h3 class="bg-[#3560A0] text-white text-center py-2">Perbandingan DPT dan Abstain</h3>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-white p-4 rounded-lg text-center">
                                <h4 class="font-semibold text-gray-600">Total DPT</h4>
                                <p class="text-2xl font-bold text-[#3560A0]">{{ $dptAbstainData['total_dpt'] }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg text-center">
                                <h4 class="font-semibold text-gray-600">Total Abstain</h4>
                                <p class="text-2xl font-bold text-[#1E3A8A]">{{ $dptAbstainData['total_abstain'] }}</p>
                            </div>
                        </div>
                        <div class="mb-4 relative">
                            <canvas id="participationChart"></canvas>
                            <!-- Legend di pojok kiri bawah -->
                            <div class="absolute mt-2 left-2 bg-white p-2 rounded-lg shadow">
                                <div class="flex flex-col">
                                    <div class="flex items-center mb-1">
                                        <div class="w-4 h-4 bg-[#3560A0] mr-2"></div>
                                        <span class="text-sm">DPT</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-[#1E3A8A] mr-2"></div>
                                        <span class="text-sm">Abstain</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Bagian HTML untuk slider dan canvas -->
            <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                <h3 id="chartTitle" class="bg-[#3560A0] text-white text-center py-2 chart-title">Jumlah Angka Suara
                    Masuk Kabupaten/Kota</h3>

                <div class="chart-container">
                    <button class="nav-button nav-button-left" id="leftArrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button class="nav-button nav-button-right" id="rightArrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="canvas-wrapper">
                        <canvas id="voteCountChart" width="800" height="300"></canvas>
                    </div>
                </div>
            </section>
        </div>

        <div class="container mx-auto px-4">
            <div class="grid grid-cols-5 gap-4 mb-8">
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Samarinda
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Balikpapan
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Bontang
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Kutai Kartanegara
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Kutai Timur
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Kutai Barat
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Berau
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Paser
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Penajam Paser Utara
                </a>
                <a href="{{ route('suara') }}" class="bg-[#3560A0] text-white py-2 px-4 rounded text-center hover:bg-[#2a4d8a] transition-colors duration-200">
                    Mahakam Ulu
                </a>
            </div>

            <section class="bg-gray-100 rounded-lg shadow-md p-6 mb-8">
                <div id="slideContainer" class="relative w-full">
                    <div class="flex flex-col">
                        <!-- Container untuk slides -->
                        <div class="flex-grow">
                            @foreach($kabupatenData as $id => $data)
                            <div id="slide{{ $id }}" class="slide101 {{ $loop->first ? 'active' : '' }}">
                                <div class="mb-6 rounded-lg">
                                    <div class="flex items-start mb-6">
                                        <img src="{{ asset('storage/' . $data['logo']) }}" alt="Logo {{ $data['nama'] }}" class="mr-8 w-40 h-45">
                                        <div class="flex-grow pl-10">
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Suara Sah</h2>
                                                    <p class="text-lg font-bold text-gray-800">{{ number_format($data['suara_sah']) }} Suara</p>
                                                </div>
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">Suara Tidak Sah</h2>
                                                    <p class="text-lg font-bold text-gray-800">{{ number_format($data['suara_tidak_sah']) }} Suara</p>
                                                </div>
                                                <div class="flex justify-between items-center border-b pb-2">
                                                    <h2 class="text-sm font-semibold text-gray-600">DPT</h2>
                                                    <p class="text-lg font-bold text-gray-800">{{ number_format($data['dpt']) }} Orang</p>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <h2 class="text-sm font-semibold text-gray-600">Abstain</h2>
                                                    <p class="text-lg font-bold text-gray-800">{{ number_format($data['abstain']) }} Orang</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 text-white bg-blue-900 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col items-start w-1/3">
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 mr-2 bg-green-500"></div>
                                                <span>70,00% - 100,00% DPT » Hijau</span>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <div class="w-4 h-4 mr-2 bg-yellow-500"></div>
                                                <span>50,00% - 69,99% DPT » Kuning</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 mr-2 bg-red-500"></div>
                                                <span>0,00% - 49,99% DPT » Merah</span>
                                            </div>
                                        </div>
                                        <div class="text-center w-1/3">
                                            <h2 class="text-xl font-bold">Tingkat Partisipasi Masyarakat</h2>
                                        </div>
                                        <div class="text-right w-1/3">
                                            <div class="text-4xl font-bold color 
                                                {{ $data['warna_partisipasi'] === 'green' ? 'text-green-400' : 
                                                ($data['warna_partisipasi'] === 'yellow' ? 'text-yellow-400' : 'text-red-400') }}">
                                                {{ number_format($data['partisipasi'], 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        
                    </div>
                </div>
            </section>
        </div>
        
        <div class="relative overflow-hidden w-[1080px] mx-auto mt-20">
            <!-- Tombol Navigasi Kiri untuk Paslon -->
            <button id="prevSlideCandidate" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white p-3 shadow-lg z-20 transition-all duration-300 rounded-r-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3560a0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div id="candidateSlider" class="flex transition-transform duration-500 ease-in-out relative">
                @foreach($kabupatenData as $kabupatenId => $kabupatenInfo)
                    @php
                        $calon = $syncedCalonData[$kabupatenId];
                        $totalSlides = ceil(count($calon) / 3);
                    @endphp

                    @for($slideIndex = 0; $slideIndex < $totalSlides; $slideIndex++)
                        <div class="candidate-slide" data-kabupaten-id="{{ $kabupatenId }}" data-slide-index="{{ $slideIndex }}" style="display: none;">
                            <div class="flex justify-center gap-[45px] min-w-[1080px]">
                                @for($i = $slideIndex * 3; $i < min(($slideIndex + 1) * 3, count($calon)); $i++)
                                    <div class="w-[330px] flex flex-col">
                                        <div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] rounded-t-2xl overflow-hidden">
                                            @if ($calon[$i]['foto'])
                                                <img class="w-full h-full object-cover" 
                                                    src="{{ Storage::disk('foto_calon_lokal')->url($calon[$i]['foto']) }}" 
                                                    alt="{{ $calon[$i]['nama'] }} / {{ $calon[$i]['nama_wakil'] }}">
                                            @else
                                                <img class="w-full h-full object-cover" 
                                                    src="{{ asset('assets/default.png') }}" 
                                                    alt="Default Image">
                                            @endif
                                        </div>
                                        <div class="bg-[#3560a0] text-white text-center py-2 px-4 rounded-md inline-block -mt-12 ml-20 mr-20 z-10">
                                            {{ $calon[$i]['wilayah'] }}
                                        </div>
                                        <div class="bg-white rounded-b-2xl p-4 shadow">
                                            <h4 class="text-[#52526c] text-center font-bold mb-1">
                                                {{ $calon[$i]['nama'] }} / {{ $calon[$i]['nama_wakil'] }}
                                            </h4>
                                            <p class="text-[#6b6b6b] text-center text-sm mb-2">
                                                {{ $calon[$i]['posisi'] }} {{ $calon[$i]['nomor_urut'] }}
                                            </p>
                                            <div class="flex justify-center items-center text-[#008bf9]">
                                                <span class="font-medium">{{ number_format($calon[$i]['persentase'], 2) }}%</span>
                                                <div class="mx-2 h-4 w-px bg-[#008bf9] opacity-80"></div>
                                                <span class="font-medium">{{ number_format($calon[$i]['total_suara']) }} Suara</span>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endfor
                @endforeach
            </div>

            <br>

            <!-- Tombol Navigasi Kanan untuk Paslon -->
            <button id="nextSlideCandidate" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white p-3 shadow-lg z-20 transition-all duration-300 rounded-l-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3560a0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        
        <div class="flex justify-center items-center w-full mt-2 pb-4">
            <div class="flex items-center gap-4">
                <button id="prevSlide101" class="p-2 bg-blue-900 text-white rounded-full hover:bg-blue-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <button id="playPauseBtn" class="p-2 bg-blue-900 text-white rounded-full hover:bg-blue-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 pause-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 play-icon hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    </svg>
                </button>

                <button id="nextSlide101" class="p-2 bg-blue-900 text-white rounded-full hover:bg-blue-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>

        // slide paslon
        document.addEventListener('DOMContentLoaded', function() {
            const candidateSlides = document.querySelectorAll('.candidate-slide');
            const participationSlides = document.querySelectorAll('.slide101');
            const prevCandidateBtn = document.getElementById('prevSlideCandidate');
            const nextCandidateBtn = document.getElementById('nextSlideCandidate');

            let currentKabupatenId = '';
            let currentSlideIndex = 0;

            // Fungsi untuk mendapatkan ID kabupaten dari slide partisipasi yang aktif
            function getActiveKabupatenId() {
                const activeParticipationSlide = Array.from(participationSlides).find(slide => 
                    slide.style.display !== 'none' || slide.classList.contains('active')
                );
                return activeParticipationSlide ? activeParticipationSlide.id.replace('slide', '') : null;
            }

            // Fungsi untuk mendapatkan total slide dalam satu kabupaten
            function getTotalSlidesForKabupaten(kabupatenId) {
                return Array.from(candidateSlides).filter(slide => 
                    slide.getAttribute('data-kabupaten-id') === kabupatenId
                ).length;
            }

            // Fungsi untuk menampilkan slide paslon
            function showCandidateSlide(kabupatenId, slideIndex) {
                // Sembunyikan semua slide
                candidateSlides.forEach(slide => {
                    slide.style.display = 'none';
                    slide.classList.remove('fade-in');
                    slide.classList.add('fade-out');
                });

                // Cari dan tampilkan slide yang sesuai
                const targetSlide = Array.from(candidateSlides).find(slide => 
                    slide.getAttribute('data-kabupaten-id') === kabupatenId &&
                    parseInt(slide.getAttribute('data-slide-index')) === slideIndex
                );

                if (targetSlide) {
                    targetSlide.style.display = 'block';
                    targetSlide.classList.remove('fade-out');
                    targetSlide.classList.add('fade-in');
                    currentKabupatenId = kabupatenId;
                    currentSlideIndex = slideIndex;
                }

                // Update visibility tombol navigasi
                updateNavigationButtons(kabupatenId, slideIndex);
            }

            // Fungsi untuk mengupdate visibilitas tombol navigasi
            function updateNavigationButtons(kabupatenId, slideIndex) {
                const totalSlides = getTotalSlidesForKabupaten(kabupatenId);
                
                prevCandidateBtn.style.visibility = slideIndex === 0 ? 'hidden' : 'visible';
                nextCandidateBtn.style.visibility = slideIndex === totalSlides - 1 ? 'hidden' : 'visible';
            }

            // Event listener untuk tombol navigasi
            prevCandidateBtn.addEventListener('click', function() {
                const kabupatenId = getActiveKabupatenId();
                if (currentSlideIndex > 0) {
                    showCandidateSlide(kabupatenId, currentSlideIndex - 1);
                }
            });

            nextCandidateBtn.addEventListener('click', function() {
                const kabupatenId = getActiveKabupatenId();
                const totalSlides = getTotalSlidesForKabupaten(kabupatenId);
                if (currentSlideIndex < totalSlides - 1) {
                    showCandidateSlide(kabupatenId, currentSlideIndex + 1);
                }
            });

            // Observer untuk memantau perubahan pada slide partisipasi
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'attributes' && 
                        (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                        const kabupatenId = getActiveKabupatenId();
                        if (kabupatenId && kabupatenId !== currentKabupatenId) {
                            showCandidateSlide(kabupatenId, 0); // Reset ke slide pertama saat kabupaten berubah
                        }
                    }
                });
            });

            // Observe semua slide partisipasi
            participationSlides.forEach(slide => {
                observer.observe(slide, {
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            });

            // Tambahkan CSS untuk animasi
            if (!document.getElementById('candidateSliderStyles')) {
                const style = document.createElement('style');
                style.id = 'candidateSliderStyles';
                style.textContent = `
                    .fade-in {
                        animation: fadeIn 0.5s ease-in forwards;
                    }
                    
                    .fade-out {
                        animation: fadeOut 0.5s ease-out forwards;
                    }
                    
                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                    
                    @keyframes fadeOut {
                        from { opacity: 1; }
                        to { opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }

            // Inisialisasi tampilan awal
            const initialKabupatenId = getActiveKabupatenId();
            if (initialKabupatenId) {
                showCandidateSlide(initialKabupatenId, 0);
            }
        });
        
        // paslon singkron partisipasi
        document.addEventListener('DOMContentLoaded', function() {
        const participationSlides = document.querySelectorAll('.slide101');
        const candidateSlides = document.querySelectorAll('.candidate-slide');
        
        function showSlides(index) {
            // Hide all slides
            participationSlides.forEach(slide => slide.style.display = 'none');
            candidateSlides.forEach(slide => slide.style.display = 'none');
            
            // Show current slides
            if (participationSlides[index]) participationSlides[index].style.display = 'block';
            if (candidateSlides[index]) candidateSlides[index].style.display = 'block';
        }
        
        // Show first slides initially
        showSlides(0);
        
        // Update navigation buttons
        document.getElementById('prevSlide101').addEventListener('click', function() {
            const currentIndex = Array.from(participationSlides).findIndex(slide => 
                slide.style.display !== 'none'
            );
            const newIndex = (currentIndex - 1 + participationSlides.length) % participationSlides.length;
            showSlides(newIndex);
        });
        
        document.getElementById('nextSlide101').addEventListener('click', function() {
            const currentIndex = Array.from(participationSlides).findIndex(slide => 
                slide.style.display !== 'none'
            );
            const newIndex = (currentIndex + 1) % participationSlides.length;
            showSlides(newIndex);
        });
        
        // Autoplay functionality
        let autoplayInterval;
        const startAutoplay = () => {
            autoplayInterval = setInterval(() => {
                document.getElementById('nextSlide101').click();
            }, 5000);
        };
        
        const stopAutoplay = () => {
            clearInterval(autoplayInterval);
        };
        
        document.getElementById('playPauseBtn').addEventListener('click', function() {
            const playIcon = this.querySelector('.play-icon');
            const pauseIcon = this.querySelector('.pause-icon');
            
            if (playIcon.classList.contains('hidden')) {
                stopAutoplay();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            } else {
                startAutoplay();
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            }
        });
        
        // Start autoplay initially
        startAutoplay();
    });

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('voteCountChart').getContext('2d');
            const titleElement = document.getElementById('chartTitle');
            const MAX_VALUE = 500000;
            let currentView = 0;
            let isHovering = false; // Add hover state tracker
        
            const chartData = [
                {
                    title: "Jumlah Angka Suara Masuk Kabupaten/Kota",
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
                    }
                },
                {
                    title: "Jumlah Angka Suara Sah dan Tidak Sah Kabupaten/Kota",
                    data: {
                        labels: ['Berau', 'Kota Balikpapan', 'Kota Bontang', 'Kota Samarinda', 'Kutai Barat', 'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser', 'Penajam Paser Utara'],
                        datasets: [
                            {
                                label: 'Suara Sah',
                                data: [125000, 200567, 120000, 132000, 105000, 150000, 143000, 200000, 150000, 120000],
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
                            grid: { display: false },
                            ticks: {
                                font: { size: 12 },
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
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            },
                            grid: { color: '#E0E0E0' }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
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
                                font: { size: 12 }
                            }
                        }
                    },
                    layout: {
                        padding: { left: 10, right: 10, top: 10, bottom: 10 }
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
                        onComplete: function(animation) {
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
                                            percentage = Math.min(((data / MAX_VALUE) * 100), 100).toFixed(1);
                                        } else {
                                            const totalVotes = chartData[1].data.datasets[0].data[index] + 
                                                            chartData[1].data.datasets[1].data[index];
                                            if (totalVotes > 0) {
                                                percentage = Math.min(((data / MAX_VALUE) * 100), 100).toFixed(1);
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
                                        ctx.translate(barX, barY + barHeight/2);
                                        ctx.rotate(-Math.PI / 2);
                                        ctx.fillStyle = '#000000';
                                        
                                        const percentageText = percentage === 100 ? '100%' : 
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
        
        
        // diagram pie
        
        document.addEventListener('DOMContentLoaded', function() {
            const pieData = @json($dptAbstainData);
            const colors = ['#3560A0', '#1E3A8A'];

            const ctx = document.getElementById('participationChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: pieData.labels,
                    datasets: [{
                        data: pieData.percentages,
                        backgroundColor: colors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw}%`;
                                }
                            }
                        }
                    }
                }
            });
        });
        
        
        
        
        //buat animasi sama slide di logo logo
        document.addEventListener('DOMContentLoaded', function() {
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
    </script>
@endpush