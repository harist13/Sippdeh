@extends('operator.layout.app')

@section('content')
	<main class="container mx-auto px-4 py-8">
		@livewire('operator.resume.pilgub.suara-pilgub')
		@livewire('operator.resume.pilwali.suara-pilwali')
		@livewire('operator.resume.pilbup.suara-pilbup')
	</main>
@endsection