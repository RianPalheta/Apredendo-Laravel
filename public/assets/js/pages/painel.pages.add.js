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
    $('#user-add').submit(function(e){
        e.preventDefault();
        let form = document.getElementById('user-add');
        let formData = new FormData(form);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url_add,
            data: formData,
            processData: false,
		    contentType: false,
            success: function (response) {
                r = JSON.parse(response);
                if(r.success === true) {
                    toastr["success"]("Usuário cadastrado com sucesso!", "Sucesso")
                    setTimeout(() => {
                        window.location.href = url_users;
                    }, 1000 );
                } else {
                    let msg = new Array(r.message);
                    msg.forEach((item, key) => {
                        $(`input`).removeClass('is-invalid');
                        $(`input[name=${Object.keys(item)[key]}]`).addClass('is-invalid');
                        toastr["error"](Object.values(item)[key], "Error");
                    });
                }
                return;
            }
        });
    })
    $("input[type=file]").on("change", function(){
        let img = document.querySelector("#avatar_new_user").files[0];
        let src = URL.createObjectURL(img);
        $('.new_avatar_label').html(img.name);
        $('.new_avatar_view').html(`<img src="${src}" class="img-fluid" style="max-height:350px" />`);
    });
})

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
