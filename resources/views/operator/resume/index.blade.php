@extends('operator.layout.app')

@section('content')
	<main class="container mx-auto px-4 py-8">
		@livewire('operator.resume.pilgub.per-wilayah.resume-suara-pilgub-per-wilayah')
		@livewire('operator.resume.pilgub.per-tps.resume-suara-pilgub-per-tps')
		@livewire('operator.paslon-pilgub')
		
		@php
            $calonWalikota = App\Models\Calon::query()
                ->wherePosisi('WALIKOTA')
                ->whereHas('kabupaten', fn ($builder) => $builder->whereNama(session('user_wilayah')));
        @endphp

		@if ($calonWalikota->count())
			<div class="mt-10">
				@livewire('operator.resume.pilwali.per-wilayah.resume-suara-pilwali-per-wilayah')
				@livewire('operator.resume.pilwali.per-tps.resume-suara-pilwali-per-tps')
				@livewire('operator.paslon-pilwali')
			</div>
		@else
			<div class="mt-10">
				@livewire('operator.resume.pilbup.resume-suara-pilbup')
			</div>
		@endif
	</main>
@endsection