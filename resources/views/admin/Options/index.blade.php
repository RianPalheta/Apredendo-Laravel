@extends('adminlte::page')

@section('title', 'Opções')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Lista de opções dos produtos <span></span></h1>
        <button title="Adicionar uma nova opção" class="btn btn-success" data-toggle="modal" data-target="#add-option"><i class="fas fa-plus"></i></button>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
@endsection

@section('js')
    <script>
        const url = "{{ route('getOptions') }}";
        const url_add = "{{ route('options.add') }}";
        const url_edit = "{{ route('options.update', [1]) }}";
        const url_del = "{{ route('options.destroy', [1]) }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/options/painel.options.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header row justify-content-between align-items-center">
            <div class="flex-1">
                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                    <font style="vertical-align: inherit;">
                        <font id="info-pages" style="vertical-align: inherit;"></font>
                    </font>
                </div>
            </div>

            <form class="flex-1">
                <select class="form-control">
                    <option value="5"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Por página: 5</font></font></option>
                    <option value="10"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Por página: 10</font></font></option>
                    <option value="20"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Por página: 20</font></font></option>
                    <option value="30"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Por página: 30</font></font></option>
                    <option value="40"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Por página: 40</font></font></option>
                    <option value="50"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Por página: 50</font></font></option>
                    <option value="100"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Por página: 100</font></font></option>
                </select>
            </form>

            <form id="search" class="flex-1">
                <div class="input-group input-group-sm" style="flex:1;">
                <input type="text" name="table_search" class="form-control float-right" placeholder="Procurar">

                <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                </div>
            </form>
        </div>
    </div>
@stop
