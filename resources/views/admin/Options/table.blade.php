<div class="card-body">
    <input type="hidden" name="total" value="{{ $options['total'] }}">
    <input type="hidden" name="last_page" value="{{ $options['last_page'] }}">
    <input type="hidden" name="current_page" value="{{ $options['current_page'] }}">

    <table class="table table-hover">
        <thead>
            <tr>
                <th>
                    <div class="icheck-primary">
                        <input type="checkbox" id="select-all">
                        <label for="select-all"></label>
                    </div>
                </th>
                <th width="100%">Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($options['data'] as $option)
                <tr>
                    <td>
                        <div class="icheck-primary">
                            <input type="checkbox" id="{{ $option['id'] }}" class="option-check">
                            <label for="{{ $option['id'] }}"></label>
                        </div>
                    </td>
                    <td>{{ $option['name'] }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a title="Editar" class="btn btn-sm btn-info" href="">
                                <i class="fas fa-pen-alt"></i>
                            </a>
                            <button onclick="delete_option([{{ $option['id'] }}])" title="Deletar" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
