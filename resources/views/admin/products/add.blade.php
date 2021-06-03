@extends('adminlte::page')

@section('title', 'Adicionar Produto')

@section('content_header')
    <h1>Adicionar produto</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/tempusdominus.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
@endsection

@section('js')
    <script>
        const url = "{{ route('products.list') }}";
        const url_add = "{{ route('products.add') }}";
        const get_brands = "{{ route('getBrands') }}";
        const get_options = "{{ route('getOptions') }}";
        const get_category = "{{ route('getCategories') }}";
        const images_upload_url = "{{ route('imageupload') }}";
    </script>
    <script src="https://cdn.tiny.cloud/1/b6hnb08gw7mg8f83eozwq9gxez89dh47vamxf49vhzugj3s0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/moment-pt-br.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/tempusdominus.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/imask.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/products/painel.products.add.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs justify-content-center" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-details-tab" data-toggle="pill" href="#custom-tabs-four-details" role="tab" aria-controls="custom-tabs-four-details" aria-selected="true">
                      Detalhes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-images-tab" data-toggle="pill" href="#custom-tabs-four-images" role="tab" aria-controls="custom-tabs-four-images" aria-selected="false">
                        Imagens / Vídeo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-delivery-tab" data-toggle="pill" href="#custom-tabs-four-delivery" role="tab" aria-controls="custom-tabs-four-delivery" aria-selected="false">
                        Entrega
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-variations-tab" data-toggle="pill" href="#custom-tabs-four-variations" role="tab" aria-controls="custom-tabs-four-variations" aria-selected="false">
                        Variações
                    </a>
                </li>
            </ul>
        </div>
        <form id="add-product">
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-four-details" role="tabpanel" aria-labelledby="custom-tabs-four-details-tab">
                        <div class="form-group">
                            <label><span class="required">*</span> Tipo de produto: </label>
                            <div class="icheck-primary">
                                <input id="physical-product" class="form-check-input" type="radio" value="0" name="product-type" checked>
                                <label for="physical-product" class="form-check-label">Produto físico</label>
                            </div>
                            <div class="icheck-primary">
                                <input id="digital-product" class="form-check-input" type="radio" value="1" name="product-type">
                                <label for="digital-product" class="form-check-label">Produto digital</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="flex-1 mr-3">
                                <label for="name"><span class="required">*</span> Nome do produto: </label>
                                <input type="name" class="form-control" id="name">
                            </div>
                            <div class="col-3">
                                <label for="stock"><span class="required">*</span> Estoque Disponível: </label>
                                <input type="text" class="form-control" id="stock">
                            </div>
                            <div class="col-3">
                                <label for="code">Código do produto: </label>
                                <input type="text" class="form-control" id="code">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="flex-1 mr-3">
                                <label for="category"><span class="required">*</span> Selecione um categoria:</label>
                                <select id="category" class="form-control"></select>
                            </div>
                            <div class="flex-1">
                                <label for="brand"><span class="required">*</span> Selecione uma marca:</label>
                                <select id="brand" class="form-control"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descrição do produto:</label>
                            <textarea class="form-control body-field"></textarea>
                        </div>

                        <div class="form-group">
                            <label><span class="required">*</span> Disponibilidade: </label>
                            <div class="icheck-primary">
                                <input id="can-be-purchased" class="form-check-input" type="radio" value="0" name="availability" checked>
                                <label for="can-be-purchased" class="form-check-label">Este produto pode ser comprado em minha loja virtual.</label>
                            </div>
                            <div class="icheck-primary">
                                <input id="pre-sales" class="form-check-input" type="radio" value="1" name="availability">
                                <label for="pre-sales" class="form-check-label">Este produto está chegando em breve mas desejo disponibilizar em pré-venda.</label>
                                <div class="pre-sales--data mt-3 d-none">
                                    <div class="form-group row">
                                        <label for="pre-sales--msg" class="col-sm-2 col-form-label">Mensagem:</label>
                                        <div class="col-sm-10">
                                        <input type="text" class="form-control" id="pre-sales--msg" name="pre-sales--msg" value="Data de lançamento e em">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pre-sales--release-date-of" class="col-sm-2 col-form-label">Data de lançamento:</label>
                                        <div class="col-sm-5 input-group date" id="pre-sales--release-date-of-datepicker" data-target-input="nearest">
                                            <input type="text" class="form-control" id="pre-sales--release-date-of" data-target="#pre-sales--release-date-of-datepicker" name="pre-sales--release-date-of" placeholder="__/__/____">
                                            <div class="input-group-append" data-target="#pre-sales--release-date-of-datepicker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="icheck-greensea">
                                                <input type="checkbox" id="pre-sales--release-date-of--r-pre-orderstatus" name="r-pre-orderstatus" value="1">
                                                <label for="pre-sales--release-date-of--r-pre-orderstatus">Remover o status de pré-venda nesta data?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="flex-1 mr-3">
                                <label for="price"><span class="required">*</span> Preço do produto: <small>(Obs.: Excluindo impostos.)</small></label>
                                <input type="text" class="form-control" id="price">
                                <a data-toggle="collapse" href="#price-options" role="button" aria-expanded="false" aria-controls="price-options" class="btn btn-block btn-default btn-xs">Mais opções de preço</a>

                                <div class="collapse" id="price-options">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="cost-price">Preço de custo: </label>
                                            <input type="text" class="form-control" name="cost-price" id="cost-price">
                                        </div>
                                        <div class="form-group">
                                            <label for="retail-price">Preço de varejo: </label>
                                            <input type="text" class="form-control" name="retail-price" id="retail-price">
                                        </div>
                                        <div class="form-group">
                                            <label for="promotion-price">Preço promocional: </label>
                                            <input type="text" class="form-control" name="promotion-price" id="promotion-price">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label><span class="required">*</span> Classe tributária:</label>
                                <select class="form-control">
                                    <option value="">Teste</option>
                                    <option value="">Teste</option>
                                    <option value="">Teste</option>
                                    <option value="">Teste</option>
                                    <option value="">Teste</option>
                                    <option value="">Teste</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-images" role="tabpanel" aria-labelledby="custom-tabs-four-images-tab">
                       Imagens
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-delivery" role="tabpanel" aria-labelledby="custom-tabs-four-delivery-tab">
                       <div class="form-group row">
                            <div class="flex-1 mr-3">
                                <label for="product-weight"><span class="required">*</span> Peso do produto: <small>(Obs.: Em gramas "g")</small></label>
                                <input type="text" class="form-control" name="product-weight" id="product-weight">
                            </div>
                            <div class="flex-1 mr-3">
                                <label for="product-width"><span class="required">*</span> Largura do produto: <small>(Obs.: Em centímetros "cm")</small></label>
                                <input type="text" class="form-control" name="product-width" id="product-width">
                            </div>
                       </div>
                       <div class="form-group row">
                            <div class="flex-1 mr-3">
                                <label for="product-diameter"><span class="required">*</span> Diâmetro do produto: <small>(Obs.: Em centímetros "cm")</small></label>
                                <input type="text" class="form-control" name="product-diameter" id="product-diameter">
                            </div>
                            <div class="flex-1 mr-3">
                                <label for="product-height"><span class="required">*</span> Altura do produto: <small>(Obs.: Em centímetros "cm")</small></label>
                                <input type="text" class="form-control" name="product-height" id="product-height">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="free-delivery">Entrega grátis?</label>
                            <div class="icheck-greensea">
                              <input type="checkbox" id="free-delivery">
                              <label for="free-delivery">Sim, este produto tem entrega grátis.</label>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-variations" role="tabpanel" aria-labelledby="custom-tabs-four-variations-tab">
                        <div class="form-group">
                            <label>Este produto:</label>
                            <div class="icheck-primary">
                                <input id="not-variation" class="form-check-input" type="radio" value="0" name="option" checked>
                                <label for="not-variation" class="form-check-label">Não terá nenhum variação em minha loja.</label>
                            </div>
                            <div class="icheck-primary">
                                <input id="has-variation" class="form-check-input" type="radio" value="1" name="option">
                                <label for="has-variation" class="form-check-label">Utilizará uma variação de produto que já criei.</label>
                            </div>
                        </div>
                        <div class="form-group select-options row"></div>
                     </div>
                  </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right" id="submit-add">
                    Cadastrar
                </button>
            </div>
        </form>
    </div>
@stop
