<div>
	<div id="slideContainer" class="relative w-full mb-5">
		<div class="flex flex-col p-6">
			<!-- Container untuk slides -->
			<div class="flex-grow">
				<!-- Slide Provinsi -->
				@if ($provinsiData)
					{{-- <div id="slideProvinsi" class="slide101">
						<div class="mb-6 rounded-lg">
							<div class="flex items-start mb-6">
								<img src="{{ asset('storage/' . $provinsiData['logo']) }}" 
									alt="Logo {{ $provinsiData['nama'] }}" 
									class="mr-8 w-40 h-45">
								<div class="flex-grow pl-10">
									<div class="space-y-2">
										<div class="flex justify-between items-center border-b pb-2">
											<h2 class="text-sm font-semibold text-gray-600">Total Suara Sah Provinsi</h2>
											<p class="text-lg font-bold text-gray-800">{{ number_format($provinsiData['suara_sah']) }} Suara</p>
										</div>
										<div class="flex justify-between items-center border-b pb-2">
											<h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah Provinsi</h2>
											<p class="text-lg font-bold text-gray-800">{{ number_format($provinsiData['suara_tidak_sah']) }} Suara</p>
										</div>
										<div class="flex justify-between items-center border-b pb-2">
											<h2 class="text-sm font-semibold text-gray-600">Total DPT Provinsi</h2>
											<p class="text-lg font-bold text-gray-800">{{ number_format($provinsiData['dpt']) }} Orang</p>
										</div>
										<div class="flex justify-between items-center">
											<h2 class="text-sm font-semibold text-gray-600">Total Abstain Provinsi</h2>
											<p class="text-lg font-bold text-gray-800">{{ number_format($provinsiData['abstain']) }} Orang</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="p-3 text-white bg-blue-900 rounded-lg">
							<div class="flex items-center justify-between">
								<div class="flex flex-col items-start w-1/3">
									<div class="flex items-center mb-1">
										<div class="w-4 h-4 mr-2 bg-green-500"></div>
										<span>70,00% - 100,00% DPT » Hijau</span>
									</div>
									<div class="flex items-center mb-1">
										<div class="w-4 h-4 mr-2 bg-yellow-500"></div>
										<span>50,00% - 69,99% DPT » Kuning</span>
									</div>
									<div class="flex items-center">
										<div class="w-4 h-4 mr-2 bg-red-500"></div>
										<span>0,00% - 49,99% DPT » Merah</span>
									</div>
								</div>
								<div class="text-center w-1/3">
									<h2 class="text-xl font-bold">Tingkat Partisipasi Masyarakat Provinsi {{ $provinsiData['nama'] }}</h2>
								</div>
								<div class="text-right w-1/3">
									<div class="text-4xl font-bold {{ $provinsiData['warna_partisipasi'] === 'green' ? 'text-green-400' : 
										($provinsiData['warna_partisipasi'] === 'yellow' ? 'text-yellow-400' : 'text-red-400') }}">
										{{ number_format($provinsiData['partisipasi'], 2) }}%
									</div>
								</div>
							</div>
						</div>
					</div> --}}
					@foreach ($kabupatenData as $id => $data)
						<div id="slide{{ $id }}" class="slide101 {{ $loop->first ? 'active' : '' }}">
							<div class="mb-6 rounded-lg">
								<div class="flex items-start mb-6">
									<img src="{{ asset('storage/' . $data['logo']) }}" alt="Logo {{ $data['nama'] }}" class="mr-8 w-40 h-45">
									<div class="flex-grow pl-10">
										<div class="space-y-2">
											<div class="flex justify-between items-center border-b pb-2">
												<h2 class="text-sm font-semibold text-gray-600">Total Suara Sah</h2>
												<p class="text-lg font-bold text-gray-800">{{ number_format($data['suara_sah']) }} Suara</p>
											</div>
											<div class="flex justify-between items-center border-b pb-2">
												<h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah</h2>
												<p class="text-lg font-bold text-gray-800">{{ number_format($data['suara_tidak_sah']) }} Suara</p>
											</div>
											<div class="flex justify-between items-center border-b pb-2">
												<h2 class="text-sm font-semibold text-gray-600">Total DPT</h2>
												<p class="text-lg font-bold text-gray-800">{{ number_format($data['dpt']) }} Orang</p>
											</div>
											<div class="flex justify-between items-center">
												<h2 class="text-sm font-semibold text-gray-600">Total Abstain</h2>
												<p class="text-lg font-bold text-gray-800">{{ number_format($data['abstain']) }} Orang</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="p-3 text-white bg-blue-900 rounded-lg">
								<div class="flex items-center justify-between">
									<div class="flex flex-col items-start w-1/3">
										<div class="flex items-center mb-1">
											<div class="w-4 h-4 mr-2 bg-green-500"></div>
											<span>70,00% - 100,00% DPT » Hijau</span>
										</div>
										<div class="flex items-center mb-1">
											<div class="w-4 h-4 mr-2 bg-yellow-500"></div>
											<span>50,00% - 69,99% DPT » Kuning</span>
										</div>
										<div class="flex items-center">
											<div class="w-4 h-4 mr-2 bg-red-500"></div>
											<span>0,00% - 49,99% DPT » Merah</span>
										</div>
									</div>
									<div class="text-center w-1/3">
										<h2 class="text-xl font-bold">Tingkat Partisipasi Masyarakat {{$data['nama']}}</h2>
									</div>
									<div class="text-right w-1/3">
										<div class="text-4xl font-bold {{ $data['warna_partisipasi'] === 'green' ? 'text-green-400' : 
											($data['warna_partisipasi'] === 'yellow' ? 'text-yellow-400' : 'text-red-400') }}">
											{{ number_format($data['partisipasi'], 2) }}%
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>

	{{-- Slider --}}
	<div class="flex justify-center items-center w-full mb-8">
		<div class="flex items-center gap-2 px-4 py-2 bg-white backdrop-blur rounded-full">
			{{-- Prev Button --}}
			<button id="prevSlide101" class="text-blue-600 hover:text-blue-800 transition-colors">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
				</svg>
			</button>

			{{-- Dots container --}}
			<div id="sliderDots" class="flex items-center gap-1 mx-2"></div>

			{{-- Play/Pause Button --}}
			<button id="playPauseBtn" class="text-blue-600 hover:text-blue-800 transition-colors">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-7 h-7 pause-icon">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6" />
				</svg>
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-7 h-7 play-icon hidden">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
				</svg>
			</button>

			{{-- Next Button --}}
			<button id="nextSlide101" class="text-blue-600 hover:text-blue-800 transition-colors">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
				</svg>
			</button>
		</div>
	</div>

	{{-- Foto Paslon Pilgub --}}
	<div class="relative overflow-hidden w-[1080px] mx-auto mb-8">
		{{-- Tombol Navigasi Kiri untuk Paslon --}}
		<button id="prevSlideCandidate" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white p-3 shadow-lg z-20 transition-all duration-300 rounded-r-lg">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3560a0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
			</svg>
		</button>

		<div id="candidateSlider" class="flex transition-transform duration-500 ease-in-out relative">
			@if($provinsiData && !empty($provinsiData['candidates']))
				@php
					$currentType = '';
					$counter = 0;
					$lastPilgubNumber = 0;
				@endphp

				<!-- View Provinsi - tetap menampilkan Kalimantan Timur -->
				<div class="candidate-slide" data-province="true" style="display: none;">
					<div class="flex justify-center gap-[45px] min-w-[1080px]">
						@foreach($provinsiData['candidates'] as $candidate)
							@if (strtolower($candidate['posisi']) == 'gubernur')
								@php
									$counter = $candidate['nomor_urut'];
								@endphp

								<div class="w-[330px] bg-white rounded-lg shadow overflow-hidden">
									<div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] overflow-hidden">
										@if ($candidate['foto'])
											<img class="w-full h-full object-cover" 
												src="{{ Storage::disk('foto_calon_lokal')->url($candidate['foto']) }}" 
												alt="{{ $candidate['nama'] }} / {{ $candidate['nama_wakil'] }}">
										@endif
									</div>
									<div class="p-4 text-center">
										<h4 class="text-[#52526c] font-bold mb-1">
											{{ $candidate['nama'] }} / {{ $candidate['nama_wakil'] }}
										</h4>
										<p class="text-[#6b6b6b] mb-2">
											{{ $candidate['wilayah'] }}
										</p>
										<p class="text-[#6b6b6b] mb-2">
											PASLON PILGUB {{ $counter }}
										</p>
										<div class="text-[#008bf9] font-medium">
											{{ number_format($candidate['persentase'], 2) }}% | {{ number_format($candidate['total_suara']) }} Suara
										</div>
									</div>
								</div>
							@endif
						@endforeach
					</div>
				</div>

				{{-- View Kabupaten - menampilkan nama kabupaten masing-masing --}}
				@foreach ($kabupatenData as $kabupatenId => $kabupatenInfo)
					@php
						// Filter to only show gubernur candidates
						$gubernurCalon = array_filter($syncedCalonData[$kabupatenId], function($calon) {
							return strtolower($calon['posisi']) == 'gubernur';
						});
						$gubernurCalon = array_values($gubernurCalon); // Reset array keys
						$kabupatenNama = $kabupatenInfo['nama']; // Mengambil nama kabupaten
					@endphp

					<div class="candidate-slide" data-kabupaten-id="{{ $kabupatenId }}" data-slide-index="0" style="display: none;">
						<div class="flex justify-center gap-[45px] min-w-[1080px]">
							@foreach($gubernurCalon as $index => $calon)
								<div class="w-[330px] bg-white rounded-lg shadow overflow-hidden">
									<div class="h-[217px] bg-gradient-to-b from-[#3560a0] to-[#608ac9] overflow-hidden">
										@if ($calon['foto'])
											<img class="w-full h-full object-cover" 
												src="{{ Storage::disk('foto_calon_lokal')->url($calon['foto']) }}" 
												alt="{{ $calon['nama'] }} / {{ $calon['nama_wakil'] }}">
										@endif
									</div>
									<div class="p-4 text-center">
										<h4 class="text-[#52526c] font-bold mb-1">
											{{ $calon['nama'] }} / {{ $calon['nama_wakil'] }}
										</h4>
										<p class="text-[#6b6b6b] mb-2">
											{{ $kabupatenNama }}
										</p>
										<p class="text-[#6b6b6b] mb-2">
											PASLON PILGUB {{ $calon['nomor_urut'] }}
										</p>
										<div class="text-[#008bf9] font-medium">
											{{ number_format($calon['persentase'], 2) }}% | {{ number_format($calon['total_suara']) }} Suara
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				@endforeach
			@endif
		</div>

		{{-- Tombol Navigasi Kanan untuk Paslon --}}
		<button id="nextSlideCandidate" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white p-3 shadow-lg z-20 transition-all duration-300 rounded-l-lg">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#3560a0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
			</svg>
		</button>
	</div>
