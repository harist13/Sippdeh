@include('admin.layout.header')
<style>
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
</style>
    <main class="bg-white shadow-lg rounded-lg p-8 max-w-7xl mx-auto my-8">
        <section class="rounded-lg p-4 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <img src="https://d22gwcrfo2de51.cloudfront.net/wp-content/uploads/2021/09/isran1-122e74ac-41bc-4213-91e3-677f58c1eab4_jpg-1024x683-1.jpg" alt="Isran Noor/Hady Mulyadi" class="rounded-full mr-4 w-20 h-20">
                    <span class="font-semibold text-lg">Isran Noor/Hady Mulyadi</span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold text-lg mr-4">Rudy Mas'ud/Seno Aji</span>
                    <img src="https://d22gwcrfo2de51.cloudfront.net/wp-content/uploads/2021/09/isran1-122e74ac-41bc-4213-91e3-677f58c1eab4_jpg-1024x683-1.jpg" alt="Rudy Mas'ud/Seno Aji" class="rounded-full w-20 h-20">
                </div>
            </div>
            <div class="bg-gray-200 h-10 rounded-full overflow-hidden flex">
                <div class="bg-[#3560A0] h-full flex-grow flex items-center">
                    <span class="text-white text-sm font-semibold ml-4">372,987 Suara</span>
                </div>
                <div class="bg-yellow-400 h-full w-[32.6%] flex items-center justify-end">
                    <span class="text-white text-sm font-semibold mr-4">180,181 Suara</span>
                </div>
            </div>
        </section>
         <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 gap-8 mb-8">
                <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                    <h3 class="bg-[#3560A0] text-white text-center py-2">Peta Jumlah Suara Masuk Paslon</h3>
                    <div class="p-4 relative">
                        @include('admin.peta-kaltim.map')
                        <div id="tooltip" class="hidden bg-slate-100 p-4 rounded-md absolute shadow">
                            <p class="mb-2 font-bold">Kutai Kartanegara</p>
                            <div class="grid grid-cols-2 gap-1">
                                <p>Total Suara Sah</p>
                                <p>: 70.000 orang</p>
                                <p>Total Suara Tidak Sah</p>
                                <p>: 18.000 orang</p>
                            </div>
                        </div>
                        <div class="absolute bottom-2 right-2 bg-white p-2 rounded-lg shadow">
                            <div class="flex flex-col">
                                <div class="flex items-center mb-1">
                                    <div class="w-4 h-4 bg-[#3560A0] mr-2"></div>
                                    <span class="text-sm">Suara Terbanyak 1</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-yellow-400 mr-2"></div>
                                    <span class="text-sm">Suara Terbanyak 2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
               <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                    <h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Angka Suara Masuk Kabupaten/Kota</h3>
                    <div class="p-4">
                        <div class="mb-4">
                            <canvas id="participationChart"></canvas>
                        </div>
                        <div id="legendContainer" class="bg-white p-4 rounded-lg grid grid-cols-2 gap-4"></div>
                    </div>
                </section>
            </div>

                <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                    <h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Angka Suara Masuk Kabupaten/Kota</h3>
                    <div class="p-4">
                        <canvas id="voteCountChart" width="800" height="300"></canvas>
                    </div>
                </section>
            </div>
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-5 gap-4 mb-8">
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Samarinda</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Balikpapan</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Bontang</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Kutai Kartanegara</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Kutai Timur</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Kutai Barat</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Berau</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Paser</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Penajam Paser Utara</button>
                    <button class="bg-[#3560A0] text-white py-2 px-4 rounded">Mahakam Ulu</button>
                </div>

                <section class="bg-gray-100 rounded-lg shadow-md p-6 mb-8">
                     <div id="slideContainer" class="relative w-full h-screen">
                        <div id="slide1" class="slide active">
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
                                                <h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah</h2>
                                                <p class="text-lg font-bold text-gray-800">37.251 Suara</p>
                                            </div>
                                            <div class="flex justify-between items-center border-b pb-2">
                                                <h2 class="text-sm font-semibold text-gray-600">Jumlah Pengguna Hak Pilih</h2>
                                                <p class="text-lg font-bold text-gray-800">2.261.813 Orang</p>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <h2 class="text-sm font-semibold text-gray-600">Jumlah Tidak Menggunakan Hak Pilih</h2>
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
                        <div id="slide2" class="slide">
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
                                                <h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah</h2>
                                                <p class="text-lg font-bold text-gray-800">37.251 Suara</p>
                                            </div>
                                            <div class="flex justify-between items-center border-b pb-2">
                                                <h2 class="text-sm font-semibold text-gray-600">Jumlah Pengguna Hak Pilih</h2>
                                                <p class="text-lg font-bold text-gray-800">2.261.813 Orang</p>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <h2 class="text-sm font-semibold text-gray-600">Jumlah Tidak Menggunakan Hak Pilih</h2>
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
                        <div id="slide3" class="slide">
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
                                                <h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah</h2>
                                                <p class="text-lg font-bold text-gray-800">37.251 Suara</p>
                                            </div>
                                            <div class="flex justify-between items-center border-b pb-2">
                                                <h2 class="text-sm font-semibold text-gray-600">Jumlah Pengguna Hak Pilih</h2>
                                                <p class="text-lg font-bold text-gray-800">2.261.813 Orang</p>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <h2 class="text-sm font-semibold text-gray-600">Jumlah Tidak Menggunakan Hak Pilih</h2>
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
                    </div>
                </section>
            </div>

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
        </main>

@include('admin.layout.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('voteCountChart').getContext('2d');
    new Chart(ctx, {
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
                            size: 12
                        },
                        maxRotation: 0,
                        minRotation: 0,
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
                        padding: 15,
                        font: {
                            size: 12
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
});




document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('participationChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                'Kota Samarinda', 'Kota Balikpapan', 'Kota Bontang', 'Kutai Kartanegara',
                'Kutai Timur', 'Kutai Barat', 'Berau', 'Paser',
                'Penajam Paser Utara', 'Mahakam Ulu'
            ],
            datasets: [{
                data: [8.4, 10.1, 6.7, 10.9, 12.9, 13.7, 9.4, 11.2, 7.7, 9],
                backgroundColor: [
                    '#E6F3FF', '#C5E3FF', '#A4D3FF', '#83C3FF',
                    '#62B3FF', '#41A3FF', '#2093FF', '#0083FF',
                    '#0065C2', '#004785'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: false,
                },
            },
        }
    });

    // Custom legend
    const chart = Chart.getChart(ctx);
    const legendContainer = document.getElementById('legendContainer');

    chart.data.labels.forEach((label, index) => {
        const legendItem = document.createElement('div');
        legendItem.className = 'flex items-center';

        const colorBox = document.createElement('div');
        colorBox.className = 'w-3 h-3 mr-2 flex-shrink-0';
        colorBox.style.backgroundColor = chart.data.datasets[0].backgroundColor[index];

        const text = document.createElement('span');
        text.className = 'text-sm';
        text.textContent = `${label}: ${chart.data.datasets[0].data[index]}%`;

        legendItem.appendChild(colorBox);
        legendItem.appendChild(text);
        legendContainer.appendChild(legendItem);
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


 $(document).ready(function() {
    function transitionSlides() {
        const $currentSlide = $('.slide.active');
        const $nextSlide = $currentSlide.next('.slide').length ? $currentSlide.next('.slide') : $('.slide:first');

        $currentSlide.removeClass('active');
        $nextSlide.addClass('active');

        setTimeout(transitionSlides, 6000); 
    }

    setTimeout(transitionSlides, 3000); 
});

</script>