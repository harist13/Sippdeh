<!DOCTYPE html>
<html lang="id" class="h-full">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Badan Kesatuan Bangsa Dan Politik Provinsi Kalimantan Timur</title>

		<script src="https://cdn.tailwindcss.com"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

		<link rel="icon" type="image/x-icon" href="{{ asset('assets/logo.png')}}">
		
		<style>
			.sidebar {
				transition: transform 0.3s ease-in-out;
				z-index: 1050;
				/* pastikan lebih tinggi dari navbar */
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
				padding-top: 80px;
				/* Sesuaikan dengan tinggi navbar Anda */
			}

			.navbar-fixed {
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				z-index: 1000;
				/* pastikan lebih rendah dari sidebar */
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

			.participation-red {
				background-color: #ff7675;
			}

			.participation-yellow {
				background-color: #feca57;
			}

			.participation-green {
				background-color: #69d788;
			}

			@media screen and (max-width: 768px) {
				footer .container {
					padding: 0 1rem;
				}

				footer .flex-col {
					align-items: center;
				}

				footer .flex-col>div {
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

				footer .border-t .flex-col>* {
					margin-bottom: 1rem;
				}

				footer .border-t .flex.space-x-4 {
					justify-content: center;
				}
			}

		</style>

		@stack('styles')
	</head>
	<body class="relative flex flex-col h-full bg-gray-100">
		<!-- Sidebar -->
		@include('operator.layout.sidebar')

		<!-- Header & Navbar -->
		@include('operator.layout.header')

		@yield('content')

		<!-- Footer -->
		@include('operator.layout.footer')

		{{-- Chart JS --}}
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

		<!-- Script -->
		@include('operator.layout.script')
	</body>
</html>