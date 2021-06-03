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

$(function() {
    $('#brand-add').submit(function(e){
        e.preventDefault();
        let form = document.getElementById('brand-add');
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
                    toastr["success"]("Marca cadastrada com sucesso!", "Sucesso")
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
                return;
            }
        });
    })
})

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
