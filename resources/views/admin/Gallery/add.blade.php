@extends('adminlte::page')

@section('title', 'Adicionar Página')

@section('content_header')
    <h1>Adicionar uma nova página</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
@endsection

@section('js')
    <script>
        const images_upload_url = "{{ route('imageupload') }}";
        const url_pages = "{{ route('pages.list') }}";
        const url_add = "{{ route('pages.add') }}";
    </script>
    <script src="https://cdn.tiny.cloud/1/b6hnb08gw7mg8f83eozwq9gxez89dh47vamxf49vhzugj3s0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/painel.pages.add.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="page-add">
                <div class="form-group">
                    <label for="title">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Título <span style="color: #DC3545">*</span></font>
                        </font>
                    </label>
                    <div class="input-group">
                        <input type="text" name="title" class="form-control" id="title" placeholder="Digite um título para página">
                    </div>
                </div>

                <div class="form-group">
                    <textarea name="body" class="form-control body-field"></textarea>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right" id="submit_add">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Adicionar</font>
                        </font>
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
