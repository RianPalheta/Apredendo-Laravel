$(document).ready(e => {
    $(function () {
        $('[data-toggle="tooltip"]').popup({
            inline     : true,
            hoverable  : true,
            position   : 'right center',
            delay: {
                hide: 300
            }
        });
    })

    $('.card-body').scroll(_.debounce(loading_demand, 200));
}).keyup(e => {
    if(e.key == 'Enter') {
        $('#setting').submit();
    }
});
IMask(document.querySelector('input[name=cep]'), {mask: '00.000-000'});

let abort = false;
let isForm = true;
$(function() {
    $('#setting').submit(e => {
        e.preventDefault();
        if(!isForm) return;
        isForm = false;
        let form = document.querySelector('#setting');
        let formData = new FormData(form);
        let btnText = $('button[type=submit]').text();
        let request = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url,
            data: formData,
            processData: false,
		    contentType: false,
            beforeSend: function() {
                $('button[type=submit]').addClass('disabled').html("<i class='fas fa-circle-notch rotafe-infinit'></i>");
                $(document).keyup(e => {
                    if(e.key == 'Escape') {
                        abort = true;
                        request.abort();
                    }
                });
            },
            success: function (r) {
                setTimeout(() => isForm = true, 1000);
                $('button[type=submit]').removeClass('disabled').html(btnText);
                if(r.success === true) {
                    toastr["success"]("Configurações salvas com sucesso!", "Sucesso")
                } else {
                    let msg = new Array(r.message);
                    msg.forEach((item, key) => {
                        $(`input`).removeClass('is-invalid');
                        $(`input[name=${Object.keys(item)[key]}]`).addClass('is-invalid');
                        toastr["error"](Object.values(item)[key], "Error");
                    });
                }
                return;
            },
            error: function(error) {
                setTimeout(() => {
                    abort = false
                    isForm = true
                }, 1000);
                $('button[type=submit]').removeClass('disabled').html(btnText);
                if(abort)
                    toastr["warning"]("Requisição cancelada.", "Aviso");
                else
                    toastr["error"]("Ops! Algo deu errado (Http 500).", "Error");
            }
        });
    });
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

let openModal = false;
let load_photo = false;

let isElement;
let last_page;
let total_page;
let current_page;

let page = 1;
let qt_result_pg = 20;
$('a[href="#"]').on('click', e => {
    e.preventDefault();
    if(e.target.id === 'logo-site' || e.target.id === 'icon-site') {
        $('.modal-personal').addClass('modal-personal--active');
        if(!openModal) list_photos(page, qt_result_pg);
        isElement = e.target.id;
        openModal = true;
    };
});
$('.modal-personal').on('click', e => {
    if(e.target.className.split(' ')[0] == 'modal-personal')
        $('.modal-personal').removeClass('modal-personal--active'); return;
})
$('.modal-personal--close').on('click', e => {
    $('.modal-personal').removeClass('modal-personal--active'); return;
});
$(document).keyup( e => {
    if(e.key === 'Escape')
        $('.modal-personal').removeClass('modal-personal--active'); return;
});

function list_photos(page, qt, search = null) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: url_gallery,
        type:'GET',
        data: {page, qt, search},
        dataType:'JSON',
        beforeSend: function() {
            if(current_page == undefined || load_photo == false) {
                $('#gallery-area').append("<div id='content-load' class='position-absolute card d-flex justify-content-center align-items-center' style='z-index: 3; width:100%; height:100%; top:0; left:0;'><i class='fas fa-circle-notch rotafe-infinit' style='font-size:2.5rem'></i></div>");
            } else {
                $('#gallery-area').append("<div id='content-load' class='card d-flex justify-content-center align-items-center' style='z-index: 3; width:100%; height:100%;'><i class='p-2 fas fa-circle-notch rotafe-infinit' style='font-size:2.5rem'></i></div>");
            }
        },
        success:function(response) {
            page = current_page;
            total_page = response.total;
            last_page = response.last_page;
            current_page = response.current_page;
            let data = Object.values(response.data);
            setInterval(() => load_photo = false, 1000);
            if($('#gallery-area .alert')) $('#gallery-area .alert').remove();
            $('#content-load').remove();

            if(parseInt(response.total) > 0) {
                data.map(item => {
                    let photo = `
                    <div class="img-area col-sm-2" data-toggle="modal" data-target="#${item.id}">
                        <img class="img-fluid lozad" data-src="${asset}/${item.hash}" alt="${item.name}">
                        <span id="span-item-${item.id}" class="img-name">${item.name}</span>
                    </div>
                    `;

                    $('#gallery-area').append(photo);
                });
            } else {
                $('#gallery-area').html("<div class='alert alert-light' role='alert'>Não há fotos para mostrar.</div>");
                return;
            }

            // Carregamento preguiçoso da imagens
            lozad().observe();

            $('.lozad').on('load', function() {
                $(this).addClass('lozad-ready');
            });

            $('.img-area').on('click', e => {
                $(`input[data-id="${isElement}"]`).val(e.target.getAttribute('data-src').split('/')[5]);
                console.log()
                $(`img[data-id="${isElement}"]`).attr('src', asset+'/'+e.target.getAttribute('data-src').split('/')[5]);
                $('.modal-personal').removeClass('modal-personal--active'); return;
            });
        }
    })
}
function loading_demand() {
    if(load_photo != false || page >= last_page) return;
    if($(this).scrollTop() + $(this).height() >= $('#gallery-area').get(0).scrollHeight - 85) {
        load_photo = true;
        list_photos(page += 1, qt_result_pg);
    }
}

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
