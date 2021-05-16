@extends('adminlte::auth.register')

@section('title', 'Cadastro')

@section('js')
    <script>
        const url = "{{ route('auth.register') }}"
        const painel = "{{ route('painel') }}"
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/painel.login.js') }}"></script>
@endsection
