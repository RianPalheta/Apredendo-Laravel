$(document).ready(e => {
    let qt = 100;
    let page = 1;
    let option_loaded;
    list_brands(page, qt);
    list_categories(page, qt);
    $('#custom-tabs-four-variations input[type=radio]').on('change', e => {
        if(e.target.id == 'has-variation') {
            if(!option_loaded) { option_loaded = true; list_options(page, qt) }
            $('.select-options').removeClass('d-none');
        } else
            $('.select-options').addClass('d-none');
    })

    const mask = [
        '#price',
        '#cost-price',
        '#retail-price',
        '#promotion-price',
    ];
    mask.forEach(i => {
        IMask(document.querySelector(i), {
            mask: 'R$ num',
            blocks: {
              num: {
                mask: Number,
                thousandsSeparator: '.',
              }
            }
        })
    });
    IMask(document.querySelector('#pre-sales--release-date-of'), {
        mask: '00/00/0000'
    })

    tinymce.init({
        selector:'textarea.body-field',
        height:300,
        menubar:true,
        convert_urls:false,
        toolbar_sticky: true,
        // images_upload_url,
        // images_upload_credentials:true,
        // autosave_ask_before_unload: true,
        insertdatetime_formats: ['%H:%M:%S', '%Y-%m-%d', '%I:%M:%S %p', '%D'],
        plugins:'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',// ['link', 'table', 'image', 'imagetools', 'autoresize', 'lists', 'advlist', 'anchor', 'charmap', 'autosave', 'codesample', 'code', 'directionality', 'emoticons', 'insertdatetime', 'searchreplace', 'wordcount', 'media'],
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',//'undo redo | formatselect | bold italic underline  backcolor | alignleft aligncenter alignright alignjustify ltr rtl | link image media emoticons | bullist numlist arrowlist | lists advlist | codesample code',
    });

    $('input[type=radio]').on('change', i => {
        if(i.target.id == 'pre-sales') {
            $('.pre-sales--data').removeClass('d-none');
        } else {
            $('.pre-sales--data').addClass('d-none');
        }
    });

    $(function() {
        $('#pre-sales--release-date-of-datepicker').datetimepicker({
            locale: 'pt',
            format: 'L'
        });
    })
});

$(function() {
    $('#add-product').submit(function(e){
        e.preventDefault();
        let form = document.getElementById('add-product');
        let formData = new FormData(form);
        console.log(formData)

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
                /*r = JSON.parse(response);
                if(r.success === true) {
                    toastr["success"]("Produto cadastrado com sucesso!", "Sucesso")
                    setTimeout(() => {
                        window.location.href = url_products;
                    }, 1000 );
                } else {
                    let msg = new Array(r.message);
                    msg.forEach((item, key) => {
                        $(`input`).removeClass('is-invalid');
                        $(`input[name=${Object.keys(item)[key]}]`).addClass('is-invalid');
                        toastr["error"](Object.values(item)[key], "Error");
                    });
                }*/
            }
        });
    })
})

function list_categories(page, qt, search = null) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: get_category,
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

function list_brands(page, qt, search = null) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: get_brands,
        type:'GET',
        data: {page, qt, search},
        dataType:'JSON',
        beforeSend: function() {
            $('#brand').append("<option id='load'>Carregando...</option>")
        },
        success:function(response) {
            let last_page = response.last_page;
            let current_page = response.current_page;
            let data = Object.values(response.data);
            $('#load').remove();

            if(parseInt(response.total) > 0) {
                return data.map(i => {
                    let table = `
                    <option value="${i.id}">
                        ${i.name}
                    </option>
                    `;
                    $('#brand').append(table);
                });
            } else {
                return $('#brand').html("<option>Não há categorias para mostrar.</option>");
            }
        }
    })
}

function list_options(page, qt) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: get_options,
        type:'GET',
        data: {page, qt, view:'select'},
        beforeSend: function() {
            $('.select-options').append("<div id='content-load' class='card d-flex justify-content-center align-items-center p-3' style='width:100%; height:100%;'><i class='fas fa-circle-notch rotafe-infinit'></i></div>");
        },
        success:function(response) {
            $('#content-load').remove();
            $('.select-options').append(response);
            $('#select-options').on('change', function(e) {
                option_selected = $(this).val();
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

$('a[href="#price-options"]').click(function() {
    if($("#price-options").hasClass('show')) {
        $(this).text('Mais opções de preço');
    } else {
        $(this).text('Menos opções de preço');
    }
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
