@extends('Tamu.layout.app')

@section('content')
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-[20px] mb-8 shadow-lg">
            @livewire('Tamu.dashboard.resume-suara-pilwali.resume-suara-per-tps-pilwali')
        </div>
    </main>
@endsection