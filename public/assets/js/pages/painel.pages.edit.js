const telephone = document.querySelector('input[name=telephone]');
const telephone_mask = {
    mask: '(000) 00000-0000'
}
const cpf = document.querySelector('input[name=cpf]');
const cpf_mask = {
    mask: '000.000.000-00'
}
const birthday = document.querySelector('input[name=birthday]');
const birthday_mask = {
    mask: '00/00/0000'
}
const cep = document.querySelector('input[name=cep]');
const cep_mask = {
    mask: '00.000-000'
}

IMask(cpf, cpf_mask);
IMask(cep, cep_mask);
IMask(birthday, birthday_mask);
IMask(telephone, telephone_mask);

const pass = $('.pass');
pass.on('click', function() {
    let input = $(this).parent().find('input');
    let span = $(this).find('.input-group-text');

    if(input.attr('type') === 'password') {
        input.attr('type', 'text');
        span.html('<i class="far fa-eye-slash"></i>');
    } else {
        input.attr('type', 'password');
        span.html('<i class="far fa-eye"></i>');
    }
});

var typingTimer;
const doneTypingInterval = 500;
//on keyup, start the countdown
$('input[name=cep]').keyup(function() {
  clearTimeout(typingTimer);
  if ($('input[name=cep]').val) {
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
  }
});

const uf = $('input[name=uf]');
const city = $('input[name=city]');
const district = $('input[name=district]');
const road = $('input[name=road]');
//user is "finished typing," do something
function doneTyping() {
    let cep = $('input[name=cep]').val();
    if(cep.length <= 9) return null;
    cep = cep.replace('.', '');
    cep = cep.replace('-', '');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url_cep,
        data: {cep},
        dataType: "JSON",
        beforeSend: function() {
            uf.attr('value', 'Carregando...');
            city.attr('value', 'Carregando...');
            district.attr('value', 'Carregando...');
            road.attr('value', 'Carregando...');
        },
        success: function (response) {
            uf.attr('value', response.uf);
            city.attr('value', response.cidade);
            district.attr('value', response.bairro);
            road.attr('value', response.logradouro);
        }
    });
}

$(function() {
    $('#user-edit').submit(function(e){
        e.preventDefault();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url: url_update,
            data: $(this).serialize(),
            success: function (response) {
                r = JSON.parse(response);
                if(r.success === true) {
                    toastr["success"]("Usuário editado com sucesso!", "Sucesso")
                } else {
                    let msg = new Array(r.message);
                    msg.map((item, key) => {
                        $(`input`).removeClass('is-invalid');
                        $(`input[name=${Object.keys(item)[key]}]`).addClass('is-invalid');
                        toastr["error"](Object.values(item)[key], "Error");
                    });
                }
            }
        });
    })
})

Dropzone.autoDiscover = false;
$('#photo_user').dropzone({
    paramName: 'avatar',
    url: url_update,
    maxFilesize: 1,
    maxFiles: 1,
    uploadMultiple: false,
    maxFilesize: 5,
    addRemoveLinks: true,
    acceptedFiles: '.jpeg,.jpg,.png,.gif,.icon,.webp',

    dictFallbackMessage: 'Seu navegador não é compatível com essa funcionalidade.',
    dictFileTooBig: 'Essa imagem ultrapassou o limite de 5Mb.',
    dictInvalidFileType: 'Esse arquivo não é suportado.',
    dictResponseError: 'Ops! Algo deu errado no servidor.',

    init: function() {
        this.on('success', function(file, res) {
            let r = JSON.parse(res);
            if(r.success != true) {
                let r_array = new Array(r.message);
                r_array.map((item, key) => {
                    let msg = Object.values(item);
                    toastr["error"](msg, "Error");
                })
            } else {
                toastr["success"]("Avatar trocado!", "Sucesso");
                $('.profile-user-img').attr('src', `${assets}/${r.avatar}`);
            }
        });
        this.on('error', function(file, errorMessage) {
            toastr["error"](errorMessage, "Error")
        });
        this.on('complete', function(file) {
            this.removeFile(file);
        });
    }
});


toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
