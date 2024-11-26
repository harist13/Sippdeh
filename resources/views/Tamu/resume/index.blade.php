@extends('Tamu.layout.app')

@section('content')
	<main class="container mx-auto px-4 py-8">
		@livewire('Tamu.resume.pilgub.per-wilayah.resume-suara-pilgub-per-wilayah')
		{{-- @livewire('Tamu.resume.pilgub.per-tps.resume-suara-pilgub-per-tps') --}}
		@livewire('Tamu.paslon-pilgub')

		@if (session('Tamu_jenis_wilayah') == 'kota')
			<div class="mt-10">
				@livewire('Tamu.resume.pilwali.per-wilayah.resume-suara-pilwali-per-wilayah')
				{{-- @livewire('Tamu.resume.pilwali.per-tps.resume-suara-pilwali-per-tps') --}}
				@livewire('Tamu.paslon-pilwali')
			</div>
		@else
			<div class="mt-10">
				@livewire('Tamu.resume.pilbup.per-wilayah.resume-suara-pilbup-per-wilayah')
				{{-- @livewire('Tamu.resume.pilbup.per-tps.resume-suara-pilbup-per-tps') --}}
				@livewire('Tamu.paslon-pilbup')
			</div>
		@endif
	</main>
@endsection