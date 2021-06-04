<div class="flex-1 mr-3">
    <label for="select-options">Selecione as opções do produto:</label>
    <select id="select-options" class="form-control">
        @foreach ($options['data'] as $option)
            <option data-optionId="{{ $option['id'] }}" data-optionSlug="{{ $option['slug'] }}" value="{{ $option['id'] }}">
                {{ $option['name'] }}
            </option>
        @endforeach
    </select>
</div>
<div class="d-flex align-items-end flex-1 icheck-greensea">
    <input type="checkbox" id="mandatory-option" name="mandatory-option" value="1">
    <label for="mandatory-option">Forçar o usuário a selecionar valores de opção?</label>
</div>
