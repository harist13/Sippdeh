@extends('operator.layout.app')

@section('content')
	<main class="container mx-auto px-4 py-8">
		@livewire('operator.resume.pilgub.resume-suara-pilgub')

		@php
            $calonWalikota = App\Models\Calon::query()
                ->wherePosisi('WALIKOTA')
                ->whereHas('kabupaten', fn ($builder) => $builder->whereNama(session('user_wilayah')));
        @endphp

		@if ($calonWalikota->count())
			<div class="mt-10">
				@livewire('operator.resume.pilwali.resume-suara-pilwali')
			</div>
		@else
			<div class="mt-10">
				@livewire('operator.resume.pilbup.resume-suara-pilbup')
			</div>
		@endif
	</main>
@endsection