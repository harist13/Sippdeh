<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badan Kesatuan Bangsa Dan Politik Provinsi Kalimantan Timur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.hidden {
            transform: translateX(-100%);
        }
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        .submenu.show {
            max-height: 300px; /* Increased max-height to accommodate all items */
        }
    </style>
</head>
<body class="flex flex-col h-full bg-gray-100">

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-full overflow-y-auto bg-white shadow-lg sidebar hidden">
    <div class="flex items-center justify-between p-4 bg-blue-700">
        <h2 class="text-xl font-bold text-white">PILKADA PROVINSI</h2>
        <button id="closeSidebar" class="text-white focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="mt-4">
        <!-- Resume Section -->
        <div class="mb-2">
            <a href="#" class="flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-gray-100">
                <span class="flex items-center">
                    <i class="mr-3 fas fa-file-alt"></i> Resume
                </span>
                <i class="fas fa-chevron-right"></i>
            </a>
            <div class="submenu ml-8">
                <a href="#" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 far fa-clipboard"></i> Rekapitulasi
                </a>
                <a href="{{ route('rangkuman') }}" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 fas fa-chart-bar"></i> Rangkuman
                </a>
            </div>
        </div>
        <!-- User Section -->
        <div class="mb-2">
            <a href="#" class="flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-gray-100">
                <span class="flex items-center">
                    <i class="mr-3 fas fa-user"></i> User
                </span>
                <i class="fas fa-chevron-right"></i>
            </a>
            <div class="submenu ml-8">
                <a href="{{ route('user')}}" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 fas fa-user-circle"></i> User
                </a>
            </div>
        </div>
        <!-- Data Section -->
        <div class="mb-2">
            <a href="#" class="flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-gray-100">
                <span class="flex items-center">
                    <i class="mr-3 fas fa-database"></i> Data
                </span>
                <i class="fas fa-chevron-right"></i>
            </a>
            <div class="submenu ml-8">
                <a href="{{ route('provinsi')}}" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 fas fa-building"></i> Provinsi
                </a>
                <a href="{{ route('kabupaten')}}" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 fas fa-city"></i> Kabupaten/Kota
                </a>
                <a href="{{ route('kecamatan')}}" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 fas fa-map-marker-alt"></i> Kecamatan
                </a>
                <a href="{{ route('kelurahan')}}" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 fas fa-home"></i> Kelurahan
                </a>
                <a href="{{ route('tps')}}" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 fas fa-vote-yea"></i> TPS
                </a>
                <a href="{{ route('calon')}}" class="block py-2 text-sm text-gray-600 hover:text-blue-700">
                    <i class="mr-2 fas fa-users"></i> Calon Kabupaten/Kota
                </a>
            </div>
        </div>
    </nav>
</div>

<!-- Header & Navbar -->
<header class="bg-white shadow">
    <div class="container flex items-center justify-between px-4 py-3 mx-auto">
        <div class="flex items-center">
            <button id="openSidebar" class="mr-4 text-gray-600 focus:outline-none">
                <i class="text-xl fas fa-bars"></i>
            </button>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Coat_of_arms_of_East_Kalimantan.svg/800px-Coat_of_arms_of_East_Kalimantan.svg.png" alt="Logo" class="w-10 h-10 mr-2">
            <div class="flex flex-col">
                <span class="text-lg font-semibold">Badan Kesatuan Bangsa Dan Politik</span>
                <span class="text-sm text-gray-600">Provinsi Kalimantan Timur</span>
            </div>
        </div>
        <div class="flex items-center">
            <span class="mr-2"> {{ Auth::user()->roles->first()->name }} </span>
            <div class="relative">
                <button id="profileDropdown" class="flex items-center text-gray-600 focus:outline-none">
                    <i class="text-xl fas fa-user mr-2"></i>
                    <span class="mr-1">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50">
                    <div class="py-1">
                        <div class="px-4 py-2 text-sm text-gray-700">
                            <p><strong>Username:</strong> {{ Auth::user()->username }}</p>
                            <p><strong>Role:</strong> {{ Auth::user()->roles->first()->name }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        </div>
                        <div class="border-t border-gray-100"></div>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

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
