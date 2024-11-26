@extends('operator.layout.app')

@section('content')
	<main class="container mx-auto px-4 py-8">
		<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
			<h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Tingkat Partisipasi Pemilihan Gubernur Di {{ session('operator_kabupaten_name') }}</h3>
			@livewire('operator.dashboard.rekap-pilgub')
		</div>
		@livewire('operator.resume.pilgub.per-wilayah.resume-suara-pilgub-per-wilayah')
		@livewire('operator.paslon-pilgub')

		@if (session('operator_jenis_wilayah') == 'kota')
			<div class="mt-10">
				<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
					<h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Tingkat Partisipasi Pemilihan Walikota Di {{ session('operator_kabupaten_name') }}</h3>
					@livewire('operator.dashboard.rekap-pilwali')
				</div>
				@livewire('operator.resume.pilwali.per-wilayah.resume-suara-pilwali-per-wilayah')
				@livewire('operator.paslon-pilwali')
			</div>
		@else
			<div class="mt-10">
				<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
					<h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Tingkat Partisipasi Pemilihan Bupati Di {{ session('operator_kabupaten_name') }}</h3>
					@livewire('operator.dashboard.rekap-pilbup')
				</div>
				@livewire('operator.resume.pilbup.per-wilayah.resume-suara-pilbup-per-wilayah')
				@livewire('operator.paslon-pilbup')
			</div>
		@endif
	</main>
@endsection