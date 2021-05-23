var load_photo = false;

var current_page;
var last_page;

var page = 1;
var qt_result_pg = 20;

$(document).ready(function() {
    list_photos(page, qt_result_pg);

    window.addEventListener('scroll', _.debounce(loading_demand, 200));
});

$('select').on('change', function() {
    let option = $('select').val();
    localStorage.setItem('qt_result_pg_list_photos', option);
    list_photos(1, option);
});

var typingTimer; //timer identifier
var doneTypingInterval = 500; //time in ms, 1 second for example

$('#search').on('submit', function(e) {
    e.preventDefault();
});

//on keyup, start the countdown
$('#search').keyup(function() {
  clearTimeout(typingTimer);
  if ($('#search').val) {
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
  }
});

//user is "finished typing," do something
function doneTyping() {
    let serach = $('#search input').val();
    list_photos(1, localStorage.getItem('qt_result_pg_list_photos'), serach);
}


function list_photos(page, qt, search = null) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url,
        type:'GET',
        data: {page, qt, search},
        dataType:'JSON',
        beforeSend: function() {
            if(current_page == undefined) {
                $('.content-load').append("<div id='content-load' class='position-absolute card d-flex justify-content-center align-items-center' style='z-index: 3; width:100%; height:100%; top:0; left:0;'><i class='fas fa-circle-notch rotafe-infinit' style='font-size:2.5rem'></i></div>");
            } else {
                $('.card-body').append("<div id='content-load' class='card d-flex justify-content-center align-items-center' style='z-index: 3; width:100%; height:100%;'><i class='p-2 fas fa-circle-notch rotafe-infinit' style='font-size:2.5rem'></i></div>");
            }
        },
        success:function(response) {
            last_page = response.last_page;
            current_page = response.current_page
            let data = Object.values(response.data);
            page = current_page;
            setInterval(() => {
                load_photo = false;
            }, 1000);

            if($('#gallery-area .alert')) {
                $('#gallery-area .alert').remove();
            }

            $('#content-load').remove();
            $('.card-header ul').html('');

            if(parseInt(response.total) > 0) {
                data.map(item => {
                    let photo = `
                    <div data-${item.id} class="img-area col-sm-2" data-toggle="modal" data-target="#${item.id}">
                        <img class="img-fluid" src="${asset}/${item.hash}" alt="${item.name}">
                        <span class="img-name">${item.name}</span>
                    </div>

                    <form>
                        <div class="modal fade" id="${item.id}" tabindex="-1" role="dialog" aria-labelledby="img-${item.id}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="img-${item.id}">Foto</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body row">
                                    <div class="col">
                                        <img id="${item.name}" class="img-fluid" src="${asset}/${item.hash}" alt="${item.name}">
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Nome</label>
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control" id="name" value="${item.name}" placeholder="Digite um nome para foto">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Descrição</label>
                                            <div class="input-group">
                                                <textarea name="description" id="description" cols="10" rows="3" class="form-control">${item.description !== null ? item.description : ''}</textarea>
                                            </div>
                                        </div>
                                            Dimensões: ${item.dimension} - Tamanho:
                                            ${item.size.length >= 7
                                                ? (item.size / 1000000+'MB').replace('.', ',')
                                                : (item.size / 1000+'KB').replace('.', ',')}
                                    </div>
                                </div>
                                <div class="modal-footer" style="justify-content: space-between;">
                                    <button onclick="delete_item(${item.id})" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Deletar</button>
                                    <a href="${asset}/${item.hash}" class="btn btn-success" download="${item.name}">Donwload</a>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    `;

                    if(search == null || search == '') {
                        $('#gallery-area').append(photo);
                    } else {
                        $('#gallery-area').html('');
                        $('#gallery-area').append(photo);
                    }
                });
            } else {
                $('#gallery-area').html("<div class='alert alert-light' role='alert'>Não há fotos para mostrar.</div>");
                return;
            }

            /*const btn_del = [...$('button[data-delete]')];
            btn_del.forEach(btn => {
                let id = btn.getAttribute('data-delete');
                btn.onclick = delete_user(id);
            });*/

            const gallery_form = document.getElementById('gallery-form');
            let gallery_img = [...document.querySelectorAll('.img-area')];
            gallery_img.forEach(img => {
                img.addEventListener('click', () => {
                    gallery_img.forEach(i => {
                        i.classList.remove('img-select');
                    })
                    let id = img.getAttribute('data-id');
                    img.classList.toggle('img-select');
                });
            });
        }
    })
}

function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return true;
    }

    return false;
}

function delete_item(id) {
    let url_del_page = url_del.replace('1', id);
    let c = confirm('Tem certeza que você quer excluir essa foto?');
    if(c) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "DELETE",
            url: url_del_page,
            success: function (response) {
                let r = JSON.parse(response);
                if(r.success === false) {
                    toastr["error"](r.message, "Error");
                    return;
                }
                $(`div[data-${id}]`).remove();
            }
        });
    }
}

function loading_demand() {
    if(load_photo != false || page >= last_page) return;
    if($(this).scrollTop() + $(this).height() >= $('#gallery-area').get(0).scrollHeight) {
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
