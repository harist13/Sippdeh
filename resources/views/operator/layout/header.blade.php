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
        .sidebar-item svg {
            color: #1C274C;
        }
        .sidebar-item.active svg {
            color: #3B82F6;
        }
        .sidebar-item.active {
            background-color: #EFF6FF;
            color: #3B82F6;
        }
        .gradient-hr {
            height: 2px;
            border: none;
            background: linear-gradient(to right, #bfdbfe, #bfdbfe, #bfdbfe);
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

         .participation-button {
            display: inline-block;
            width: 100px;
            padding: 3px 0;
            font-size: 14px;
            text-align: center;
            border-radius: 6px;
            font-weight: 500;
            color: white;
        }
        .participation-red { background-color: #ff7675; }
        .participation-yellow { background-color: #feca57; }
        .participation-green { background-color: #69d788; }

        @media screen and (max-width: 768px) {
            footer .container {
                padding: 0 1rem;
            }

            footer .flex-col {
                align-items: center;
            }

            footer .flex-col > div {
                width: 100%;
                text-align: center;
                margin-bottom: 2rem;
            }

            footer .flex.items-start {
                flex-direction: column;
                align-items: center;
            }

            footer .flex.items-start img {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            footer .text-right {
                text-align: center;
            }

            footer .flex.justify-end {
                justify-content: center;
            }

            footer .border-t .flex-col {
                text-align: center;
            }

            footer .border-t .flex-col > * {
                margin-bottom: 1rem;
            }

            footer .border-t .flex.space-x-4 {
                justify-content: center;
            }
        }
    </style>
    @livewireStyles
</head>
<body class="relative flex flex-col h-full bg-gray-100">

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 z-1050 w-64 h-full overflow-y-auto bg-white shadow-lg sidebar hidden">
    <div class="flex items-center justify-between p-4 bg-[#3560A0] my-2 ml-2 mr-2 rounded-md">
        <h2 class="text-lg font-bold text-white">SIPPDEH PROV.KALTIM</h2>
        <button id="closeSidebar" class="text-white focus:outline-none">
            
        </button>
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="mt-4">
        <a href="{{ route('operator.dashboard') }}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('operator.dashboard') ? 'active' : '' }}">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V10C2 6.22876 2 4.34315 3.17157 3.17157C4.34315 2 6.23869 2 10.0298 2C10.6358 2 11.1214 2 11.53 2.01666C11.5166 2.09659 11.5095 2.17813 11.5092 2.26057L11.5 5.09497C11.4999 6.19207 11.4998 7.16164 11.6049 7.94316C11.7188 8.79028 11.9803 9.63726 12.6716 10.3285C13.3628 11.0198 14.2098 11.2813 15.0569 11.3952C15.8385 11.5003 16.808 11.5002 17.9051 11.5001L18 11.5001H21.9574C22 12.0344 22 12.6901 22 13.5629V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22Z" fill="currentColor"/>
            <path d="M19.3517 7.61665L15.3929 4.05375C14.2651 3.03868 13.7012 2.53114 13.0092 2.26562L13 5.00011C13 7.35713 13 8.53564 13.7322 9.26787C14.4645 10.0001 15.643 10.0001 18 10.0001H21.5801C21.2175 9.29588 20.5684 8.71164 19.3517 7.61665Z" fill="currentColor"/>
            </svg>
            <span class="ml-2">Dashboard</span>
        </a>
        <hr class="my-2 ml-2 mr-2 rounded-md gradient-hr">
        
        

        <div class="px-2 py-2 text-gray-700 font-bold text-base">Data</div>
        <a href="{{ route('operator.pilgub') }}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('operator.pilgub') ? 'active' : '' }}">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 13.75C20 13.3358 19.6642 13 19.25 13H16.25C15.8358 13 15.5 13.3358 15.5 13.75V20.5H14V4.25C14 3.52169 13.9984 3.05091 13.9518 2.70403C13.908 2.37872 13.8374 2.27676 13.7803 2.21967C13.7232 2.16258 13.6213 2.09197 13.296 2.04823C12.9491 2.00159 12.4783 2 11.75 2C11.0217 2 10.5509 2.00159 10.204 2.04823C9.87872 2.09197 9.77676 2.16258 9.71967 2.21967C9.66258 2.27676 9.59196 2.37872 9.54823 2.70403C9.50159 3.05091 9.5 3.52169 9.5 4.25V20.5H8V8.75C8 8.33579 7.66421 8 7.25 8H4.25C3.83579 8 3.5 8.33579 3.5 8.75V20.5H2H1.75C1.33579 20.5 1 20.8358 1 21.25C1 21.6642 1.33579 22 1.75 22H21.75C22.1642 22 22.5 21.6642 22.5 21.25C22.5 20.8358 22.1642 20.5 21.75 20.5H21.5H20V13.75Z" fill="currentColor"/>
            </svg>
            <span class="ml-2">Input Suara Pilgub</span>
        </a>
        <a href="{{ route('operator.pilkada') }}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('operator.pilkada') ? 'active' : '' }}">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447ZM17 8.25C17.4142 8.25 17.75 8.58579 17.75 9V18C17.75 18.4142 17.4142 18.75 17 18.75C16.5858 18.75 16.25 18.4142 16.25 18V9C16.25 8.58579 16.5858 8.25 17 8.25ZM12.75 12C12.75 11.5858 12.4142 11.25 12 11.25C11.5858 11.25 11.25 11.5858 11.25 12V18C11.25 18.4142 11.5858 18.75 12 18.75C12.4142 18.75 12.75 18.4142 12.75 18V12ZM7 14.25C7.41421 14.25 7.75 14.5858 7.75 15V18C7.75 18.4142 7.41421 18.75 7 18.75C6.58579 18.75 6.25 18.4142 6.25 18V15C6.25 14.5858 6.58579 14.25 7 14.25Z" fill="currentColor"/>
            </svg>
            <span class="ml-2">Input Suara Pilkada</span>
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
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
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
                    <input class="w-full px-3 py-2 border bg-gray-300 border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500" id="username" type="text" value="{{ Auth::user()->username }}" readonly>
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
                    <input class="w-full px-3 py-2 border bg-gray-300 border-gray-300 rounded-md text-gray-700 focus:outline-none focus:border-blue-500" id="wilayah" type="text" value="{{ Auth::user()->wilayah }}" readonly>
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