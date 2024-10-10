 @include('admin.layout.header')
 <main class="container flex-grow px-4 mx-auto mt-6">
     <!-- Hasil Suara -->
     <div class="relative p-6 mb-6 overflow-hidden bg-white rounded-lg shadow">
         <!-- Background wavey pattern -->
         <div class="absolute inset-0 z-0">
             <img src="bg.png" width="100%" height="100%" preserveAspectRatio="none">
             <defs>
                 <pattern id="wavyPattern" patternUnits="userSpaceOnUse" width="100" height="100">
                     <path d="M0 50 Q 25 0, 50 50 T 100 50" fill="none" stroke="#f0f0f0" stroke-width="2" />
                 </pattern>
             </defs>
             <rect width="100%" height="100%" fill="url(#wavyPattern)" />
             </svg>
         </div>

         <!-- Map Kaltim -->
         <div class="z-10">
             <div class="flex items-center justify-between mb-4">
                 <div class="w-1/2 p-2 text-white bg-[#3560A0] rounded">
                     <span class="font-bold">372,987 Suara</span>
                     <span class="block text-sm">Isran Noor/Hady Mulyadi</span>
                 </div>
                 <div class="w-1/2 p-2 ml-2 text-right text-white bg-yellow-400 rounded">
                     <span class="font-bold">350,611 Suara</span>
                     <span class="block text-sm">Rudy Mas'ud/Seno Aji</span>
                 </div>
             </div>
             <div class="flex justify-center relative">
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
             </div>
         </div>
     </div>

     <!-- Grafik dan Pie Chart -->
    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="col-span-2 p-6 bg-white rounded-lg shadow">
            <canvas id="barChart"></canvas>
        </div>
        <div class="p-6 bg-white rounded-lg shadow">
            <canvas id="pieChart"></canvas>
        </div>
    </div>

     <!-- Kandidat -->
     <div class="grid grid-cols-3 gap-6 mb-6">
         <!-- Paslon 1 -->
         <div class="overflow-hidden bg-[#3560A0] rounded-lg shadow">
             <div class="relative">
                 <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/Wagub_Kaltim_Hadi_Mulyadi.jpg/800px-Wagub_Kaltim_Hadi_Mulyadi.jpg"
                     alt="Andi Harun" class="w-full h-48 object-cover">
                 <span class="absolute top-2 left-2 px-2 py-1 text-sm bg-[#3560A0] rounded text-white">Samarinda</span>
             </div>
             <div class="p-4 text-white">
                 <h3 class="font-bold text-center">Andi Harun / Saefuddin Zuhri</h3>
                 <p class="text-sm text-center">PASLON 1</p>
             </div>
             <div class="p-2 text-center text-white bg-[#3560A0]">
                 <p class="font-bold">21,69% - 288.131 Suara</p>
             </div>
         </div>

         <!-- Paslon 2 (Kotak Kosong) -->
         <div class="overflow-hidden bg-[#3560A0] rounded-lg shadow">
             <div class="relative h-48 bg-gray-200 flex items-center justify-center">
                 <p class="text-2xl font-bold text-gray-600">Kotak Kosong</p>
                 <span class="absolute top-2 left-2 px-2 py-1 text-sm bg-[#3560A0] rounded text-white">Samarinda</span>
             </div>
             <div class="p-4 text-white">
                 <h3 class="font-bold text-center">Kotak Kosong</h3>
                 <p class="text-sm text-center">PASLON 2</p>
             </div>
             <div class="p-2 text-center text-white bg-[#3560A0]">
                 <p class="font-bold">21,69% - 268,131 Suara</p>
             </div>
         </div>

         <!-- Paslon 3 -->
         <div class="overflow-hidden bg-[#3560A0] rounded-lg shadow">
             <div class="relative">
                 <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/Wagub_Kaltim_Hadi_Mulyadi.jpg/800px-Wagub_Kaltim_Hadi_Mulyadi.jpg"
                     alt="Muhammad Sabhani" class="w-full h-48 object-cover">
                 <span class="absolute top-2 left-2 px-2 py-1 text-sm bg-[#3560A0] rounded text-white">Balikpapan</span>
             </div>
             <div class="p-4 text-white">
                 <h3 class="font-bold text-center">Muhammad Sabhani / Syafril Wahid</h3>
                 <p class="text-sm text-center">PASLON 1</p>
             </div>
             <div class="p-2 text-center text-white bg-[#3560A0]">
                 <p class="font-bold">21,69% - 268,131 Suara</p>
             </div>
         </div>
     </div>

     <!-- Carousel Indicators -->
     <div class="flex justify-center mt-4 mb-6">
         <button class="w-8 h-2 mx-1 bg-[#3560A0] rounded-full"></button>
         <button class="w-2 h-2 mx-1 bg-gray-300 rounded-full"></button>
         <button class="w-2 h-2 mx-1 bg-gray-300 rounded-full"></button>
     </div>

     <!-- Statistik Suara -->
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

     <!-- Tombol Daerah -->
     <div class="grid grid-cols-5 gap-4 mb-6">
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Samarinda</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Balikpapan</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Bontang</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Kutai Kartanegara</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Kutai Timur</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Kutai Barat</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Berau</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Paser</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Penajam Paser Utara</button>
         <button class="px-4 py-2 text-white bg-[#3560A0] rounded">Mahakam Ulu</button>
     </div>
 </main>

@include('admin.layout.footer')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Bar Chart
    var barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Berau', 'Balikpapan', 'Bontang', 'Samarinda', 'Kutai Barat', 'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser', 'Penajam Paser Utara'],
            datasets: [{
                data: [345000, 92080, 155550, 100030, 200140, 51700, 285000, 79000, 319140, 277131],
                backgroundColor: '#4a90e2',
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 500000,
                    ticks: {
                        stepSize: 100000
                    }
                }
            }
        }
    });

    // Pie Chart
    var pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Tidak Menggunakan Hak Pilih', 'Pengguna Hak Pilih'],
            datasets: [{
                data: [32, 68],
                backgroundColor: ['#4a90e2', '#5ab0ee']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>