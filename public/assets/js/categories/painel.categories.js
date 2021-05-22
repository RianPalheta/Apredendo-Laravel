var qt_result_pg = 50;
var page = 1;

$(document).ready(function() {
    if(localStorage.getItem('current_page_list_categories')) {
        page = localStorage.getItem('current_page_list_categories');
    }
    if(localStorage.getItem('qt_result_pg_list_categories')) {
        qt_result_pg = localStorage.getItem('qt_result_pg_list_categories');
    }

    if($('table').length > 0) {
        [...$('select').children()].forEach(item => {
            if(item.getAttribute('value') === qt_result_pg) {
                item.setAttribute('selected','selected')
            }
        });
        list_categories(page, qt_result_pg);
    }
});

$('select').on('change', function() {
    let option = $('select').val();
    localStorage.setItem('qt_result_pg_list_categories', option);
    list_categories(1, option);
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
    list_categories(1, localStorage.getItem('qt_result_pg_list_categories'), serach);
}


function list_categories(page, qt, search = null) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url,
        type:'GET',
        data: {page, qt, search},
        dataType:'JSON',
        beforeSend: function() {
            $('tbody').html("Carregando...")
        },
        success:function(response) {
            let last_page = response.last_page;
            let current_page = response.current_page
            let data = Object.values(response.data);
            localStorage.setItem('current_page_list_categories', current_page);

            $('tbody').html('');
            $('.card-header ul').html('');

            if(parseInt(response.total) > 0) {

                let table = organizeCategory(data, url_edit, level = 0);
                $('tbody').append(table);
            } else {
                $('table').html("<div class='alert alert-light' role='alert'>Não há categorias para mostrar.</div>");
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
                        list_categories(attr, localStorage.getItem('qt_result_pg_list_categories'));
                    }
                })
            });

            const btn_del = [...$('button[data-delete]')];
            btn_del.forEach(btn => {
                let id = btn.getAttribute('data-delete');
                btn.onclick = delete_user(id);
            });
        }
    })
}

function organizeCategory(array = [], url, level) {
    if(array.length < 0) return null;
    
    let mark = '-- ';
    for(let l = 0; l < level; l++) {
        var level_cat = mark;
        for(let q = 0; q < l; q++) {
            level_cat += mark;
        }
    }

    let table = array.map(i => {
        let url_edit = url.replace('1', i.id);
        let table = `
        <tr>
            <td>${ isEmpty(level_cat) ? level_cat + i.name : i.name}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <a title="Editar" href="${url_edit}" class="btn btn-sm btn-info"><i class="fas fa-user-edit"></i></a>
                    <button onclick="delete_item(${i.id})" title="Deletar" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
        `
        if(isEmpty(i.subs)) {
            let subs = organizeCategory(Object.values(i.subs), url, level + 1);
            table += subs;
        }
        
        return table;
    })
    return table;
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
    let c = confirm('Tem certeza que você quer excluir esse usuário?');
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
                list_categories(
                    localStorage.getItem('current_page_list_categories'),
                    localStorage.getItem('qt_result_pg_list_categories')
                );
            }
        });
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