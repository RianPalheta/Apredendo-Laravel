@extends('adminlte::page')

@section('title', 'Editar Marca')

@section('content_header')
    <h1>Editar Marca</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
@endsection

@section('js')
    <script>
        const id = "{{ $cat->id }}"
        const opt_sub = "{{ $cat->sub }}";
        const url = "{{ route('getCategories') }}";
        const url_edit = "{{ route('categories.update', [1]) }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/categories/painel.categories.edit.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="categories-edit">
                <div class="form-group">
                    <label for="name">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Nome <span style="color: #DC3545">*</span></font>
                        </font>
                    </label>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" id="name" value="{{ $cat->name }}"placeholder="Digite o nome da categoria">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Sub-categoria</font>
                        </font>
                    </label>
                    <select name="sub" id="category" class="form-control">
                        <option value="null">
                            Nenhuma
                        </option>
                    </select>
                </div>

                <div class="d-flex">
                    <div class="d-flex justify-content-center align-items-center" style="flex:1;">
                        <div class="custom-file">
                            <input type="file" name="img" class="custom-file-input" id="img_new_categorie">
                            <label class="custom-file-label new_img_label" for="img_new_categorie">
                                Escolha uma imagem.
                            </label>
                        </div>

                        <div class="d-flex new_img_view justify-content-center align-items-center m-2" style="width:100px">
                            <img id="img-categorie" width="100%" src="{{ asset('media/categories/'.$cat->img) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="progress" style="height: 15px;">
                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right" id="submit_add">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">Salvar</font>
                    </font>
                </button>
            </div>
        </form>
    </div>
@stop
