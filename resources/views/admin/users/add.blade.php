@extends('adminlte::page')

@section('title', 'Adicionar Usuário')

@section('content_header')
    <h1>Adicionar Usuário</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
@endsection

@section('js')
    <script>
        const url_cep = "{{ route('cep') }}";
        const url_users = "{{ route('users.list') }}";
        const url_add = "{{ route('users.add') }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/imask.js') }}"></script>
    <script src="{{ asset('assets/js/users/painel.users.add.js') }}"></script>
@endsection

@section('content')
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
                <li class="nav-item">
                    <a class="nav-link" href="#avatar" data-toggle="tab">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Avatar</font>
                        </font>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form id="user-add">
                <div class="tab-content">
                    <div class="tab-pane active" id="info">
                        <div class="form-group">
                            <label for="name">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">Nome <span style="color: #DC3545">*</span></font>
                                </font>
                            </label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Digite seu nome">
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
                                    <font style="vertical-align: inherit;">Email <span style="color: #DC3545">*</span></font>
                                </font>
                            </label>
                            <div class="input-group">
                                <input type="text" name="email" class="form-control" id="email" placeholder="Digite seu email">
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
                                    <font style="vertical-align: inherit;">Telefone <span style="color: #DC3545">*</span></font>
                                </font>
                            </label>
                            <div class="input-group">
                                <input type="text" name="telephone" class="form-control" id="telephone" placeholder="(___) _____-____">
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
                                    <font style="vertical-align: inherit;">Data de nascimento <span style="color: #DC3545">*</span></font>
                                </font>
                            </label>
                            <div class="input-group">
                                <input type="text" name="birthday" class="form-control" id="birthday" placeholder="__/__/____">
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
                                    <font style="vertical-align: inherit;">CPF <span style="color: #DC3545">*</span></font>
                                </font>
                            </label>
                            <div class="input-group">
                                <input type="text" name="cpf" class="form-control" id="cpf" placeholder="___.___.___-__">
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
                                    <font style="vertical-align: inherit;">Senha <span style="color: #DC3545">*</span></font>
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
                                    <font style="vertical-align: inherit;">Confirme sua senha <span style="color: #DC3545">*</span></font>
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
                                <input type="text" name="cep" class="form-control" id="cep" placeholder="__.___-__">
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
                                <input type="text" name="uf" class="form-control" id="uf" placeholder="Unidade Federativa (Cidade)">
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
                                <input type="text" name="city" class="form-control" id="city" placeholder="Sua cidade">
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
                                <input type="text" name="district" class="form-control" id="district" placeholder="Seu bairro">
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
                                <input type="text" name="road" class="form-control" id="road" placeholder="Sua rua">
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
                                <input type="text" name="number_home" class="form-control" id="number_home" placeholder="Número da sua residência">
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
                                <textarea class="form-control" name="complement" rows="3" spellcheck="false" data-gramm="false"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="avatar">
                        <div class="d-flex">
                            <div class="d-flex col-sm-6 justify-content-center align-items-center" style="flex:1; height:350px">
                                <div class="custom-file">
                                    <input type="file" name="avatar" class="custom-file-input" id="avatar_new_user">
                                    <label class="custom-file-label new_avatar_label" for="avatar_new_user">
                                        Escolha uma imagem.
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex col-sm-6 new_avatar_view justify-content-center align-items-center" style="flex:1; height:350px"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right" id="submit_add">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">Cadastrar</font>
                    </font>
                </button>
            </div>
        </form>
    </div>
@stop
