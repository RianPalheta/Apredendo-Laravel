@extends('adminlte::page')

@section('title', 'Editar Marca')

@section('content_header')
    <h1>Editar Marca</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
@endsection

@section('js')
    <script>
        const id = "{{ $brand->id }}"
        const url_edit = "{{ route('brands.update', [1]) }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/brands/painel.brands.edit.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="brand-edit">
                <div class="form-group">
                    <label for="name">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Nome</font>
                        </font>
                    </label>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" id="name" value="{{ $brand->name }}" placeholder="Digite o nome da marca">
                    </div>
                </div>

                <div class="d-flex">
                    <div class="d-flex justify-content-center align-items-center" style="flex:1;">
                        <div class="custom-file">
                            <input type="file" name="img" class="custom-file-input" id="img_new_brand">
                            <label class="custom-file-label new_img_label" for="img_new_brand">
                                Escolha uma imagem.
                            </label>
                        </div>

                        <div class="d-flex new_img_view justify-content-center align-items-center m-2" style="width:100px">
                            <img id="img-brand" width="100%" src="{{ asset('media/brands/'.$brand->img) }}">
                        </div>
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
