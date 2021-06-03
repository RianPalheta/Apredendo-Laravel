var qt_result_pg = 50;
var page = 1;

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

$(document).ready(function() {
    if(localStorage.getItem('current_page_list_options')) {
        page = localStorage.getItem('current_page_list_options');
    }
    if(localStorage.getItem('qt_result_pg_list_options')) {
        qt_result_pg = localStorage.getItem('qt_result_pg_list_options');
    }

    if($('table').length > 0) {
        [...$('select').children()].forEach(item => {
            if(item.getAttribute('value') === qt_result_pg) {
                item.setAttribute('selected','selected')
            }
        });
        list_options(page, qt_result_pg);
    }
});

$('select').on('change', function() {
    let option = $('select').val();
    localStorage.setItem('qt_result_pg_list_options', option);
    list_options(1, option);
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
    list_options(1, localStorage.getItem('qt_result_pg_list_options'), serach);
}

function list_options(page, qt, search = null) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url,
        type:'GET',
        data: {page, qt, search},
        dataType:'JSON',
        beforeSend: function() {
            $('.content-load').append("<div id='content-load' class='position-absolute card d-flex justify-content-center align-items-center' style='z-index: 3; width:100%; height:100%; top:0; left:0;'><i class='fas fa-circle-notch rotafe-infinit' style='font-size:2.5rem'></i></div>");
        },
        success:function(response) {
            let last_page = response.last_page;
            let current_page = response.current_page
            let data = response.data;
            localStorage.setItem('current_page_list_options', current_page);

            $('tbody').html('');
            $('#content-load').remove();
            $('.card-header ul').html('');

            if(parseInt(response.total) > 0) {
                data.map(i => {
                    let url_edit_page = url_edit.replace('1', i.id);
                    let table = `
                    <tr>
                        <td>${i.name}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button title="Editar" class="btn btn-sm btn-info" data-toggle="modal" data-target="#${i.id}"><i class="fas fa-pen-alt"></i></button>
                                <button onclick="delete_option(${i.id})" title="Deletar" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    `;
                    let modal = `
                    <div class="modal fade" id="${i.id}" tabindex="-1" role="dialog" aria-labelledby="${i.id}-Label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="${i.id}-Label">Editar Opção</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name-${i.id}">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Nome</font>
                                        </font>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="name" class="form-control" id="name-${i.id}" value="${i.name}" placeholder="Digite o nome da opção">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                <button onclick="edit_option(${i.id})" type="button" id="#btn-${i.id}" class="btn btn-primary">Salvar</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    `;
                    $('.card-body').append(modal);
                    $('tbody').append(table);
                    return;
                });
            } else {
                $('table').html("<div class='alert alert-light' role='alert'>Não há marcas para mostrar.</div>");
                return;
            }

            const max_links = 2;
            if(last_page > 1) {
                $('.card-header ul').append(`
                <li class="page-item ${current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">«</font></font></a></li>
                `)
                for(let page_ant = current_page - max_links; page_ant <= current_page -1; page_ant++) {
                    if(page_ant >= 1) {
                        $('.card-header ul').append(`
                            <li class="page-item"><a class="page-link" href="${page_ant}"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">${page_ant}</font></font></a></li>
                        `)
                    }
                }
                $('.card-header ul').append(`
                    <li class="page-item active"><a class="page-link" href="${current_page}"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">${current_page}</font></font></a></li>
                `)
                for(let page_dep = current_page + 1; page_dep <= current_page + max_links; page_dep++) {
                    if(page_dep <= last_page) {
                        $('.card-header ul').append(`
                            <li class="page-item"><a class="page-link" href="${page_dep}"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">${page_dep}</font></font></a></li>
                        `)
                    }
                }
                $('.card-header ul').append(`
                    <li class="page-item ${current_page === last_page ? 'disabled' : ''}"><a class="page-link" href="${last_page}"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">»</font></font></a></li>
                `)
            }

            $('#info-pages').html(`Mostrando ${current_page} de ${last_page} páginas`);
            $('h1 span').addClass('badge bg-secondary');
            $('h1 span').html(`${response.total}`);

            const page_link = [...$('.page-link')];
            page_link.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    let attr = item.getAttribute('href');
                    if(parseInt(current_page) != parseInt(attr)) {
                        list_options(attr, localStorage.getItem('qt_result_pg_list_options'));
                    }
                })
            });

            const btn_del = [...$('button[data-delete]')];
            btn_del.forEach(btn => {
                let id = btn.getAttribute('data-delete');
                btn.onclick = delete_option(id);
            });
        }
    })
}

$(function() {
    $('#form-add-option').submit(function(e){
        e.preventDefault();
        let form = document.getElementById('form-add-option');
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
            success: function (r) {
                if(r.success === true) {
                    list_options(page, qt_result_pg);
                    toastr["success"]("Opção cadastrada com sucesso!", "Sucesso");
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
})

function edit_option(id) {
    let name = $(`#name-${id}`).val();
    let url = url_edit.replace('1', id);
    let btn_text = $(`#btn-${id}`).text();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "PUT",
        url,
        data: {name},
        beforeSend: function() {
            $(`#btn-${id}`).html("<i class='fas fa-circle-notch rotafe-infinit'></i>");
        },
        success: function (r) {
            $(`#btn-${id}`).text(btn_text);
            if(r.success === true) {
                list_options(page, qt_result_pg);
                toastr["success"]("Opção editada com sucesso!", "Sucesso");
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
}

function delete_option(id) {
    let url_del_page = url_del.replace('1', id);
    let c = confirm('Tem certeza que você quer excluir esse usuário?');
    if(c) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "DELETE",
            url: url_del_page,
            success: function (r) {
                if(r.success === false) {
                    return toastr["error"](r.message, "Error");
                }
                list_options(
                    localStorage.getItem('current_page_list_options'),
                    localStorage.getItem('qt_result_pg_list_options')
                );
            }
        });
    }
}
