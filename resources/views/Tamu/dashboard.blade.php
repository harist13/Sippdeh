@extends('Tamu.layout.app')

@push('styles')
    <style>
        .participation-button {
            display: inline-block;
            width: 100px;
            padding: 3px 0;
            font-size: 14px;
            text-align: center;
            border-radius: 6px;
            font-weight: 500;
            color: white;
        }

        .participation-red {
            background-color: #ff7675;
        }

        .participation-yellow {
            background-color: #feca57;
        }

        .participation-green {
            background-color: #69d788;
        }
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #CBD5E1;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            padding: 0;
            margin: 0 2px;
        }

        .dot.active {
            background-color: #2563EB;
            width: 24px;
            border-radius: 4px;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in forwards;
        }

        .fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        /* slide kusus diagram bar */
        .chart-container {
                position: relative;
                width: 100%;
                padding: 20px;
            }
            
            .nav-button {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                padding: 8px;
                background-color: #1e3a8a;
                color: white;
                border: none;
                border-radius: 9999px;
                cursor: pointer;
                transition: background-color 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10;
            }
            
            .nav-button:hover {
                background-color: #1e40af;
            }
            
            .nav-button-left {
                left: 10px;
            }
            
            .nav-button-right {
                right: 10px;
            }

            .canvas-wrapper {
                padding: 0 50px;
            }

            .chart-title {
                transition: opacity 0.3s ease;
            }

        /* slide kusus paslon */
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            transition: opacity 3s ease-in-out;
        }
        .slide.active {
            opacity: 1;
        }
        #slideContainer {
            max-width: 1100px;
            height: 320px;
            padding-bottom: 2rem;
        }

        .text-right h2 {
            font-size: 1.25rem;
        }

        .text-right .text-4xl {
            font-size: 2rem;
        }

        /* slide partisipasi */
        
        .slide101 {
        display: none;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        }
        .slide101.active {
            display: block;
            opacity: 1;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in forwards;
        }

        .fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }
    </style>
@endpush

@section('content')
    <main class="bg-white shadow-lg rounded-lg p-8 max-w-7xl mx-auto my-8">
        <h1 class="bg-gray-100 rounded-lg font-bold text-center text-2xl mb-3 p-3">Data Perolehan Suara Calon Gubernur dan Wakil Gubernur Se-Kalimantan Timur</h1>

        <div class="container mx-auto">
            {{-- Diagram Batang --}}
            <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden mb-8">
                <h3 class="bg-[#3560A0] text-white text-center py-2 chart-title">
                    Jumlah Perolehan Suara Gubernur Per Kabupaten/Kota
                </h3>

                @livewire('Tamu.dashboard.diagram-bar-pilgub')
            </section>
            
            {{-- Rekap Partisipasi --}}
            <section class="bg-gray-100 rounded-lg shadow-md overflow-hidden">
                <h3 class="bg-[#3560A0] text-white text-center py-2">Jumlah Tingkat Partisipasi Di Kalimantan Timur</h3>

                @livewire('Tamu.dashboard.rekap-pilgub')
            </section>
        </div>
    </main>
@endsection