</div>

@script
    <script>
		console.log('Init');

        // Script untuk mengatur slide partisipasi dan kandidat
		function init1() {
            const slideContainer = document.getElementById('slideContainer');
            const slides = document.querySelectorAll('.slide101');
            const candidateSlides = document.querySelectorAll('.candidate-slide');
            const prevBtn = document.getElementById('prevSlide101');
            const nextBtn = document.getElementById('nextSlide101');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const pauseIcon = playPauseBtn.querySelector('.pause-icon');
            const playIcon = playPauseBtn.querySelector('.play-icon');
            
            // Create dots container
            const dotsContainer = document.createElement('div');
            dotsContainer.className = 'flex items-center gap-1 mx-2';
            playPauseBtn.parentNode.insertBefore(dotsContainer, playPauseBtn);
            
            let currentSlide = 0;
            let isPlaying = true;
            let slideInterval = null;

            // Create dots for each slide
            slides.forEach((_, index) => {
                const dot = document.createElement('button');
                dot.className = `dot ${index === 0 ? 'active' : ''}`;
                dot.setAttribute('aria-label', `Go to slide ${index + 1}`);
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    showSlides(currentSlide);
                    if (isPlaying) {
                        startSlideShow();
                    }
                });
                dotsContainer.appendChild(dot);
            });

            // Update dots
            function updateDots() {
                document.querySelectorAll('.dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }

            // Hide all slides
            function hideAllSlides() {
                slides.forEach(slide => {
                    slide.classList.remove('active');
                    slide.classList.add('fade-out');
                    slide.style.display = 'none';
                });
                if (candidateSlides) {
                    candidateSlides.forEach(slide => {
                        slide.style.display = 'none';
                    });
                }
            }

            // Show specific slides
            function showSlides(index) {
                hideAllSlides();
                
                // Show participation slide
                if (slides[index]) {
                    slides[index].style.display = 'block';
                    slides[index].classList.remove('fade-out');
                    slides[index].classList.add('active', 'fade-in');
                }
                
                // Show candidate slide
                if (candidateSlides && candidateSlides[index]) {
                    candidateSlides[index].style.display = 'block';
                }
                
                updateDots();
            }

            // Next slide function
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlides(currentSlide);
            }

            // Previous slide function
            function prevSlide() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlides(currentSlide);
            }

            // Start auto-sliding
            function startSlideShow() {
                if (slideInterval) clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            }

            // Toggle play/pause
            function togglePlayPause() {
                isPlaying = !isPlaying;
                if (isPlaying) {
                    startSlideShow();
                    pauseIcon.classList.remove('hidden');
                    playIcon.classList.add('hidden');
                } else {
                    clearInterval(slideInterval);
                    pauseIcon.classList.add('hidden');
                    playIcon.classList.remove('hidden');
                }
            }

            // Initialize slider
            hideAllSlides();
            showSlides(0);
            startSlideShow();

            // Event listeners for navigation
            prevBtn.addEventListener('click', () => {
                prevSlide();
                if (isPlaying) {
                    startSlideShow();
                }
            });

            nextBtn.addEventListener('click', () => {
                nextSlide();
                if (isPlaying) {
                    startSlideShow();
                }
            });

            playPauseBtn.addEventListener('click', togglePlayPause);

            // Add keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                    if (isPlaying) startSlideShow();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                    if (isPlaying) startSlideShow();
                } else if (e.key === ' ') {
                    e.preventDefault();
                    togglePlayPause();
                }
            });

            // Add swipe support for touch devices
            let touchStartX = 0;
            let touchEndX = 0;

            if (slideContainer) {
                slideContainer.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                });

                slideContainer.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                });
            }

            function handleSwipe() {
                const swipeThreshold = 50;
                const difference = touchStartX - touchEndX;

                if (Math.abs(difference) > swipeThreshold) {
                    if (difference > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                    if (isPlaying) startSlideShow();
                }
            }

            // Pause on hover
            if (slideContainer) {
                slideContainer.addEventListener('mouseenter', () => {
                    if (isPlaying) {
                        clearInterval(slideInterval);
                    }
                });

                slideContainer.addEventListener('mouseleave', () => {
                    if (isPlaying) {
                        startSlideShow();
                    }
                });
            }

            // Add styles for dots and animations
            const style = document.createElement('style');
            style.textContent = `
                .dot {
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background-color: #CBD5E0;
                    transition: background-color 0.3s ease;
                }
                
                .dot.active {
                    background-color: #3560a0;
                }

                .fade-in {
                    animation: fadeIn 0.5s ease-in forwards;
                }
                
                .fade-out {
                    animation: fadeOut 0.5s ease-out forwards;
                }
                
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                
                @keyframes fadeOut {
                    from { opacity: 1; }
                    to { opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }

        // Script untuk mengatur slide kandidat dan sinkronisasi dengan slide partisipasi
		function init2() {
            const candidateSlides = document.querySelectorAll('.candidate-slide');
            const participationSlides = document.querySelectorAll('.slide101');
            const prevCandidateBtn = document.getElementById('prevSlideCandidate');
            const nextCandidateBtn = document.getElementById('nextSlideCandidate');

            let currentKabupatenId = '';
            let currentSlideIndex = 0;

            // Fungsi untuk mendapatkan ID kabupaten dari slide partisipasi yang aktif
            function getActiveKabupatenId() {
                const activeParticipationSlide = Array.from(participationSlides).find(slide => 
                    slide.style.display !== 'none' || slide.classList.contains('active')
                );
                return activeParticipationSlide ? activeParticipationSlide.id.replace('slide', '') : null;
            }

            // Fungsi untuk mendapatkan total slide dalam satu kabupaten
            function getTotalSlidesForKabupaten(kabupatenId) {
                return Array.from(candidateSlides).filter(slide => 
                    slide.getAttribute('data-kabupaten-id') === kabupatenId ||
                    (kabupatenId === 'Provinsi' && slide.getAttribute('data-province') === 'true')
                ).length;
            }

            // Fungsi untuk menampilkan slide paslon
            function showCandidateSlide(kabupatenId, slideIndex) {
                // Sembunyikan semua slide
                candidateSlides.forEach(slide => {
                    slide.style.display = 'none';
                    slide.classList.remove('fade-in');
                    slide.classList.add('fade-out');
                });

                // Cari dan tampilkan slide yang sesuai
                let targetSlide;
                if (kabupatenId === 'Provinsi') {
                    targetSlide = Array.from(candidateSlides).find(slide => 
                        slide.getAttribute('data-province') === 'true'
                    );
                } else {
                    targetSlide = Array.from(candidateSlides).find(slide => 
                        slide.getAttribute('data-kabupaten-id') === kabupatenId &&
                        parseInt(slide.getAttribute('data-slide-index')) === slideIndex
                    );
                }

                if (targetSlide) {
                    targetSlide.style.display = 'block';
                    targetSlide.classList.remove('fade-out');
                    targetSlide.classList.add('fade-in');
                    currentKabupatenId = kabupatenId;
                    currentSlideIndex = slideIndex;
                }

                // Update visibility tombol navigasi
                updateNavigationButtons(kabupatenId, slideIndex);
            }

            // Fungsi untuk mengupdate visibilitas tombol navigasi
            function updateNavigationButtons(kabupatenId, slideIndex) {
                const totalSlides = getTotalSlidesForKabupaten(kabupatenId);
                
                // Handle khusus untuk provinsi yang hanya memiliki satu slide
                if (kabupatenId === 'Provinsi') {
                    prevCandidateBtn.style.visibility = 'hidden';
                    nextCandidateBtn.style.visibility = 'hidden';
                    return;
                }
                
                prevCandidateBtn.style.visibility = slideIndex === 0 ? 'hidden' : 'visible';
                nextCandidateBtn.style.visibility = slideIndex === totalSlides - 1 ? 'hidden' : 'visible';
            }

            // Event listener untuk tombol navigasi
            prevCandidateBtn.addEventListener('click', function() {
                const kabupatenId = getActiveKabupatenId();
                if (currentSlideIndex > 0) {
                    showCandidateSlide(kabupatenId, currentSlideIndex - 1);
                }
            });

            nextCandidateBtn.addEventListener('click', function() {
                const kabupatenId = getActiveKabupatenId();
                const totalSlides = getTotalSlidesForKabupaten(kabupatenId);
                if (currentSlideIndex < totalSlides - 1) {
                    showCandidateSlide(kabupatenId, currentSlideIndex + 1);
                }
            });

            // Observer untuk memantau perubahan pada slide partisipasi
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'attributes' && 
                        (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                        const kabupatenId = getActiveKabupatenId();
                        if (kabupatenId && kabupatenId !== currentKabupatenId) {
                            showCandidateSlide(kabupatenId, 0);
                        }
                    }
                });
            });

            // Observe semua slide partisipasi
            participationSlides.forEach(slide => {
                observer.observe(slide, {
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            });

            // Inisialisasi tampilan awal
            const initialKabupatenId = getActiveKabupatenId();
            if (initialKabupatenId) {
                showCandidateSlide(initialKabupatenId, 0);
            }
        }

		setTimeout(() => {
			init1();
			init2();
		}, 0);
    </script>
@endscript