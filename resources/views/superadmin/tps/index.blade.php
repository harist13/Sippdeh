@extends('superadmin.layout.app')

@push('styles')
    <style>
        .container {
            max-width: 1200px;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        table tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        
        /* Responsive styles */
        @media (max-width: 1024px) {
            .flex-col-mobile {
                flex-direction: column;
            }
            .w-full-mobile {
                width: 100%;
            }
            .space-y-2-mobile > * + * {
                margin-top: 0.5rem;
            }
            .mt-4-mobile {
                margin-top: 1rem;
            }
            .px-2-mobile {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .text-sm-mobile {
                font-size: 0.875rem;
            }
            .overflow-x-auto {
                overflow-x: auto;
            }
        }
    </style>
@endpush

@section('content')
    <main class="container flex-grow px-4 mx-auto mt-6">
        @php $status = session('pesan_sukses'); @endphp
        @isset ($status)
            @include('components.alert-berhasil', ['message' => $status])
        @endisset

        @php $status = session('pesan_gagal'); @endphp
        @isset ($status)
            @include('components.alert-gagal', ['message' => $status])
        @endisset

        @if ($errors->first('name') != null)
            @include('components.alert-gagal', ['message' => $errors->first('name')])
        @endif

        @if ($errors->first('dpt') != null)
            @include('components.alert-gagal', ['message' => $errors->first('dpt')])
        @endif

        @if ($errors->first('kelurahan_id') != null)
            @include('components.alert-gagal', ['message' => $errors->first('kelurahan_id')])
        @endif

        @livewire('superadmin.t-p-s')
    </main>
@endsection