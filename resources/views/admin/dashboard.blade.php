@include('admin.layout.header')
    <main class="container mx-auto px-4 py-8">
        <section class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <img src="/api/placeholder/50/50" alt="Isran Noor/Hady Mulyadi" class="rounded-full mr-2">
                    <span class="font-semibold">Isran Noor/Hady Mulyadi</span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2">Rudy Mas'ud/Seno Aji</span>
                    <img src="/api/placeholder/50/50" alt="Rudy Mas'ud/Seno Aji" class="rounded-full">
                </div>
            </div>
            <div class="bg-gray-200 h-8 rounded-full overflow-hidden flex">
                <div class="bg-[#3560A0] h-full flex-grow flex items-center">
                    <span class="text-white text-xs font-semibold ml-2">372,987 Suara</span>
                </div>
                <div class="bg-yellow-400 h-full w-[32.6%] flex items-center justify-end">
                    <span class="text-white text-xs font-semibold mr-2">180,181 Suara</span>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <section class="bg-white rounded-lg shadow-md overflow-hidden">
                <h3 class="bg-[#3560A0] text-white text-center py-2">Peta Jumlah Suara Masuk Paslon</h3>
                <div class="p-4">
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

                    <div class="flex justify-end mt-4">
                        <div class="flex items-center mr-4">
                            <div class="w-4 h-4 bg-[#3560A0] mr-2"></div>
                            <span class="text-sm">Suara Terbanyak 1</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-yellow-400 mr-2"></div>
                            <span class="text-sm">Suara Terbanyak 2</span>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white rounded-lg shadow-md overflow-hidden">
                <h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Partisipasi Kabupaten/Kota</h3>
                <div class="p-4">
                    <canvas id="participationChart" width="400" height="400"></canvas>
                </div>
            </section>
        </div>

           <section class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Angka Suara Masuk Kabupaten/Kota</h3>
                <div class="p-4">
                    <canvas id="voteCountChart" width="800" height="300"></canvas>
                </div>
            </section>

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

        <section class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="p-6 mb-6 bg-white rounded-lg shadow">
                    <div class="flex items-start mb-6">
                        <img src="{{ asset('assets/logo.png')}}" alt="Logo Kota" class="mr-8 w-40 h-45">
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
                <div class="p-4 text-white bg-blue-900 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-start">
                            <div class="flex items-center mb-1">
                                <div class="w-4 h-4 mr-2 bg-red-500"></div> <!-- Warna diubah ke merah -->
                                <span>> 90,00% DPT » Merah</span> <!-- Teks diubah -->
                            </div>
                            <div class="flex items-center mb-1">
                                <div class="w-4 h-4 mr-2 bg-yellow-500"></div>
                                <span>> 80,00% DPT » Kuning</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 mr-2 bg-green-500"></div> <!-- Warna diubah ke hijau -->
                                <span>> 70,00% DPT » Hijau</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-xl font-bold">Tingkat Partisipasi Masyarakat</h2>
                            <div class="mt-2 text-4xl font-bold">81.40%</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

      <div class="relative overflow-hidden">
        <div id="candidateSlider" class="flex transition-transform duration-500 ease-in-out">
            <div class="w-1/3 flex-shrink-0 px-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="/api/placeholder/400/250" alt="Andi Harun / Saefuddin Zuhri" class="w-full h-48 object-cover">
                        <div class="absolute bottom-0 left-0 bg-[#3560A0] text-white px-2 py-1 text-sm">Samarinda</div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold mb-1">Andi Harun / Saefuddin Zuhri</h4>
                        <p class="text-gray-600 text-sm mb-2">PASLON 1</p>
                        <div class="flex justify-between text-[#3560A0] text-sm font-semibold">
                            <span>21,69%</span>
                            <span>288.131 Suara</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/3 flex-shrink-0 px-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="/api/placeholder/400/250" alt="Kotak Kosong" class="w-full h-48 object-cover">
                        <div class="absolute bottom-0 left-0 bg-[#3560A0] text-white px-2 py-1 text-sm">Samarinda</div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold mb-1">Kotak Kosong</h4>
                        <p class="text-gray-600 text-sm mb-2">PASLON 2</p>
                        <div class="flex justify-between text-[#3560A0] text-sm font-semibold">
                            <span>21,69%</span>
                            <span>288.131 Suara</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/3 flex-shrink-0 px-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="/api/placeholder/400/250" alt="Muhammad Sabani / Syukri Wahid" class="w-full h-48 object-cover">
                        <div class="absolute bottom-0 left-0 bg-[#3560A0] text-white px-2 py-1 text-sm">Balikpapan</div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold mb-1">Muhammad Sabani / Syukri Wahid</h4>
                        <p class="text-gray-600 text-sm mb-2">PASLON 1</p>
                        <div class="flex justify-between text-[#3560A0] text-sm font-semibold">
                            <span>21,69%</span>
                            <span>288.131 Suara</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/3 flex-shrink-0 px-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="/api/placeholder/400/250" alt="Muhammad Sabani / Syukri Wahid" class="w-full h-48 object-cover">
                        <div class="absolute bottom-0 left-0 bg-[#3560A0] text-white px-2 py-1 text-sm">Balikpapan</div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold mb-1">Muhammad Sabani / Syukri Wahid</h4>
                        <p class="text-gray-600 text-sm mb-2">PASLON 1</p>
                        <div class="flex justify-between text-[#3560A0] text-sm font-semibold">
                            <span>21,69%</span>
                            <span>288.131 Suara</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/3 flex-shrink-0 px-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="/api/placeholder/400/250" alt="Muhammad Sabani / Syukri Wahid" class="w-full h-48 object-cover">
                        <div class="absolute bottom-0 left-0 bg-[#3560A0] text-white px-2 py-1 text-sm">Balikpapan</div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold mb-1">Muhammad Sabani / Syukri Wahid</h4>
                        <p class="text-gray-600 text-sm mb-2">PASLON 1</p>
                        <div class="flex justify-between text-[#3560A0] text-sm font-semibold">
                            <span>21,69%</span>
                            <span>288.131 Suara</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/3 flex-shrink-0 px-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="/api/placeholder/400/250" alt="Muhammad Sabani / Syukri Wahid" class="w-full h-48 object-cover">
                        <div class="absolute bottom-0 left-0 bg-[#3560A0] text-white px-2 py-1 text-sm">Balikpapan</div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold mb-1">Muhammad Sabani / Syukri Wahid</h4>
                        <p class="text-gray-600 text-sm mb-2">PASLON 1</p>
                        <div class="flex justify-between text-[#3560A0] text-sm font-semibold">
                            <span>21,69%</span>
                            <span>288.131 Suara</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="flex justify-center mt-4">
    <button id="prevBtn" class="w-3 h-3 rounded-full bg-gray-300 mx-1"></button>
    <button id="nextBtn" class="w-3 h-3 rounded-full bg-[#3560A0] mx-1"></button>
