@extends('adminlte::page')

@section('title', 'Galeria')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Galeria <span></span></h1>
        <button title="Adicionar uma nova foto" class="btn btn-success" data-toggle="modal" data-target="#add-photo"><i class="fas fa-plus"></i></button>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/progressive-image.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
@endsection

@section('js')
    <script>
        const url = "{{ route('getPhotos') }}";
        const asset = "{{ asset('media/gallery') }}";
        const url_gallery = "{{ Request::url() }}";
        const url_add = "{{ route('gallery.add') }}";
        const url_edit = "{{ route('gallery.update', [1]) }}";
        const url_del = "{{ route('gallery.destroy', [1]) }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/lozad.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/gallery/painel.gallery.js') }}"></script>
@endsection

@section('content')
    <div class="modal fade" id="add-photo" tabindex="-1" role="dialog" aria-labelledby="img-add-photo" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="img-add-photo">Adicionar Fotos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="photo-add">
                        <div class="form-group">
                            <div class="drop-zone">
                                <span class="drop-zone__prompt">Solte a imagem aqui ou clique para fazer o upload.</span>
                                <input type="file" name="photo[]" class="drop-zone__input" multiple>
                            </div>
                        </div>

                        <div class="form-group" style="margin: 0">
                            <div class="progress" style="height: 15px;">
                                <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </form>
                    <small class="form-text text-muted">Imagens suportadas: JPEG, JPG, PNG, BMP, GIF, SVG e WEBP - Tamanho máximo da imagem é 2MB - Tamanho máximo de upload é 8MB.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header clearfix d-flex justify-content-between align-items-center">
            <div class="card-tools" style="flex:1">
                <form id="search">
                    <div class="input-group input-group-sm">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Procurar foto...">

                        {{-- <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                            </button>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div id="gallery-area" class="row d-flex justify-content-center align-items-center"></div>
        </div>
    </div>
@stop
