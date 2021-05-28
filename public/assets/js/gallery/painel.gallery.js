var load_photo = false;

var last_page;
var total_page;
var current_page;

var page = 1;
var qt_result_pg = 50;

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
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYyMjA0MDgxNCwiZXhwIjoxNjIyMDQ0NDE0LCJuYmYiOjE2MjIwNDA4MTQsImp0aSI6IlQ0dTdvbklkVVd5SHhjcnciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.n7RhO3yRQkhpsx7qb4YHMgBi_0WL6jvxMW9kALH4iwg'
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
            total_page = response.total;
            last_page = response.last_page;
            current_page = response.current_page;
            let data = Object.values(response.data);
            page = current_page;
            setInterval(() => {
                load_photo = false;
            }, 1000);

            if($('#gallery-area .alert')) {
                $('#gallery-area .alert').remove();
            }

            $('#content-load').remove();

            if(parseInt(response.total) > 0) {
                data.map(item => {
                    let photo = `
                    <div data-${item.id} class="img-area col-sm-2" data-toggle="modal" data-target="#${item.id}">
                        <img class="img-fluid lozad" data-src="${asset}/${item.hash}" alt="${item.name}">
                        <span id="span-item-${item.id}" class="img-name">${item.name}</span>
                    </div>

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
                                    <div class="col d-flex justify-content-center align-items-center" style="background-color:#ccc">
                                        <img id="${item.name}" class="img-fluid lozad" data-src="${asset}/${item.hash}" alt="${item.name}">
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name-${item.id}">Nome</label>
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control" id="name-${item.id}" value="${item.name}" placeholder="Digite um nome para foto">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description-${item.id}">Descrição</label>
                                            <div class="input-group">
                                                <textarea name="description" id="description-${item.id}" cols="10" rows="3" class="form-control">${item.description !== null ? item.description : ''}</textarea>
                                            </div>
                                        </div>
                                            Dimensões: ${item.dimension} - Tamanho:
                                            ${item.size.length >= 7
                                                ? (((item.size / 1000000).toFixed(2)+'MB')).replace('.', ',')
                                                : ((item.size / 1000).toFixed(2)+'KB').replace('.', ',')}
                                    </div>
                                </div>
                                <div class="modal-footer" style="justify-content: space-between;">
                                    <button onclick="delete_item(${item.id})" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Deletar</button>
                                    <input id="btn-copy-${item.id}" class="hidden" type="text" value="${asset}/${item.hash}">
                                    <button class="btn btn-info btn-copy" data-id="${item.id}">Copiar Link</button>
                                    <a href="${asset}/${item.hash}" class="btn btn-success" download="${item.name}">Donwload</a>
                                    <button id="btn-${item.id}" onclick="edit_item(${item.id})" class="btn btn-primary">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>
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

            // Carregamento preguiçoso da imagens
            lozad().observe();

            $('.lozad').on('load', function() {
                $(this).addClass('lozad-ready');
            });

            $('h1 span').addClass('badge bg-secondary').html(`${response.total}`);

            $('.btn-copy').click(function(e) {
                let id = $(this).attr('data-id');
                let t = $(this).text();
                $(`#btn-copy-${id}`).select();
                document.execCommand('copy');

                $(this).text('Copiado!');
                setTimeout(() => {
                    $(this).text(t);
                }, 1000);
                return;
            });

            /* let gallery_img = [...document.querySelectorAll('.img-area')];
            gallery_img.forEach(img => {
                img.addEventListener('click', () => {
                    gallery_img.forEach(i => {
                        i.classList.remove('img-select');
                    })
                    let id = img.getAttribute('data-id');
                    img.classList.toggle('img-select');
                });
            });*/
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

function edit_item(id) {
    let url = url_edit.replace('1', id);

    let btn_text = $(`#btn-${id}`).text();

    let name = $(`#name-${id}`).val();
    let description = $(`#description-${id}`).val();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url,
        type:'POST',
        data: {name, description},
        dataType:'JSON',
        beforeSend: function() {
            $(`#btn-${id}`).html("<i class='fas fa-circle-notch rotafe-infinit'></i>");
        },
        success:function(response) {
            $(`#btn-${id}`).text(btn_text);
            if(response.success === true) {
                $(`#span-item-${id}`).text(name);
                toastr["success"]("Foto editada com sucesso!", "Sucesso");
            } else {
                let data = new Array(response.message);
                data.forEach(m => {
                    toastr["error"](Object.values(m), "Error");
                });
            }
        }
    })
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
                let n_total = parseInt($('h1 span').text());
                if(r.success === false) {
                    toastr["error"](r.message, "Error");
                    return;
                }
                $('h1 span').text(n_total - 1);
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

