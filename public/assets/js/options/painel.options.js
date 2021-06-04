let page = 1;
let qt_result_pg = 50;

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

    if($('.card').length > 0) {
        [...$('select').children()].forEach(item => {
            if(item.getAttribute('value') === qt_result_pg) {
                item.setAttribute('selected','selected')
            }
        });
        list_options(page, qt_result_pg);
    }

    $('select').on('change', function() {
        let option = $('select').val();
        localStorage.setItem('qt_result_pg_list_options', option);
        list_options(1, option);
    });

    let typingTimer; //timer identifier
    let doneTypingInterval = 500; //time in ms, 1 second for example
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
        data: {page, qt, search, view:'table'},
        beforeSend: function() {
            $('.card').append("<div id='content-load' class='position-absolute card d-flex justify-content-center align-items-center' style='z-index:3; width:100%; height:100%; top:0; left:0;'><i class='fas fa-circle-notch rotafe-infinit' style='font-size:2.5rem'></i></div>");
        },
        success: function(response) {
            $('.alert').remove();
            $('.card-body').remove();
            $('#content-load').remove();
            $('.card-header ul').remove();

            $('.card').append(response);

            let checkDell = [];
            let total = parseInt($('input[name="total"]').val());
            let last_page = parseInt($('input[name="last_page"]').val());
            let current_page = parseInt($('input[name="current_page"]').val());

            if(total <= 0 && search == null) {
                $('.card-body').remove();
                $('.card').append("<div class='alert alert-info mt-3 mx-3 text-center' role='alert'><i class='fas fa-info-circle'></i> Não há opções para mostrar.</div>");
            }
            if(total <= 0 && search != null) {
                $('.card-body').remove();
                $('.card').append("<div class='alert alert-info mt-3 mx-3 text-center' role='alert'><i class='fas fa-info-circle'></i> Nenhuma opção correspondeu ao seu critério de pesquisa. Por favor, tente novamente.</div>");
            }

            const max_links = 2;
            if(last_page > 1) {
                $('.card-header').append('<ul class="pagination pagination-sm m-0 float-right flex-1"></ul>');
                $('.card-header ul').append(`
                    <li class="page-item ${current_page === 1 ? 'disabled' : ''}"><a class="page-link" ${current_page === 1 ? 'disabled' : ''} href="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">«</font></font></a></li>
                `)
                for(let page_ant = current_page - max_links; page_ant <= current_page - 1; page_ant++) {
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
            $('h1 span').html(`${total}`);

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


            let checkboxes = document.querySelectorAll('input[type="checkbox"].option-check');
            if(total != 0)
            document.getElementById('select-all').addEventListener('change', function(e) {
                checkboxes.forEach(c => {
                    c.checked = this.checked;
                    c.addEventListener('change', () => this.checked = false);
                });
            });

            $(document).keyup(e => {
                if(e.key == 'Delete') {
                    checkDell = [];
                    checkboxes.forEach(c => {
                        if(c.checked) {
                            checkDell.push(c.id);
                        }
                    })
                    console.log(checkDell);
                    return delete_option(checkDell);
                }
            });

            localStorage.setItem('current_page_list_options', current_page);
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

function delete_option(arrayId = []) {
    if(arrayId.length <= 0) {
        return alert('Nenhuma item foi selecionado.');
    };
    if(arrayId.length == 1)
        var c = confirm('Tem certeza que você quer excluir essa variação?');
    else
        var c = confirm('Tem certeza que você quer excluir essas variações?');

    if(c) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "DELETE",
            data: {arrayId},
            url: url_del,
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
        return;
    }
}
