<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPPPDEH Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/output.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="h-full overflow-hidden">
    <div class="flex min-h-screen">
        <!-- Left side - Login form -->
        <div class="relative flex items-center justify-center w-full p-8 overflow-hidden bg-white lg:w-1/2 lg:p-12">
            <!-- Background pattern -->
            <div class="absolute inset-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" preserveAspectRatio="none">
                    <path d="M0 0C200 150 400 150 600 0C800 150 1000 150 1200 0V600C1000 450 800 450 600 600C400 450 200 450 0 600V0Z" fill="url(#paint0_linear)" fill-opacity="0.1"/>
                    <defs>
                        <linearGradient id="paint0_linear" x1="600" y1="0" x2="600" y2="600" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#E5E7EB"/>
                            <stop offset="1" stop-color="#E5E7EB" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            
            <div class="z-10 w-full max-w-md">
                <h2 class="mb-2 text-2xl font-bold text-gray-800 lg:text-3xl">Selamat Datang di SIPPPDEH</h2>
                <p class="mb-8 text-sm text-gray-600 lg:text-base">Mohon masukkan informasi akun Anda mulai menggunakan SIPPPDEH</p>
                
                <form id="loginForm" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700" for="email">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="text-gray-400 fas fa-user"></i>
                            </span>
                            <input name="email" class="w-full px-3 py-2 pl-10 leading-tight text-gray-700 border rounded appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" type="email" placeholder="Masukan email yang telah terdaftar" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700" for="password">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="text-gray-400 fas fa-lock"></i>
                            </span>
                            <input name="password" class="w-full px-3 py-2 pl-10 leading-tight text-gray-700 border rounded appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" type="password" placeholder="6 Karakter atau lebih">
                        </div>
                    </div>
                    <div>
                        <button class="w-full px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline" type="submit">
                            Masuk Ke Sistem
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Right side - Image and text overlay -->
        <div class="relative hidden w-1/2 bg-gray-800 lg:block">
            <img src="{{ asset('assets/login.png')}}" alt="Building" class="object-cover w-full h-full opacity-70">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="absolute w-full pr-20 text-right text-white bottom-10 left-10">
                <h3 class="mb-2 text-3xl font-bold">Sistem Informasi</h3>
                <p class="text-xl">Pemantauan Perkembangan Politik Daerah</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            let errors = [];

            if (!email) {
                errors.push('Email harus diisi.');
            } else if (!/\S+@\S+\.\S+/.test(email)) {
                errors.push('Format email tidak valid.');
            }

            if (!password) {
                errors.push('Password harus diisi.');
            } else if (password.length < 6) {
                errors.push('Password harus minimal 6 karakter.');
            }

            if (errors.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: errors.join('<br>'),
                });
            } else {
                this.submit();
            }
        });

        // Check for success message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Check for error messages
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '{!! implode("<br>", $errors->all()) !!}',
            });
        @endif
    </script>
</body>
</html>