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
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                -webkit-touch-callout: none;
            }

            .navbar-fixed {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
            }

            /* Anti-screenshot and anti-copy styles */
            .protect-content {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                pointer-events: none;
            }

            .protection-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 10000;
                background: transparent;
            }

            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 48px;
                color: rgba(0, 0, 0, 0.1);
                pointer-events: none;
                z-index: 1000;
                white-space: nowrap;
                user-select: none;
            }

            /* Prevent image drag-and-drop */
            img {
                -webkit-user-drag: none;
                -khtml-user-drag: none;
                -moz-user-drag: none;
                -o-user-drag: none;
                user-drag: none;
                pointer-events: none;
            }

            /* Dynamic watermark style */
            .dynamic-watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 3rem;
                color: rgba(0, 0, 0, 0.1);
                white-space: nowrap;
                pointer-events: none;
                z-index: 9998;
                user-select: none;
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

    <body class="relative flex flex-col h-full bg-gray-100" oncopy="return false" oncut="return false" onpaste="return false" onselectstart="return false" ondragstart="return false">
        <!-- Protection Overlay -->
        <div class="protection-overlay"></div>

      

        <!-- Sidebar -->
        @include('Tamu.layout.sidebar')

        <!-- Header & Navbar -->
        @include('Tamu.layout.header')

        @yield('content')

        <!-- Footer -->
        @include('Tamu.layout.footer')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

        <script>
            // Enhanced Screenshot Prevention
            document.addEventListener('keydown', function(event) {
                // Prevent PrintScreen
                if (event.key === 'PrintScreen' || event.keyCode === 44) {
                    event.preventDefault();
                    showScreenshotAttemptWarning();
                    return false;
                }

                // Prevent Win+Shift+S (Windows Snipping Tool)
                if (event.shiftKey && event.key === 'S') {
                    event.preventDefault();
                    showScreenshotAttemptWarning();
                    return false;
                }

                // Prevent Alt+PrtScn
                if (event.altKey && (event.key === 'PrintScreen' || event.keyCode === 44)) {
                    event.preventDefault();
                    showScreenshotAttemptWarning();
                    return false;
                }

                // Prevent Ctrl+PrtScn
                if (event.ctrlKey && (event.key === 'PrintScreen' || event.keyCode === 44)) {
                    event.preventDefault();
                    showScreenshotAttemptWarning();
                    return false;
                }

                // Prevent Snipping Tool shortcuts
                if ((event.ctrlKey && event.shiftKey && event.key === 'S') || 
                    (event.windowsKey && event.shiftKey && event.key === 'S') || 
                    (event.metaKey && event.shiftKey && event.key === 'S')) {
                    event.preventDefault();
                    showScreenshotAttemptWarning();
                    return false;
                }

                // Prevent Ctrl+Alt+PrtScn
                if (event.ctrlKey && event.altKey && (event.key === 'PrintScreen' || event.keyCode === 44)) {
                    event.preventDefault();
                    showScreenshotAttemptWarning();
                    return false;
                }

                // Prevent Fn+PrintScreen
                if (event.keyCode === 44 || event.key === 'PrintScreen') {
                    event.preventDefault();
                    showScreenshotAttemptWarning();
                    return false;
                }

                // Prevent Ctrl+P (Print)
                if (event.ctrlKey && event.keyCode === 80) {
                    event.preventDefault();
                    return false;
                }

                // Prevent Ctrl+C (Copy)
                if (event.ctrlKey && event.keyCode === 67) {
                    event.preventDefault();
                    return false;
                }

                // Prevent Ctrl+Shift+I (Developer Tools)
                if (event.ctrlKey && event.shiftKey && event.keyCode === 73) {
                    event.preventDefault();
                    return false;
                }

                // Prevent Ctrl+Shift+C (Developer Tools Element Inspector)
                if (event.ctrlKey && event.shiftKey && event.keyCode === 67) {
                    event.preventDefault();
                    return false;
                }

                // Prevent Ctrl+U (View Source)
                if (event.ctrlKey && event.keyCode === 85) {
                    event.preventDefault();
                    return false;
                }

                // Prevent F12 (Developer Tools)
                if (event.keyCode === 123) {
                    event.preventDefault();
                    return false;
                }
            });

            // Show warning when screenshot is attempted
            function showScreenshotAttemptWarning() {
                Swal.fire({
                    title: 'Screenshot Blocked',
                    text: 'Screenshots are not allowed on this page.',
                    icon: 'warning',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }

            // Clear clipboard on screenshot attempt
            window.addEventListener('keyup', function(event) {
                if (event.key === 'PrintScreen' || event.keyCode === 44) {
                    navigator.clipboard.writeText('').then(function() {
                        console.log('Clipboard cleared');
                    }).catch(function(err) {
                        console.error('Failed to clear clipboard:', err);
                    });
                }
            });

            // Monitor for screenshot attempts
            setInterval(function() {
                // Clear clipboard periodically
                navigator.clipboard.writeText('').catch(function(){});
                
                // Check if the window loses focus
                window.addEventListener('blur', function() {
                    setTimeout(function() {
                        window.focus();
                    }, 0);
                });
            }, 1000);

            // Prevent screenshots using Page Visibility API
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'hidden') {
                    navigator.clipboard.writeText('').catch(function(){});
                }
            });

            // Disable right-click context menu
            document.addEventListener('contextmenu', function(event) {
                event.preventDefault();
                return false;
            });

            // Disable text selection
            document.addEventListener('selectstart', function(event) {
                event.preventDefault();
                return false;
            });

            // Disable drag and drop
            document.addEventListener('dragstart', function(event) {
                event.preventDefault();
                return false;
            });

            // Monitor for developer tools
            setInterval(function() {
                const widthThreshold = window.outerWidth - window.innerWidth > 160;
                const heightThreshold = window.outerHeight - window.innerHeight > 160;
                
                if (widthThreshold || heightThreshold) {
                    document.body.innerHTML = 'Developer tools detected!';
                }
            }, 1000);

            // Create dynamic watermarks
            function createRandomWatermarks() {
                const watermarkText = 'PROTECTED CONTENT - ' + new Date().toISOString();
                const watermarks = document.querySelectorAll('.watermark');
                watermarks.forEach(watermark => {
                    watermark.textContent = watermarkText;
                });
            }

            setInterval(createRandomWatermarks, 5000);
			// Get the modal
			var modal = document.getElementById("profileModal");

			// Get the button that opens the modal
			var btn = document.querySelector("a[href='#']:has(i.fas.fa-user-circle)");

			// Get the <span> element that closes the modal
			var span = document.getElementById("closeModal");

			// Get the save button
			var saveBtn = document.getElementById("saveProfile");

			// When the user clicks the button, open the modal 
			btn.onclick = function (event) {
				event.preventDefault();
				modal.classList.remove("hidden");
			}

			// When the user clicks on <span> (x), close the modal
			span.onclick = function () {
				modal.classList.add("hidden");
			}

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function (event) {
				if (event.target == modal) {
					modal.classList.add("hidden");
				}
			}

			// Handle save profile
			saveBtn.onclick = function () {
				var form = document.getElementById("profileForm");
				var formData = new FormData(form);

				fetch('/updateTamu', {
						method: 'POST',
						body: formData,
						headers: {
							'X-CSRF-TOKEN': '{{ csrf_token() }}',
						},
					})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							Swal.fire({
								title: 'Success!',
								text: 'Profile updated successfully',
								icon: 'success',
								confirmButtonText: 'OK'
							}).then((result) => {
								if (result.isConfirmed) {
									modal.classList.add("hidden");
								}
							});
						} else {
							Swal.fire({
								title: 'Error!',
								text: 'Error updating profile: ' + data.message,
								icon: 'error',
								confirmButtonText: 'OK'
							});
						}
					})
					.catch((error) => {
						console.error('Error:', error);
						Swal.fire({
							title: 'Error!',
							text: 'An error occurred while updating the profile',
							icon: 'error',
							confirmButtonText: 'OK'
						});
					});
			}


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


			// Close sidebar when clicking outside
			document.addEventListener('click', (event) => {
				if (!sidebar.contains(event.target) && !openSidebarBtn.contains(event.target)) {
					sidebar.classList.add('hidden');
				}
			});

			document.addEventListener('DOMContentLoaded', function () {
				const sidebarItems = document.querySelectorAll('.sidebar-item');

				sidebarItems.forEach(item => {
					item.addEventListener('click', function () {
						// Remove active class from all items
						sidebarItems.forEach(i => i.classList.remove('bg-blue-200',
							'text-blue-700'));

						// Add active class to clicked item
						this.classList.add('bg-blue-200', 'text-blue-700');

						// Store active item in localStorage
						localStorage.setItem('activeMenuItem', this.getAttribute('href'));
					});
				});

				// Check for active item on page load
				const activeMenuItem = localStorage.getItem('activeMenuItem');
				if (activeMenuItem) {
					const activeItem = document.querySelector(`.sidebar-item[href="${activeMenuItem}"]`);
					if (activeItem) {
						activeItem.classList.add('bg-blue-200', 'text-blue-700');
					}
				}
			});
		</script>

		@stack('scripts')
	</body>
</html>