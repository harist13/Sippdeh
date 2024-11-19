<div>
	<div class="w-full mb-5">
		<div class="flex flex-col p-6">
			@if ($provinsiData)
				<div>
					<div class="mb-6 rounded-lg">
						<div class="flex items-start mb-6">
							<img src="{{ asset('storage/' . $provinsiData['logo']) }}" 
								alt="Logo {{ $provinsiData['nama'] }}" 
								class="mr-8 w-40 h-45">
							<div class="flex-grow pl-10">
								<div class="space-y-2">
									<div class="flex justify-between items-center border-b pb-2">
										<h2 class="text-sm font-semibold text-gray-600">Total Suara Sah Kabupaten</h2>
										<p class="text-lg font-bold text-gray-800">{{ number_format($provinsiData['suara_sah']) }} Suara</p>
									</div>
									<div class="flex justify-between items-center border-b pb-2">
										<h2 class="text-sm font-semibold text-gray-600">Total Suara Tidak Sah Kabupaten</h2>
										<p class="text-lg font-bold text-gray-800">{{ number_format($provinsiData['suara_tidak_sah']) }} Suara</p>
									</div>
									<div class="flex justify-between items-center border-b pb-2">
										<h2 class="text-sm font-semibold text-gray-600">Total DPT Kabupaten</h2>
										<p class="text-lg font-bold text-gray-800">{{ number_format($provinsiData['dpt']) }} Orang</p>
									</div>
									<div class="flex justify-between items-center">
										<h2 class="text-sm font-semibold text-gray-600">Total Abstain Kabupaten</h2>
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
									{{ number_format($provinsiData['partisipasi'], 1) }}%
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>

	{{-- Foto Paslon Pilgub --}}
	<div class="relative overflow-hidden w-[1080px] mx-auto mb-8">
		<div class="w-[1080px] mx-auto">
			@if ($provinsiData && !empty($provinsiData['candidates']))
				<div data-province="true">
					<div class="flex justify-center gap-[45px]">
						@foreach($provinsiData['candidates'] as $candidate)
							@if (strtolower($candidate['posisi']) == 'gubernur')
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
											PASLON PILGUB {{ $candidate['nomor_urut'] }}
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
			@endif
		</div>
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

        setTimeout(() => {
			init1();
		}, 0);
    </script>
@endscript