@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Usuários <span></span></h1>
        <a title="Adicionar um novo usuário" href="{{ route('users.create') }}" class="btn btn-success"><i class="fas fa-user-plus"></i></a>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <style>th,td{min-width: 150px;}.card-header,.card-body{overflow-x: auto;}table{min-width: 600px;}</style>
@endsection

@section('js')
    <script>
        const logged = {{ $logged }};
        const url = "{{ route('getUsers') }}";
        const url_edit = "{{ route('users.edit', [1]) }}";
        const url_del = "{{ route('users.destroy', [1]) }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/users/painel.users.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header clearfix row justify-content-between align-items-center">
            <div class="col-md-3">
                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                    <font style="vertical-align: inherit;">
                        <font id="info-pages" style="vertical-align: inherit;">...</font>
                    </font>
                </div>
            </div>


            <form>
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

            <div class="card-tools">
                <form id="search">
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

            <div class="float-right">
                <ul class="pagination pagination-sm m-0 float-right"></ul>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="25%">Nome</th>
                        <th width="25%">E-mail</th>
                        <th width="20%">Telefone</th>
                        <th width="20%">Data de cadastro</th>
                        <th width="10%">Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@stop
