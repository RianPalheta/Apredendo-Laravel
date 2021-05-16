@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content_header')
    <h1>Editar Usuário</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
@endsection

@section('js')
    <script>
        const url_cep = "{{ route('cep') }}";
        const assets = "{{ asset('media/users') }}";
        const url_update = "{{ route('users.update', [$user->id]) }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/imask.js') }}"></script>
    <script src="{{ asset('assets/js/painel.users.edit.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('media/users/perfil.jpg') }}" />
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('users.update', [$user->id]) }}" class="dropzone dz-clickable" id="photo_user">
                        @csrf
                        @method('PUT')
                        <div class="dz-default dz-message">
                            <span>
                                Coloque sua foto de perfil aqui.
                            </span>
                        </div>
                        <div class="fallback">
                            <input type="file" name="fileToUpload" accept="image/*">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#info" data-toggle="tab">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">Detalhes</font>
                                </font>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#address" data-toggle="tab">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">Endereço</font>
                                </font>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form id="user-edit">
                        <div class="tab-content">
                            <div class="tab-pane active" id="info">
                                <div class="form-group">
                                    <label for="name">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Nome</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" id="name" placeholder="Digite seu nome">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-user"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Email</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="email" value="{{ $user->email }}" class="form-control" id="email" placeholder="Digite seu email">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-envelope"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="telephone">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Telefone</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="telephone" value="{{ $user->telephone }}" class="form-control" id="telephone" placeholder="(___) _____-____">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-mobile-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="birthday">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Data de nascimento</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="birthday" value="{{ $user->birthday }}" class="form-control" id="birthday" placeholder="__/__/____">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cpf">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">CPF</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="cpf" value="{{ $user->cpf }}" class="form-control" id="cpf" placeholder="___.___.___-__">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-address-card"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Senha</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Digite uma senha">
                                        <div class="input-group-prepend pass" style="cursor: pointer">
                                            <span class="input-group-text">
                                                <i class="far fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Confirme sua senha</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirme sua nova senha">
                                        <div class="input-group-prepend pass" style="cursor: pointer">
                                            <span class="input-group-text">
                                                <i class="far fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="address">
                                <div class="form-group">
                                    <label for="cep">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">CEP</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="cep" value="{{ $user->cep }}" class="form-control" id="cep" placeholder="__.___-__">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-globe-americas"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="uf">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">UF</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="uf" value="{{ $user->uf }}" class="form-control" id="uf" placeholder="Unidade Federativa (Cidade)">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-map"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="city">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Cidade</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="city" value="{{ $user->city }}" class="form-control" id="city" placeholder="Sua cidade">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-building"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="district">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Bairro</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="district" value="{{ $user->district }}" class="form-control" id="district" placeholder="Seu bairro">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="road">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Rua</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="road" value="{{ $user->road }}" class="form-control" id="road" placeholder="Sua rua">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-map-signs"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="number_home">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Número</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="number_home" value="{{ $user->number_home }}" class="form-control" id="number_home" placeholder="Número da sua residência">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-home"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="complement">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Complemento</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="complement" rows="3" spellcheck="false" data-gramm="false">{{ $user->complement }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Salvar</font>
                            </font>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