</div>

    </main>

@include('admin.layout.footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
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
                    barPercentage: 0.6,
                },
                {
                    label: 'DPT',
                    data: [179000, 324534, 169432, 155372, 179193, 213285, 103193, 320193, 178456, 156183],
                    backgroundColor: '#99C9FF',
                    barPercentage: 0.6,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    grid: {
                        display: false
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
                    position: 'bottom',
                },
                title: {
                    display: false
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
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 15,
                        padding: 15,
                    },
                    display: false, // Hide default legend
                },
                title: {
                    display: false,
                },
            },
            layout: {
                padding: {
                    bottom: 100 // Add padding for custom legend
                }
            }
        }
    });

    // Custom legend
    const chart = Chart.getChart(ctx);
    const legendContainer = document.createElement('div');
    legendContainer.style.display = 'flex';
    legendContainer.style.flexWrap = 'wrap';
    legendContainer.style.justifyContent = 'center';
    legendContainer.style.marginTop = '20px';

    chart.data.labels.forEach((label, index) => {
        const legendItem = document.createElement('div');
        legendItem.style.display = 'flex';
        legendItem.style.alignItems = 'center';
        legendItem.style.marginRight = '10px';
        legendItem.style.marginBottom = '5px';
        legendItem.style.width = 'calc(50% - 10px)'; // Two columns

        const colorBox = document.createElement('div');
        colorBox.style.width = '15px';
        colorBox.style.height = '15px';
        colorBox.style.backgroundColor = chart.data.datasets[0].backgroundColor[index];
        colorBox.style.marginRight = '5px';

        const text = document.createElement('span');
        text.textContent = `${label}: ${chart.data.datasets[0].data[index]}%`;

        legendItem.appendChild(colorBox);
        legendItem.appendChild(text);
        legendContainer.appendChild(legendItem);
    });

    ctx.canvas.parentNode.appendChild(legendContainer);
});


document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('candidateSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let currentSlide = 0;

    function showSlide(index) {
        slider.style.transform = `translateX(-${index * 100}%)`;
        currentSlide = index;
        updateButtons();
    }

    function updateButtons() {
        prevBtn.classList.toggle('bg-[#3560A0]', currentSlide === 0);
        prevBtn.classList.toggle('bg-gray-300', currentSlide !== 0);
        nextBtn.classList.toggle('bg-[#3560A0]', currentSlide === 1);
        nextBtn.classList.toggle('bg-gray-300', currentSlide !== 1);
    }

    prevBtn.addEventListener('click', () => showSlide(0));
    nextBtn.addEventListener('click', () => showSlide(1));

    function autoSlide() {
        showSlide((currentSlide + 1) % 2);
    }

    setInterval(autoSlide, 5000);
});


</script>