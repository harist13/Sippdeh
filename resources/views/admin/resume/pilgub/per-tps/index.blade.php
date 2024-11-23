@extends('Admin.layout.app')

@section('content')
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-[20px] mb-8 shadow-lg">
            @livewire('Admin.resume.pilgub.per-tps.resume-suara-per-tps-pilgub')
        </div>
    </main>
@endsection