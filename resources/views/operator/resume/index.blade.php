@extends('operator.layout.app')

@section('content')
	<main class="container mx-auto px-4 py-8">
		<div class="mb-10">
			@livewire('operator.resume.pilgub.resume-suara-pilgub')
		</div>
		<div class="mb-10">
			@livewire('operator.resume.pilwali.resume-suara-pilwali')
		</div>
		<div class="mb-10">
			@livewire('operator.resume.pilbup.resume-suara-pilbup')
		</div>
	</main>
@endsection