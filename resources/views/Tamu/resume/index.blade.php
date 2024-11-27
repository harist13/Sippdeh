@extends('Tamu.layout.app')

@section('content')
	<main class="container mx-auto px-4 py-8">
		<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
			<h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Tingkat Partisipasi Pemilihan Gubernur Di {{ session('Tamu_kabupaten_name') }}</h3>
			@livewire('Tamu.dashboard.rekap-pilgub')
		</div>
		@livewire('Tamu.resume.pilgub.per-wilayah.resume-suara-pilgub-per-wilayah')
		@livewire('Tamu.paslon-pilgub')

		@if (session('Tamu_jenis_wilayah') == 'kota')
			<div class="mt-10">
				<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
					<h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Tingkat Partisipasi Pemilihan Walikota Di {{ session('Tamu_kabupaten_name') }}</h3>
					@livewire('Tamu.dashboard.rekap-pilwali')
				</div>
				@livewire('Tamu.resume.pilwali.per-wilayah.resume-suara-pilwali-per-wilayah')
				@livewire('Tamu.paslon-pilwali')
			</div>
		@else
			<div class="mt-10">
				<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
					<h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Tingkat Partisipasi Pemilihan Bupati Di {{ session('Tamu_kabupaten_name') }}</h3>
					@livewire('Tamu.dashboard.rekap-pilbup')
				</div>
				@livewire('Tamu.resume.pilbup.per-wilayah.resume-suara-pilbup-per-wilayah')
				@livewire('Tamu.paslon-pilbup')
			</div>
		@endif
	</main>
@endsection