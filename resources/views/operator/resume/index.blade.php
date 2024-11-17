@extends('operator.layout.app')

@section('content')
	<main class="container mx-auto px-4 py-8">
		<div class="mb-10">
			@livewire('operator.resume.pilgub.resume-suara-pilgub')
		</div>
		@livewire('operator.resume.pilwali.resume-suara-pilwali')
		@livewire('operator.resume.pilbup.resume-suara-pilbup')
	</main>
@endsection