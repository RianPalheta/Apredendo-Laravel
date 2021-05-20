tinymce.init({
    selector:'textarea.body-field',
    height:300,
    menubar:true,
    plugins:['link', 'table', 'image', 'autoresize', 'lists', 'advlist', 'anchor', 'charmap', 'autosave', 'codesample', 'code', 'directionality', 'emoticons', 'insertdatetime', 'searchreplace', 'wordcount', 'media'],
    toolbar:'undo redo | formatselect | bold italic underline  backcolor | alignleft aligncenter alignright alignjustify ltr rtl | link image imagetools emoticons | bullist numlist arrowlist | lists advlist | codesample code',
    autosave_ask_before_unload: true,
    insertdatetime_formats: ['%H:%M:%S', '%Y-%m-%d', '%I:%M:%S %p', '%D'],
    images_upload_url,
    images_upload_credentials:true,
    convert_urls:false
})

$(function() {
    $('#page-edit').submit(function(e){
        e.preventDefault();
        let url = url_edit.replace('1', id);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url,
            data: $(this).serialize(),
            success: function (response) {
                r = JSON.parse(response);
                if(r.success === true) {
                    toastr["success"]("Página editada com sucesso!", "Sucesso");
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
