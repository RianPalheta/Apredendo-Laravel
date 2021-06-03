@extends('adminlte::page')

@section('title', 'Configurações')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Configurações do Site</h1>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/semantic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <style>.c-pointer {cursor: pointer} .flex-1 {flex: 1} .btn-tooltip-info {border-radius: 50%;width: 15px;height: 15px;padding: 0;font-size: 10px;line-height: 13px;}</style>
@endsection

@section('js')
    <script>
        const url_cep = "{{ route('cep') }}";
        const url = "{{ route('settings.edit') }}";
        const asset = "{{ asset('media/gallery') }}";
        const url_gallery = "{{ route('getPhotos') }}";
    </script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/lozad.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/imask.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/semantic.min.js') }}"></script>
    <script src="{{ asset('assets/js/setting/painel.setting.js') }}"></script>
@endsection

@section('content')
    <form id="setting">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs justify-content-center" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-site-tab" data-toggle="pill" href="#custom-tabs-four-site" role="tab" aria-controls="custom-tabs-four-site" aria-selected="true">
                            Site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-localization-tab" data-toggle="pill" href="#custom-tabs-four-localization" role="tab" aria-controls="custom-tabs-four-localization" aria-selected="false">
                            Localização
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-appearance-tab" data-toggle="pill" href="#custom-tabs-four-appearance" role="tab" aria-controls="custom-tabs-four-appearance" aria-selected="false">
                            Aparência
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-image-tab" data-toggle="pill" href="#custom-tabs-four-image" role="tab" aria-controls="custom-tabs-four-image" aria-selected="false">
                            Imagens
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-screen-tab" data-toggle="pill" href="#custom-tabs-four-screen" role="tab" aria-controls="custom-tabs-four-screen" aria-selected="false">
                            Tela
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-four-site" role="tabpanel" aria-labelledby="custom-tabs-four-site-tab">
                        <div class="form-group d-flex">
                            <div class="flex-1">
                                <label for="name-site" class="form-control-label c-pointer">
                                    Nome da loja: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="O nome da sua loja virtual. Ele será mostrado no cabeçalho de todas as páginas do seu site se você não enviar um logotipo."></i>
                                </label>
                                <input type="text" id="name-site" class="form-control" name="name-site" value="{{ $settings['name-shop'] }}">
                            </div>
                            <div class="col-5">
                                <label for="type-company" class="form-control-label c-pointer">
                                    Tipo de empresa: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="Os tipos societários das empresas são também conhecidos como natureza jurídica. Basicamente, eles definem se você empreenderá sozinho ou se terá sócios."></i>
                                </label>
                                <select id="type-company" class="form-control" name="type-company">
                                    <option value="0">Escolha uma opção</option>
                                    <option {{ $settings['type-company'] == "MEI" ? "selected" : ''}} value="MEI">Microeemprendedor Individual (MEI)</option>
                                    <option {{ $settings['type-company'] == "EIRELI" ? "selected" : ''}} value="EIRELI">Empresa Individual de Responsabilidade Limitada (EIRELI)</option>
                                    <option {{ $settings['type-company'] == "EI" ? "selected" : ''}} value="EI">Empresa Individual (EI)</option>
                                    <option {{ $settings['type-company'] == "LTDA" ? "selected" : ''}} value="LTDA">Sociedade Empresária Limitada (LTDA)</option>
                                    <option {{ $settings['type-company'] == "S/S" ? "selected" : ''}} value="S/S">Sociedade Simples (S/S)</option>
                                    <option {{ $settings['type-company'] == "S.A" ? "selected" : ''}} value="S.A">Sociedade Anônima (S.A)</option>
                                    <option {{ $settings['type-company'] == "SLU" ? "selected" : ''}} value="SLU">Sociedade Limitada Unipessoal (SLU)</option>
                                    <option {{ $settings['type-company'] == "NULL" ? "selected" : ''}} value="NULL">Nenhuma das opções anteriores ou sou pessoa física</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="key-words-site" class="form-control-label c-pointer">
                                Palavras-chave da loja: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="As palavras-chave a serem mostradas no cabeçalho de cada página do seu site. Essas palavras devem explicar o que você vende online. Separe cada palavra com uma vírgula, como DVD de ação, DVD de comédia, etc."></i>
                            </label>
                            <input type="text" id="key-words-site" class="form-control" name="key-words-site" value="{{ $settings['key-words-site'] }}">
                        </div>
                        <div class="form-group">
                            <label for="description-site" class="form-control-label c-pointer">
                                Descrição da loja: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="A descrição a ser mostrada no cabeçalho de cada página do seu site. Use uma breve frase para descrever sua loja virtual, já que este é o texto que o Google usará para descrever o seu site em seus resultados de busca."></i>
                            </label>
                            <input type="text" id="description-site" class="form-control" name="description-site" value="{{ $settings['description-site'] }}">
                        </div>
                        <div class="form-group">
                            <label for="url-site" class="form-control-label c-pointer">
                                URL do site de loja: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="O caminho completo de sua loja virtual tal como você digitará em um browser."></i>
                            </label>
                            <input type="text" id="url-site" class="form-control" name="url-site" value="{{ $settings['url-site'] }}">
                        </div>
                        <div class="form-group d-flex">
                            <div class="flex-1 mr-3">
                                <label for="email-admin" class="form-control-label c-pointer">
                                    Email do administrator: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="O endereço de email para onde todas as notificações do sistema devem ser enviadas."></i>
                                </label>
                                <input type="email" id="email-admin" class="form-control" name="email-admin" value="{{ $settings['email-admin'] }}">
                            </div>
                            <div class="flex-1">
                                <label for="email-sender" class="form-control-label c-pointer">
                                    Email do remetente: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="O endereço de email utilizado em todos os emails enviados para  clientes, tal como 'vendas@seusite.com' ou 'pedidos@seusite.com'."></i>
                                </label>
                                <input type="email" id="email-sender" class="form-control" name="email-sender" value="{{ $settings['email-sender'] }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="check-maintenance" name="maintenance" {{ $settings['maintenance'] == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label c-pointer" for="check-maintenance">Fora do ar para manutenção?</label>
                                <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="Se marcada, a loja se tornará indisponível para visitantes e uma mensagem customizável 'Fora do ar para manutenção' será exibida."></i>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="check-allow-purchases" name="allow-purchases" {{ $settings['allow-purchases'] == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label c-pointer" for="check-allow-purchases">Permitir que as pessoas comprem de minha loja?</label>
                                <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="Você deseja habilitar as funcionalidades de carrinho de compras e finalização de pedido em sua loja? Se sim, marque esta caixa. Se você desejar apenas que sua loja funcione como um catálogo online e *não* permita aos clientes fazerem pedidos online, então desmarque esta caixa e todas as funcionalidades de carrinho de compras e finalização de pedidos serão desativadas em sua loja."></i>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="check-low-stock" name="low-stock" {{ $settings['low-stock'] == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label c-pointer" for="check-low-stock">Notificar quando o estoque estiver baixo?</label>
                                <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="Se você deseja receber mensagens de e-mail quando os produtos em sua loja atingirem o nível de estoque baixo definido para eles, marque esta caixa."></i>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-localization" role="tabpanel" aria-labelledby="custom-tabs-four-localization-tab">
                        <div class="form-group d-flex">
                            <div class="flex-1 mr-3">
                                <label for="country" class="form-control-label c-pointer">
                                    País:
                                </label>
                                <input type="text" id="country" class="form-control" name="country" value="{{ $settings['country'] }}">
                            </div>
                            <div class="flex-1">
                                <label for="cep" class="form-control-label c-pointer">
                                    CEP da loja:
                                </label>
                                <input type="text" id="cep" class="form-control" name="cep" value="{{ $settings['cep'] }}">
                            </div>
                        </div>

                        <div class="form-group d-flex">
                            <div class="flex-1 mr-3">
                                <label for="uf" class="form-control-label c-pointer">
                                    Estado e/ou UF:
                                </label>
                                <input type="text" id="uf" class="form-control" name="uf" value="{{ $settings['uf'] }}">
                            </div>
                            <div class="flex-1">
                                <label for="city" class="form-control-label c-pointer">
                                    Cidade:
                                </label>
                                <input type="text" id="city" class="form-control" name="city" value="{{ $settings['city'] }}">
                            </div>
                        </div>

                        <div class="form-group d-flex">
                            <div class="flex-1 mr-3">
                                <label for="district" class="form-control-label c-pointer">
                                    Bairro:
                                </label>
                                <input type="text" id="district" class="form-control" name="district" value="{{ $settings['district'] }}">
                            </div>
                            <div class="flex-1 mr-3">
                                <label for="road" class="form-control-label c-pointer">
                                    Avenida ou Rua:
                                </label>
                                <input type="text" id="road" class="form-control" name="road" value="{{ $settings['road'] }}">
                            </div>
                            <div class="flex-1">
                                <label for="n-shop" class="form-control-label c-pointer">
                                    nº da loja:
                                </label>
                                <input type="text" id="n-shop" class="form-control" name="n-shop" value="{{ $settings['n-shop'] }}">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-appearance" role="tabpanel" aria-labelledby="custom-tabs-four-appearance-tab">
                        <div class="form-group d-flex">
                            <div class="flex-1 mr-3">
                                <label for="bg-site" class="form-control-label c-pointer">
                                    Cor do site: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="Cor padrão que aparecerá no seu site."></i>
                                </label>
                                <input type="color" id="bg-site" class="form-control" name="bg-site" value="{{ $settings['bg-site'] }}">
                            </div>
                            <div class="flex-1">
                                <label for="bg-text" class="form-control-label c-pointer">
                                    Cor de texto: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="Cor padrão que aparecerá no texto do seu site."></i>
                                </label>
                                <input type="color" id="bg-text" class="form-control" name="bg-text" value="{{ $settings['bg-text'] }}">
                            </div>
                        </div>

                        <div class="form-group d-flex">
                            <div class="flex-1 mr-3">
                                <label for="logo-site" class="form-control-label c-pointer">
                                    Logo do site: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="Imagem que representa sua marca e aparece no topo do seu site."></i>
                                </label>
                                <img data-id="logo-site" class="img-thumbnail rounded" style="max-width: 200px" src="{{ asset('media/gallery/'.$settings['logo-site']) }}">
                                <a href="#" id="logo-site" class="btn btn-block bg-gradient-primary col-3 mt-2">Escolher logo</a>
                                <input type="hidden" data-id="logo-site" class="form-control" name="logo-site" value="{{ $settings['logo-site'] }}">
                            </div>
                            <div class="flex-1">
                                <label for="icon-site" class="form-control-label c-pointer">
                                    Ícone do site: <i class="far fa-question-circle btn btn-tooltip-info bg-gradient-info" data-toggle="tooltip" title="Imagem que aparece na aba do navegador."></i>
                                </label>
                                <img data-id="icon-site" class="img-thumbnail rounded" style="max-width: 200px" src="{{ asset('media/gallery/'.$settings['icon-site']) }}">
                                <a href="#" id="icon-site" class="btn btn-block bg-gradient-primary col-3 mt-2">Escolher ícone</a>
                                <input type="hidden" data-id="icon-site" class="form-control" name="icon-site" value="{{ $settings['icon-site'] }}">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-image" role="tabpanel" aria-labelledby="custom-tabs-four-image-tab">
                        Imagens
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-screen" role="tabpanel" aria-labelledby="custom-tabs-four-screen-tab">
                        Tela
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </form>

    <div class="modal-personal">
        <div class="card">
            <div class="card-header">
                <h3 class="float-left modal-title">Galeria</h3> <span class="modal-personal--close float-right"><i class="fas fa-times"></i></span>
            </div>
            <div id="gallery-area" class="card-body row"></div>
        </div>
    </div>
@stop
