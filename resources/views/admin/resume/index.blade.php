@extends('admin.layout.app')

@section('content')
<main class="container mx-auto px-4 py-8">
    @if($showPilgub)
        @livewire('admin.resume.pilgub.per-wilayah.resume-suara-pilgub-per-wilayah')
        @livewire('admin.resume.pilgub.per-tps.resume-suara-pilgub-per-tps')
        @livewire('admin.paslon-pilgub')
    @endif

    @if($showPilwali)
        @livewire('admin.resume.pilwali.per-wilayah.resume-suara-pilwali-per-wilayah', ['kabupatenId' => $kabupatenId])
        @livewire('admin.resume.pilwali.per-tps.resume-suara-pilwali-per-tps', ['kabupatenId' => $kabupatenId])
        @livewire('admin.paslon-pilwali', ['kabupatenId' => $kabupatenId])
    @endif

    @if($showPilbup)
        @livewire('admin.resume.pilbup.per-wilayah.resume-suara-pilbup-per-wilayah', ['kabupatenId' => $kabupatenId])
        @livewire('admin.resume.pilbup.per-tps.resume-suara-pilbup-per-tps', ['kabupatenId' => $kabupatenId])
        @livewire('admin.paslon-pilbup', ['kabupatenId' => $kabupatenId])
    @endif
</main>
@endsection