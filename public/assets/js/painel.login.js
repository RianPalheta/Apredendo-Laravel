$(function() {
    $('form').submit(function(e) {
        e.preventDefault()

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:url,
            type:'POST',
            data:$(this).serialize(),
            dataType:'JSON',
            success:function(response) {
                if(response.success === false) {
                    let input = new Array(response.message)
                    input.map((item, key) => {
                        $(`input`).removeClass('is-invalid')
                        $(`input[name=${Object.keys(item)[key]}]`).addClass('is-invalid')
                    })
                } else {
                    window.location.href = painel
                }
            }
        })
    })
})
