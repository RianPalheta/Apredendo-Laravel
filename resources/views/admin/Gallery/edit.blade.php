@extends('adminlte::page')

@section('title', 'Editar Página')

@section('content_header')
    <h1>Editar página</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
@endsection

@section('js')
    <script>
        const images_upload_url = "{{ route('imageupload') }}";
        const url_edit = "{{ route('pages.update', [1]) }}";
        const id = "{{ $page->id }}";
    </script>
    <script src="https://cdn.tiny.cloud/1/b6hnb08gw7mg8f83eozwq9gxez89dh47vamxf49vhzugj3s0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/painel.pages.edit.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="page-edit">
                <div class="form-group">
                    <label for="title">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Título <span style="color: #DC3545">*</span></font>
                        </font>
                    </label>
                    <div class="input-group">
                        <input type="text" name="title" class="form-control" id="title" value="{{ $page->title }}" placeholder="Digite um título para página">
                    </div>
                </div>

                <div class="form-group">
                    <textarea name="body" class="form-control body-field">{{ $page->body }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary float-right" id="submit_add">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">Salvar</font>
                    </font>
                </button>
            </form>
        </div>
    </div>
@stop
