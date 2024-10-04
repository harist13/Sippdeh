 <!-- Footer -->
<footer class="py-6 text-white bg-blue-600">
    <div class="container px-4 mx-auto">
        <div class="flex flex-col items-start space-y-6 md:flex-row md:items-center md:justify-between md:space-y-0">
            <div class="flex flex-col items-start md:flex-row md:items-center">
                <img src="{{ asset('assets/logo.png')}}" alt="Logo" class="w-12 h-12 mb-4 md:mb-0 md:w-15 md:h-16 md:mr-4">
                <div>
                    <h3 class="text-lg font-bold">BADAN KESATUAN BANGSA DAN POLITIK</h3>
                    <p class="text-sm">Jalan Jenderal Sudirman</p>
                    <p class="text-sm">Nomor 1, Samarinda</p>
                    <p class="text-sm">Kalimantan Timur 75117</p>
                </div>
            </div>
            <div class="w-full md:w-auto">
                <h3 class="mb-2 text-lg font-bold">KONTAK</h3>
                <p class="text-sm"><i class="mr-2 fas fa-phone"></i>+62541-733333</p>
                <p class="text-sm"><i class="mr-2 fas fa-envelope"></i>kesbangpolkaltim@gmail.com</p>
            </div>
        </div>
        <div class="mt-6 text-center">
            <p>Kesbangpolkaltim.info</p>
            <p class="mt-2 text-xs text-gray-200">Copyright © 2023 detikcom, All rights reserved</p>
            <div class="flex justify-center mt-2 space-x-4">
                <i class="text-xl fab fa-instagram"></i>
                <i class="text-xl fab fa-twitter"></i>
                <i class="text-xl fab fa-facebook"></i>
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

<script>
    // Toggle profile dropdown
    const profileDropdown = document.getElementById('profileDropdown');
    const profileMenu = document.getElementById('profileMenu');

    profileDropdown.addEventListener('click', () => {
        profileMenu.classList.toggle('hidden');
    });

    // Close the dropdown when clicking outside
    document.addEventListener('click', (event) => {
        if (!profileDropdown.contains(event.target) && !profileMenu.contains(event.target)) {
            profileMenu.classList.add('hidden');
        }
    });
</script>


</body>
</html>