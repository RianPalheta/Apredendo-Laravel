$(function() {
    $('#brand-edit').submit(function(e){
        e.preventDefault();
        let form = document.getElementById('brand-edit');
        let formData = new FormData(form);
        let url = url_edit.replace('1', id);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url,
            data: formData,
            processData: false,
		    contentType: false,
            success: function (response) {
                r = JSON.parse(response);
                if(r.success === true) {
                    toastr["success"]("Marca editada com sucesso!", "Sucesso");

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
    });
    $("input[type=file]").on("change", function(){
        let img = document.querySelector("#img_new_brand").files[0];
        if(!img) return;
        let src = URL.createObjectURL(img);
        $('.new_img_label').html(`${img.name}`);
        $('#img-brand').attr('src', src);
    });
});

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
