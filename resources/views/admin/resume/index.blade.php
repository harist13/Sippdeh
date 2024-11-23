@extends('Admin.layout.app')

@section('content')
<main class="container mx-auto px-4 py-8">
    @if($showPilgub)
        @livewire('Admin.resume.pilgub.per-wilayah.resume-suara-pilgub-per-wilayah')
        @livewire('Admin.resume.pilgub.per-tps.resume-suara-pilgub-per-tps')
        @livewire('Admin.paslon-pilgub')
    @endif

    @if($showPilwali)
        @livewire('Admin.resume.pilwali.per-wilayah.resume-suara-pilwali-per-wilayah', ['kabupatenId' => $kabupatenId])
        @livewire('Admin.resume.pilwali.per-tps.resume-suara-pilwali-per-tps', ['kabupatenId' => $kabupatenId])
        @livewire('Admin.paslon-pilwali', ['kabupatenId' => $kabupatenId])
    @endif

    @if($showPilbup)
        @livewire('Admin.resume.pilbup.per-wilayah.resume-suara-pilbup-per-wilayah', ['kabupatenId' => $kabupatenId])
        @livewire('Admin.resume.pilbup.per-tps.resume-suara-pilbup-per-tps', ['kabupatenId' => $kabupatenId])
        @livewire('Admin.paslon-pilbup', ['kabupatenId' => $kabupatenId])
    @endif
</main>
@endsection