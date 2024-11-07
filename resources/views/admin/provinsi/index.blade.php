@extends('admin.layout.app')

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

            .space-y-2-mobile>*+* {
                margin-top: 0.5rem;
            }

            .overflow-x-auto {
                overflow-x: auto;
            }

            .text-sm-mobile {
                font-size: 0.875rem;
            }

            .px-2-mobile {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }

    </style>
@endpush

@section('content')
    @livewire('admin.provinsi')
@endsection