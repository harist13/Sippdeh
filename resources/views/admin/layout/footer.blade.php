 <!-- Footer -->
    <footer class="py-6 text-white bg-blue-600">
        <div class="container px-4 mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('assets/logo.png')}}" alt="Logo" class="w-15 h-16 mr-4">
                    <div>
                        <h3 class="font-bold">BADAN KESATUAN BANGSA DAN POLITIK</h3>
                        <p>Jalan Jenderal Sudirman</p>
                        <p>Nomor 1, Samarinda</p>
                        <p>Kalimantan Timur 75117</p>
                    </div>
                </div>
                <div>
                    <h3 class="mb-2 font-bold">KONTAK</h3>
                    <p><i class="mr-2 fas fa-phone"></i>+62541-733333</p>
                    <p><i class="mr-2 fas fa-envelope"></i>kesbangpolkaltim@gmail.com</p>
                </div>
            </div>
            <div class="mt-6 text-center">
                <p>Kesbangpolkaltim.info</p>
                <p class="mt-2 text-sm">Copyright © 2023 detikcom, All right reserved</p>
                <div class="mt-2">
                    <i class="mx-2 fab fa-instagram"></i>
                    <i class="mx-2 fab fa-twitter"></i>
                    <i class="mx-2 fab fa-facebook"></i>
                </div>
            </div>
        </div>
    </footer>

    <script>
    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Berau', 'Balikpapan', 'Bontang', 'Samarinda', 'Kutai Barat', 'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser', 'Penajam Paser Utara'],
            datasets: [{
                label: 'Jumlah Suara',
                data: [433163, 189399, 106684, 265548, 57000, 389909, 89000, 340145, 217231, 0],
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 500000,
                    ticks: {
                        stepSize: 100000
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Jumlah Suara per Daerah',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Tidak Menggunakan Hak Pilih', 'Pengguna Hak Pilih'],
            datasets: [{
                data: [32, 68],
                backgroundColor: ['#4299e1', '#2b6cb0'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Tingkat Partisipasi',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });
</script>
    

    <script>
    // Sidebar open/close
    const sidebar = document.getElementById('sidebar');
    const openSidebarBtn = document.getElementById('openSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');

    openSidebarBtn.addEventListener('click', () => {
        sidebar.classList.remove('hidden');
    });

    closeSidebarBtn.addEventListener('click', () => {
        sidebar.classList.add('hidden');
    });

    // Toggle submenus
    document.querySelectorAll('#sidebar > nav > div > a').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            const submenu = event.currentTarget.nextElementSibling;
            submenu.classList.toggle('show');
            event.currentTarget.querySelector('.fa-chevron-right').classList.toggle('rotate-90');
        });
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', (event) => {
        if (!sidebar.contains(event.target) && !openSidebarBtn.contains(event.target)) {
            sidebar.classList.add('hidden');
        }
    });
</script>

</body>
</html>