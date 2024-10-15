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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            transition: transform 0.3s ease-in-out;
            z-index: 1050; /* pastikan lebih tinggi dari navbar */
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
            max-height: 300px;
        }
        body {
            padding-top: 80px; /* Sesuaikan dengan tinggi navbar Anda */
        }
        .navbar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000; /* pastikan lebih rendah dari sidebar */
        }
    </style>
</head>
<body class="relative flex flex-col h-full bg-gray-100">

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 z-1050 w-64 h-full overflow-y-auto bg-white shadow-lg sidebar hidden">
    <div class="flex items-center justify-between p-4 bg-[#3560A0]">
        <h2 class="text-xl font-bold text-white">SIPPDEH KALTIM</h2>
        <button id="closeSidebar" class="text-white focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="mt-4">
        <div class="px-4 py-2 text-gray-700 font-bold text-base">Resume</div>
        <a href="{{ route('rangkuman') }}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-100">
            <img src="#" alt="" class="w-5 h-5 mr-3">
            <span>Rangkuman</span>
        </a>
        <hr class="my-2 border-gray-200">
        

        <div class="px-4 py-2 text-gray-700 font-bold text-base">Data</div>
        <a href="#" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-100">
            <img src="{{ asset('assets/icon/Building3.png') }}" alt="" class="w-5 h-5 mr-3">
            <span>Provinsi</span>
        </a>
        <a href="#" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-100">
            <img src="{{ asset('assets/icon/Building2.png') }}" alt="" class="w-5 h-5 mr-3">
            <span>Kabupaten/Kota</span>
        </a>
       
    </nav>
</div>

<!-- Header & Navbar -->
<header class="bg-white shadow navbar-fixed">
    <div class="container flex items-center justify-between px-4 py-3 mx-auto">
        <div class="flex items-center">
            <button id="openSidebar" class="mr-4 text-gray-600 focus:outline-none">
                <i class="text-xl fas fa-bars"></i>
            </button>
            <img src="{{ asset('assets/logo.png')}}" alt="Logo" class="w-8 h-10 mr-2">
            <div class="flex flex-col">
                <span class="text-lg font-semibold text-blue-600">Badan Kesatuan Bangsa Dan Politik</span>
                <span class="text-sm text-gray-600">Provinsi Kalimantan Timur</span>
            </div>
        </div>
        <div class="flex items-center">
            <div class="relative">
                <button id="profileDropdown" class="flex items-center text-gray-600 focus:outline-none">
                    <img src="{{ asset('assets/user.png')}}" alt="Logo" class="w-10 h-10 mr-2">
                    
                    <div class="flex-col text-left hidden sm:flex">
                        <span class="font-semibold">{{ Auth::user()->wilayah }}</span>
                        <span class="text-sm text-gray-500">{{ Auth::user()->roles->first()->name }}</span>
                    </div>
                </button>
                <!-- Dropdown Menu -->
                <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50">
                    <div class="py-1">
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
    <div id="profileModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-6 border w-96 shadow-lg rounded-lg bg-white">
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Profile Information</h3>
            <form id="profileForm" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="username">
                        Username
                    </label>
                    <input class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500" id="username" type="text" value="{{ Auth::user()->username }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="email">
                        Email
                    </label>
                    <input class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500" id="email" name="email" type="email" value="{{ Auth::user()->email }}">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="wilayah">
                        Wilayah
                    </label>
                    <input class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500" id="wilayah" type="text" value="{{ Auth::user()->wilayah }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="password">
                        New Password
                    </label>
                    <input class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500" id="password" name="password" type="password" placeholder="Leave blank to keep current password">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1 text-left" for="password_confirmation">
                        Confirm New Password
                    </label>
                    <input class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password">
                </div>
            </form>
            <div class="mt-6 flex justify-between">
                <button id="saveProfile" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Save Changes
                </button>
                <button id="closeModal" class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
</header>