document.querySelectorAll('.drop-zone__input').forEach(inputElement => {
    const dropZoneElemnt = inputElement.closest('.drop-zone');

    dropZoneElemnt.addEventListener('click', e => {
        inputElement.click();
    });

    inputElement.addEventListener('change', e => {
        if(inputElement.files.length) {
            [...inputElement.files].forEach(file => {
                updateThumbnail(dropZoneElemnt, file)
            });
            add_photo();
        }
    })

    dropZoneElemnt.addEventListener('dragover', e => {
        e.preventDefault();
        dropZoneElemnt.classList.add('drop-zone--over');
    });

    ['dragleave', 'dragend'].forEach(type => {
        dropZoneElemnt.addEventListener(type, e => {
            dropZoneElemnt.classList.remove('drop-zone--over');
        });
    });

    dropZoneElemnt.addEventListener('drop', e => {
        e.preventDefault();

        if(e.dataTransfer.files.length) {
            inputElement.files = e.dataTransfer.files;

            [...e.dataTransfer.files].forEach(file => {
                updateThumbnail(dropZoneElemnt, file);
            })
            add_photo();
        }
        dropZoneElemnt.classList.remove('drop-zone--over');
    });
});

function updateThumbnail(dropZoneElemnt, file) {
    let thumbnailElemnt = dropZoneElemnt.querySelector('.drop-zone__thumb');

    if(dropZoneElemnt.querySelector('.drop-zone__prompt'))
        dropZoneElemnt.querySelector('.drop-zone__prompt').remove();

    if(!thumbnailElemnt) {
        thumbnailElemnt = document.createElement('div');
        thumbnailElemnt.classList.add('drop-zone__thumb');
        dropZoneElemnt.appendChild(thumbnailElemnt);
    } else {
        thumbnailElemnt = document.createElement('div');
        thumbnailElemnt.classList.add('drop-zone__thumb');
        dropZoneElemnt.appendChild(thumbnailElemnt);
    }

    thumbnailElemnt.dataset.label = file.name;

    if(file.type.startsWith('image/')) {
        const reader = new FileReader();

        reader.readAsDataURL(file);
        reader.onload = () => {
            thumbnailElemnt.style.backgroundImage = `url('${ reader.result }')`;
        }
    } else {
        thumbnailElemnt.style.backgroundImage = null;
    }
}

function add_photo() {
    let form = document.getElementById('photo-add');
    let formData = new FormData(form);
    let request = new XMLHttpRequest();

    request.upload.addEventListener('progress', function(e) {
        if(e.total >= 8e+6) {
            toastr["warning"]("O limite máximo de upload é 8MB", "Aviso");
            request.abort();
            return;
        }
        var percent = Math.round((e.loaded * 100) / e.total);
        $('.progress-bar')
            .width(`${percent}%`)
            .removeClass('bg-success progress-bar-striped progress-bar-animated')
            .addClass('bg-primary')
            .attr('aria-valuenow', percent)
            .text(`${percent}% Upload concluido!`);

        if(percent === 100) {
            setTimeout(() => {
                $('.progress-bar')
                    .addClass('progress-bar-striped progress-bar-animated')
                    .text(`Processando...`);
            }, 1000);
        }
    });
    request.addEventListener('load', function(e) {
        $('.progress-bar')
            .removeClass('bg-primary progress-bar-striped progress-bar-animated')
            .addClass('bg-success')
            .text('Completo!');

        setTimeout(() => {
            $('.drop-zone__thumb').remove();
            $('.drop-zone')
                .append('<span class="drop-zone__prompt">Solte a imagem aqui ou clique para fazer o upload.</span>');
            $('.progress-bar')
                .width(`0%`)
                .removeClass('bg-success progress-bar-striped progress-bar-animated')
                .addClass('bg-primary')
                .attr('aria-valuenow', 0)
                .text('');
            $('#photo-add').trigger('reset');
        }, 1000);

        if(request.status === 200) {
            let r = JSON.parse(request.response);
            if(r.success === true) {
                $('#gallery-area').html('');
                toastr["success"]("Foto salva com sucesso!", "Sucesso");
                list_photos(page, qt_result_pg);
            } else {
                let msg = new Array(r.message);
                msg.forEach((item, key) => {
                    $(`input`).removeClass('is-invalid');
                    $(`input[name=${Object.keys(item)[key]}]`).addClass('is-invalid');
                    toastr["error"](Object.values(item)[key], "Error");
                });
            }
        } else {
            $('.progress-bar')
                .removeClass('bg-primary')
                .addClass('bg-danger')
                .text('Algo deu errado!');
            toastr["error"]("Ops! Algo deu errado.", "Error");
        }
    });
    request.addEventListener('error', function(e) {
        $('.progress-bar')
            .removeClass('bg-primary')
            .addClass('bg-danger')
            .text('Algo deu errado!');

        toastr["error"]("Ops! Algo deu errado.", "Error");
    });

    request.open('POST', url_add);
    request.send(formData);
}
