@extends('adminlte::auth.login')

@section('title', 'Login')

@section('js')
    <script>
        const url = "{{ route('auth.login') }}"
        const painel = "{{ route('painel') }}"
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/painel.login.js') }}"></script>
@endsection
