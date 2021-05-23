$(function() {
    const btn_text = $('#submit_add').html();
    let btn_click = false;

    $('#categorie-add').submit(function(e){
        e.preventDefault();
        if(btn_click !== false) return null;

        let form = document.getElementById('categorie-add');
        let formData = new FormData(form);
        let request = new XMLHttpRequest();
        btn_click = true;

        request.addEventListener('loadstart', function(e) {
            $('#submit_add')
            .addClass('disabled')
            .html('<i class="fas fa-circle-notch rotafe-infinit"></i>');
        });

        request.upload.addEventListener('progress', function(e) {
            var percent = Math.round(e.loaded / e.total * 100);
            $('.progress-bar')
            .width(`${percent}%`)
            .removeClass('bg-success')
            .addClass('bg-primary')
            .attr('aria-valuenow', percent)
            .text(`${percent}%`);
        });
        request.addEventListener('load', function(e) {
            btn_click = false;

            $('#submit_add')
            .removeClass('disabled')
            .html(btn_text);

            $('.progress-bar')
            .removeClass('bg-primary')
            .addClass('bg-success')
            .text('Completo!');
            if(request.status === 200) {
                let r = JSON.parse(request.response);
                if(r.success === true) {
                    toastr["success"]("Categoria cadastrada com sucesso!", "Sucesso")
                    setTimeout(() => {
                        window.location.href = url_list;
                    }, 1000 );
                } else {
                    let msg = new Array(r.message);
                    msg.forEach((item, key) => {
                        $(`input`).removeClass('is-invalid');
                        $(`input[name=${Object.keys(item)[key]}]`).addClass('is-invalid');
                        toastr["error"](Object.values(item)[key], "Error");
                    });
                }
            } else {
                btn_click = false;

                $('#submit_add')
                .html(btn_text);

                $('.progress-bar')
                .removeClass('bg-primary')
                .addClass('bg-danger')
                .text('Algo deu errado!');
                toastr["error"]("Ops! Algo deu errado.", "Error");
            }
        });
        request.addEventListener('error', function(e) {
            btn_click = false;

            $('#submit_add')
            .html(btn_text);

            $('.progress-bar')
            .removeClass('bg-primary')
            .addClass('bg-danger')
            .text('Algo deu errado!');

            toastr["error"]("Ops! Algo deu errado.", "Error");
        });

        request.open('POST', url_add);
        request.send(formData);
    })
    $("input[type=file]").on("change", function(){
        let img = document.querySelector("#img_new_categorie").files[0];
        if(!img) return;
        let src = URL.createObjectURL(img);
        $('.new_img_label').html(`${img.name}`);
        $('#img-categorie').attr('src', src);
    });
})

let page = 1;
let qt = 100;
list_categories(page, qt);
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
            $('#category').append("<option id='load'>Carregando...</option>")
        },
        success:function(response) {
            let last_page = response.last_page;
            let current_page = response.current_page;
            let data = Object.values(response.data);
            $('#load').remove();

            if(parseInt(response.total) > 0) {
                let table = organizeCategory(data, level = 0);
                $('#category').append(table);
            } else {
                $('#category').html("<option>Não há categorias para mostrar.</option>");
                return;
            }
        }
    })
}

function organizeCategory(array = [], level) {
    if(array.length < 0) return null;

    let mark = '-- ';
    for(let l = 0; l < level; l++) {
        var level_cat = mark;
        for(let q = 0; q < l; q++) {
            level_cat += mark;
        }
    }

    let option = array.map(i => {
        let option = `
        <option value="${i.id}">
            ${ isEmpty(level_cat) ? level_cat + i.name : i.name}
        </option>
        `
        if(isEmpty(i.subs)) {
            let subs = organizeCategory(Object.values(i.subs), level + 1);
            option += subs;
        }

        return option;
    })
    return option;
}

function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return true;
    }

    return false;
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
