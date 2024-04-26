@extends('adminlte::page')
@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop
@vite('resources/js/app.js')
@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop
@section('content')
    @yield('content_body')
@stop
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop
@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js">
        $(document).ready(function() {
            // Add your common script logic here...
        });
    </script>
@endpush
@stack('scripts')
@push('css')
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" rel="stylesheet">
    <style type="text/css">

        {{-- You can add AdminLTE customizations here --}}
    .card-header {
            border-bottom: none;
        }
        .card-title {
            font-weight: 600;
        }
    </style>
@endpush