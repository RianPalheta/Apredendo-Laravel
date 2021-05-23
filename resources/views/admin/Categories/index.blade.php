@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Categorias <span></span></h1>
        <a title="Adicionar uma nova marca" href="{{ route('categories.create') }}" class="btn btn-success"><i class="fas fa-plus"></i></a>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
@endsection

@section('js')
    <script>
        const url = "{{ route('getCategories') }}";
        const url_edit = "{{ route('categories.edit', [1]) }}";
        const url_del = "{{ route('categories.destroy', [1]) }}";
        const assets = "{{ asset('media/categories') }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/categories/painel.categories.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header clearfix row justify-content-between align-items-center">
            <div class="col-md-3">
                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                    <font style="vertical-align: inherit;">
                        <font id="info-pages" style="vertical-align: inherit;"></font>
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
                        <th>Nome</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@stop
