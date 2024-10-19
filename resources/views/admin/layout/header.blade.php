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
</head>
<body class="relative flex flex-col h-full bg-gray-100">


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
        <a href="{{ route('Dashboard') }}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('Dashboard') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V10C2 6.22876 2 4.34315 3.17157 3.17157C4.34315 2 6.23869 2 10.0298 2C10.6358 2 11.1214 2 11.53 2.01666C11.5166 2.09659 11.5095 2.17813 11.5092 2.26057L11.5 5.09497C11.4999 6.19207 11.4998 7.16164 11.6049 7.94316C11.7188 8.79028 11.9803 9.63726 12.6716 10.3285C13.3628 11.0198 14.2098 11.2813 15.0569 11.3952C15.8385 11.5003 16.808 11.5002 17.9051 11.5001L18 11.5001H21.9574C22 12.0344 22 12.6901 22 13.5629V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22Z" fill="currentColor"/>
            <path d="M19.3517 7.61665L15.3929 4.05375C14.2651 3.03868 13.7012 2.53114 13.0092 2.26562L13 5.00011C13 7.35713 13 8.53564 13.7322 9.26787C14.4645 10.0001 15.643 10.0001 18 10.0001H21.5801C21.2175 9.29588 20.5684 8.71164 19.3517 7.61665Z" fill="currentColor"/>
            </svg>
            <span class="ml-3">Dashboard</span>
        </a>
        <a href="{{ route('rangkuman') }}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('rangkuman') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.5189 16.5013C16.6939 16.3648 16.8526 16.2061 17.1701 15.8886L21.1275 11.9312C21.2231 11.8356 21.1793 11.6708 21.0515 11.6264C20.5844 11.4644 19.9767 11.1601 19.4083 10.5917C18.8399 10.0233 18.5356 9.41561 18.3736 8.94849C18.3292 8.82066 18.1644 8.77687 18.0688 8.87254L14.1114 12.8299C13.7939 13.1474 13.6352 13.3061 13.4987 13.4811C13.3377 13.6876 13.1996 13.9109 13.087 14.1473C12.9915 14.3476 12.9205 14.5606 12.7786 14.9865L12.5951 15.5368L12.3034 16.4118L12.0299 17.2323C11.9601 17.4419 12.0146 17.6729 12.1708 17.8292C12.3271 17.9854 12.5581 18.0399 12.7677 17.9701L13.5882 17.6966L14.4632 17.4049L15.0135 17.2214L15.0136 17.2214C15.4394 17.0795 15.6524 17.0085 15.8527 16.913C16.0891 16.8004 16.3124 16.6623 16.5189 16.5013Z" fill="currentColor"/>
            <path d="M22.3665 10.6922C23.2112 9.84754 23.2112 8.47812 22.3665 7.63348C21.5219 6.78884 20.1525 6.78884 19.3078 7.63348L19.1806 7.76071C19.0578 7.88348 19.0022 8.05496 19.0329 8.22586C19.0522 8.33336 19.0879 8.49053 19.153 8.67807C19.2831 9.05314 19.5288 9.54549 19.9917 10.0083C20.4545 10.4712 20.9469 10.7169 21.3219 10.847C21.5095 10.9121 21.6666 10.9478 21.7741 10.9671C21.945 10.9978 22.1165 10.9422 22.2393 10.8194L22.3665 10.6922Z" fill="currentColor"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.17157 3.17157C3 4.34315 3 6.22876 3 10V14C3 17.7712 3 19.6569 4.17157 20.8284C5.34315 22 7.22876 22 11 22H13C16.7712 22 18.6569 22 19.8284 20.8284C20.9812 19.6756 20.9997 17.8316 21 14.1801L18.1817 16.9984C17.9119 17.2683 17.691 17.4894 17.4415 17.6841C17.1491 17.9121 16.8328 18.1076 16.4981 18.2671C16.2124 18.4032 15.9159 18.502 15.5538 18.6225L13.2421 19.3931C12.4935 19.6426 11.6682 19.4478 11.1102 18.8898C10.5523 18.3318 10.3574 17.5065 10.607 16.7579L10.8805 15.9375L11.3556 14.5121L11.3775 14.4463C11.4981 14.0842 11.5968 13.7876 11.7329 13.5019C11.8924 13.1672 12.0879 12.8509 12.316 12.5586C12.5106 12.309 12.7317 12.0881 13.0017 11.8183L17.0081 7.81188L18.12 6.70004L18.2472 6.57282C18.9626 5.85741 19.9003 5.49981 20.838 5.5C20.6867 4.46945 20.3941 3.73727 19.8284 3.17157C18.6569 2 16.7712 2 13 2H11C7.22876 2 5.34315 2 4.17157 3.17157ZM7.25 9C7.25 8.58579 7.58579 8.25 8 8.25H14.5C14.9142 8.25 15.25 8.58579 15.25 9C15.25 9.41421 14.9142 9.75 14.5 9.75H8C7.58579 9.75 7.25 9.41421 7.25 9ZM7.25 13C7.25 12.5858 7.58579 12.25 8 12.25H10.5C10.9142 12.25 11.25 12.5858 11.25 13C11.25 13.4142 10.9142 13.75 10.5 13.75H8C7.58579 13.75 7.25 13.4142 7.25 13ZM7.25 17C7.25 16.5858 7.58579 16.25 8 16.25H9.5C9.91421 16.25 10.25 16.5858 10.25 17C10.25 17.4142 9.91421 17.75 9.5 17.75H8C7.58579 17.75 7.25 17.4142 7.25 17Z" fill="currentColor"/>
            </svg>
            <span class="ml-3">Rangkuman</span>
        </a>
        <hr class="my-2 border-gray-200">
        <div class="px-4 py-2 text-gray-700 font-bold text-base">User</div>
        <a href="{{ route('user')}}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('user') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="6" r="4" fill="currentColor"/>
            <ellipse cx="12" cy="17" rx="7" ry="4" fill="currentColor"/>
            </svg>
            <span class="ml-3">User</span>
        </a>
        <hr class="my-2 border-gray-200">
        <div class="px-4 py-2 text-gray-700 font-bold text-base">Data</div>
        <a href="{{ route('provinsi')}}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('provinsi') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.9129 5.88886C21.25 6.39331 21.25 7.09554 21.25 8.5V21.25H21.75C22.1642 21.25 22.5 21.5858 22.5 22C22.5 22.4142 22.1642 22.75 21.75 22.75H1.75C1.33579 22.75 1 22.4142 1 22C1 21.5858 1.33579 21.25 1.75 21.25H2.25V8.5C2.25 7.09554 2.25 6.39331 2.58706 5.88886C2.73298 5.67048 2.92048 5.48298 3.13886 5.33706C3.58008 5.04224 4.17262 5.00529 5.25403 5.00066C5.24992 5.29206 5.24996 5.59655 5.24999 5.9105L5.25 6V7.25H4.25C3.83579 7.25 3.5 7.58579 3.5 8C3.5 8.41421 3.83579 8.75 4.25 8.75H5.25V10.25H4.25C3.83579 10.25 3.5 10.5858 3.5 11C3.5 11.4142 3.83579 11.75 4.25 11.75H5.25V13.25H4.25C3.83579 13.25 3.5 13.5858 3.5 14C3.5 14.4142 3.83579 14.75 4.25 14.75H5.25V21.25H6.75V6C6.75 4.11438 6.75 3.17157 7.33579 2.58579C7.92157 2 8.86438 2 10.75 2H12.75C14.6356 2 15.5784 2 16.1642 2.58579C16.75 3.17157 16.75 4.11438 16.75 6V21.25H18.25V14.75H19.25C19.6642 14.75 20 14.4142 20 14C20 13.5858 19.6642 13.25 19.25 13.25H18.25V11.75H19.25C19.6642 11.75 20 11.4142 20 11C20 10.5858 19.6642 10.25 19.25 10.25H18.25V8.75H19.25C19.6642 8.75 20 8.41421 20 8C20 7.58579 19.6642 7.25 19.25 7.25H18.25V6L18.25 5.91051C18.25 5.59655 18.2501 5.29206 18.246 5.00066C19.3274 5.00529 19.9199 5.04224 20.3611 5.33706C20.5795 5.48298 20.767 5.67048 20.9129 5.88886ZM12.5 19C12.5 18.5858 12.1642 18.25 11.75 18.25C11.3358 18.25 11 18.5858 11 19V21.25H12.5V19ZM9 15C9 14.5858 9.33579 14.25 9.75 14.25H13.75C14.1642 14.25 14.5 14.5858 14.5 15C14.5 15.4142 14.1642 15.75 13.75 15.75H9.75C9.33579 15.75 9 15.4142 9 15ZM9.75 11.25C9.33579 11.25 9 11.5858 9 12C9 12.4142 9.33579 12.75 9.75 12.75H13.75C14.1642 12.75 14.5 12.4142 14.5 12C14.5 11.5858 14.1642 11.25 13.75 11.25H9.75ZM9.25 7C9.25 5.48122 10.4812 4.25 12 4.25C13.5188 4.25 14.75 5.48122 14.75 7C14.75 8.51878 13.5188 9.75 12 9.75C10.4812 9.75 9.25 8.51878 9.25 7ZM10.75 7C10.75 6.30964 11.3096 5.75 12 5.75C12.6904 5.75 13.25 6.30964 13.25 7C13.25 7.69036 12.6904 8.25 12 8.25C11.3096 8.25 10.75 7.69036 10.75 7Z" fill="currentColor"/>
            </svg>
            <span class="ml-3">Provinsi</span>
        </a>
        <a href="{{ route('kabupaten')}}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('kabupaten') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.25 8.5C21.25 7.09554 21.25 6.39331 20.9129 5.88886C20.767 5.67048 20.5795 5.48298 20.3611 5.33706C19.9199 5.04224 19.3274 5.00529 18.246 5.00066C18.2501 5.29206 18.25 5.59655 18.25 5.91051L18.25 6V7.25H19.25C19.6642 7.25 20 7.58579 20 8C20 8.41421 19.6642 8.75 19.25 8.75H18.25V10.25H19.25C19.6642 10.25 20 10.5858 20 11C20 11.4142 19.6642 11.75 19.25 11.75H18.25V13.25H19.25C19.6642 13.25 20 13.5858 20 14C20 14.4142 19.6642 14.75 19.25 14.75H18.25V21.25H16.75V6C16.75 4.11438 16.75 3.17157 16.1642 2.58579C15.5784 2 14.6356 2 12.75 2H10.75C8.86438 2 7.92157 2 7.33579 2.58579C6.75 3.17157 6.75 4.11438 6.75 6V21.25H5.25V14.75H4.25C3.83579 14.75 3.5 14.4142 3.5 14C3.5 13.5858 3.83579 13.25 4.25 13.25H5.25V11.75H4.25C3.83579 11.75 3.5 11.4142 3.5 11C3.5 10.5858 3.83579 10.25 4.25 10.25H5.25V8.75H4.25C3.83579 8.75 3.5 8.41421 3.5 8C3.5 7.58579 3.83579 7.25 4.25 7.25H5.25V6L5.24999 5.9105C5.24996 5.59655 5.24992 5.29206 5.25403 5.00066C4.17262 5.00529 3.58008 5.04224 3.13886 5.33706C2.92048 5.48298 2.73298 5.67048 2.58706 5.88886C2.25 6.39331 2.25 7.09554 2.25 8.5V21.25H1.75C1.33579 21.25 1 21.5858 1 22C1 22.4142 1.33579 22.75 1.75 22.75H21.75C22.1642 22.75 22.5 22.4142 22.5 22C22.5 21.5858 22.1642 21.25 21.75 21.25H21.25V8.5ZM9 11.75C9 11.3358 9.33579 11 9.75 11H13.75C14.1642 11 14.5 11.3358 14.5 11.75C14.5 12.1642 14.1642 12.5 13.75 12.5H9.75C9.33579 12.5 9 12.1642 9 11.75ZM9 14.75C9 14.3358 9.33579 14 9.75 14H13.75C14.1642 14 14.5 14.3358 14.5 14.75C14.5 15.1642 14.1642 15.5 13.75 15.5H9.75C9.33579 15.5 9 15.1642 9 14.75ZM11.75 18.25C12.1642 18.25 12.5 18.5858 12.5 19V21.25H11V19C11 18.5858 11.3358 18.25 11.75 18.25ZM9 6.25C9 5.83579 9.33579 5.5 9.75 5.5H13.75C14.1642 5.5 14.5 5.83579 14.5 6.25C14.5 6.66421 14.1642 7 13.75 7H9.75C9.33579 7 9 6.66421 9 6.25ZM9 9.25C9 8.83579 9.33579 8.5 9.75 8.5H13.75C14.1642 8.5 14.5 8.83579 14.5 9.25C14.5 9.66421 14.1642 10 13.75 10H9.75C9.33579 10 9 9.66421 9 9.25Z" fill="currentColor"/>
            </svg>
            <span class="ml-3">Kabupaten/Kota</span>
        </a>
        <a href="{{ route('kecamatan')}}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('kecamatan') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M15 2H17C18.8856 2 19.8284 2 20.4142 2.58579C21 3.17157 21 4.11438 21 6V21.25H22C22.4142 21.25 22.75 21.5858 22.75 22C22.75 22.4142 22.4142 22.75 22 22.75H2C1.58579 22.75 1.25 22.4142 1.25 22C1.25 21.5858 1.58579 21.25 2 21.25H3V9C3 7.11438 3 6.17157 3.58579 5.58579C4.17157 5 5.11438 5 7 5H11C12.8856 5 13.8284 5 14.4142 5.58579C15 6.17157 15 7.11438 15 9V21.25H16.5V9L16.5 8.91051C16.5001 8.04488 16.5002 7.25121 16.4134 6.60559C16.3178 5.89462 16.0929 5.14317 15.4749 4.52513C14.8568 3.90708 14.1054 3.68219 13.3944 3.5866C12.7579 3.50102 11.9774 3.49991 11.126 3.49999C11.2103 3.11275 11.351 2.82059 11.5858 2.58579C12.1716 2 13.1144 2 15 2ZM5.25 8C5.25 7.58579 5.58579 7.25 6 7.25H12C12.4142 7.25 12.75 7.58579 12.75 8C12.75 8.41421 12.4142 8.75 12 8.75H6C5.58579 8.75 5.25 8.41421 5.25 8ZM5.25 11C5.25 10.5858 5.58579 10.25 6 10.25H12C12.4142 10.25 12.75 10.5858 12.75 11C12.75 11.4142 12.4142 11.75 12 11.75H6C5.58579 11.75 5.25 11.4142 5.25 11ZM5.25 14C5.25 13.5858 5.58579 13.25 6 13.25H12C12.4142 13.25 12.75 13.5858 12.75 14C12.75 14.4142 12.4142 14.75 12 14.75H6C5.58579 14.75 5.25 14.4142 5.25 14ZM9 18.25C9.41421 18.25 9.75 18.5858 9.75 19V21.25H8.25V19C8.25 18.5858 8.58579 18.25 9 18.25Z" fill="currentColor"/>
            </svg>
            <span class="ml-3">Kecamatan</span>
        </a>
        <a href="{{ route('kelurahan')}}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('kelurahan') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.00691 9.10478L10.0069 3.77144C11.1436 2.76108 12.8564 2.76108 13.9931 3.77144L19.9931 9.10478C20.6336 9.67408 21 10.4901 21 11.347V21.2498H22C22.4142 21.2498 22.75 21.5856 22.75 21.9998C22.75 22.414 22.4142 22.7498 22 22.7498H2C1.58579 22.7498 1.25 22.414 1.25 21.9998C1.25 21.5856 1.58579 21.2498 2 21.2498H3V11.347C3 10.4901 3.36644 9.67408 4.00691 9.10478ZM9.25 8.99981C9.25 8.58559 9.58579 8.24981 10 8.24981H14C14.4142 8.24981 14.75 8.58559 14.75 8.99981C14.75 9.41402 14.4142 9.74981 14 9.74981H10C9.58579 9.74981 9.25 9.41402 9.25 8.99981ZM14.052 11.2498C14.9505 11.2498 15.6997 11.2498 16.2945 11.3297C16.9223 11.4141 17.4891 11.5998 17.9445 12.0553C18.4 12.5107 18.5857 13.0775 18.6701 13.7053C18.7501 14.3001 18.75 15.0493 18.75 15.9478L18.75 21.2498H17.25V15.9998C17.25 15.0358 17.2484 14.3882 17.1835 13.9052C17.1214 13.4437 17.0142 13.2462 16.8839 13.1159C16.7536 12.9856 16.5561 12.8784 16.0946 12.8163C15.6116 12.7514 14.964 12.7498 14 12.7498H10C9.03599 12.7498 8.38843 12.7514 7.90539 12.8163C7.44393 12.8784 7.24643 12.9856 7.11612 13.1159C6.9858 13.2462 6.87858 13.4437 6.81654 13.9052C6.75159 14.3882 6.75 15.0358 6.75 15.9998V21.2498H5.25L5.25 15.9478C5.24997 15.0493 5.24994 14.3001 5.32991 13.7053C5.41432 13.0775 5.59999 12.5107 6.05546 12.0553C6.51093 11.5998 7.07773 11.4141 7.70552 11.3297C8.3003 11.2498 9.04952 11.2498 9.948 11.2498H14.052ZM8.25 15.4998C8.25 15.0856 8.58579 14.7498 9 14.7498H15C15.4142 14.7498 15.75 15.0856 15.75 15.4998C15.75 15.914 15.4142 16.2498 15 16.2498H9C8.58579 16.2498 8.25 15.914 8.25 15.4998ZM8.25 18.4998C8.25 18.0856 8.58579 17.7498 9 17.7498H15C15.4142 17.7498 15.75 18.0856 15.75 18.4998C15.75 18.914 15.4142 19.2498 15 19.2498H9C8.58579 19.2498 8.25 18.914 8.25 18.4998Z" fill="currentColor"/>
            </svg>
            <span class="ml-3">Kelurahan</span>
        </a>
        <a href="{{ route('tps')}}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('tps') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447ZM10.5431 7.51724C10.8288 7.2173 10.8172 6.74256 10.5172 6.4569C10.2173 6.17123 9.74256 6.18281 9.4569 6.48276L7.14286 8.9125L6.5431 8.28276C6.25744 7.98281 5.78271 7.97123 5.48276 8.2569C5.18281 8.54256 5.17123 9.01729 5.4569 9.31724L6.59976 10.5172C6.74131 10.6659 6.9376 10.75 7.14286 10.75C7.34812 10.75 7.5444 10.6659 7.68596 10.5172L10.5431 7.51724ZM13 8.25C12.5858 8.25 12.25 8.58579 12.25 9C12.25 9.41422 12.5858 9.75 13 9.75H18C18.4142 9.75 18.75 9.41422 18.75 9C18.75 8.58579 18.4142 8.25 18 8.25H13ZM10.5431 14.5172C10.8288 14.2173 10.8172 13.7426 10.5172 13.4569C10.2173 13.1712 9.74256 13.1828 9.4569 13.4828L7.14286 15.9125L6.5431 15.2828C6.25744 14.9828 5.78271 14.9712 5.48276 15.2569C5.18281 15.5426 5.17123 16.0173 5.4569 16.3172L6.59976 17.5172C6.74131 17.6659 6.9376 17.75 7.14286 17.75C7.34812 17.75 7.5444 17.6659 7.68596 17.5172L10.5431 14.5172ZM13 15.25C12.5858 15.25 12.25 15.5858 12.25 16C12.25 16.4142 12.5858 16.75 13 16.75H18C18.4142 16.75 18.75 16.4142 18.75 16C18.75 15.5858 18.4142 15.25 18 15.25H13Z" fill="currentColor"/>
            </svg>
            <span class="ml-3">TPS</span>
        </a>
        <a href="{{ route('calon')}}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg mx-2 {{ Request::routeIs('calon') ? 'active' : '' }}">
            <svg class="ml-3" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="9.00098" cy="6" r="4" fill="currentColor"/>
            <ellipse cx="9.00098" cy="17.001" rx="7" ry="4" fill="currentColor"/>
            <path d="M20.9996 17.0007C20.9996 18.6576 18.9641 20.0007 16.4788 20.0007C17.211 19.2003 17.7145 18.1958 17.7145 17.0021C17.7145 15.807 17.2098 14.8015 16.4762 14.0007C18.9615 14.0007 20.9996 15.3438 20.9996 17.0007Z" fill="currentColor"/>
            <path d="M17.9996 6.00098C17.9996 7.65783 16.6565 9.00098 14.9996 9.00098C14.6383 9.00098 14.292 8.93711 13.9712 8.82006C14.4443 7.98796 14.7145 7.02547 14.7145 5.99986C14.7145 4.97501 14.4447 4.01318 13.9722 3.18151C14.2927 3.0647 14.6387 3.00098 14.9996 3.00098C16.6565 3.00098 17.9996 4.34412 17.9996 6.00098Z" fill="currentColor"/>
            </svg>
            <span class="ml-3">Calon Kabupaten/Kota</span>
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
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-blue-100">
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