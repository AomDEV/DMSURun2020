$(".save-bill").on('click', function (e) {
    var nameImage = $(".upload-file").val();
    if(nameImage.match(/jpg.*/)||nameImage.match(/jpeg.*/)||nameImage.match(/png.*/)){
        $(this).attr("disabled","disabled");
        e.preventDefault();

        var formData = new FormData($("form.form-bill")[0]);

        $.ajax({
            url: 'public/api/api.bill.php',
            type: 'POST',
            data: formData,
            success: function (data) {
                var callback = JSON.parse(data);
                if(callback["error"]==true){
                    $("#box").show(500).html('<div class="uk-alert-danger" uk-alert><span uk-icon="warning"></span> '+callback["message"]+'</div>');
                    $(this).removeAttr("disabled");
                } else{
                    $("#box").show(500).html('<div class="uk-alert-success" uk-alert><span uk-icon="check"></span> '+callback["message"]+'</div>');
                    setInterval(function(){window.location="./?a=bill";},2000);
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });

    }else{
        e.preventDefault();
        $(this).removeAttr("disabled");
        $("#box").show(500).html('<div class="uk-alert-danger" uk-alert><span uk-icon="warning"></span> ไฟล์ต้องเป็นรูปภาพเท่านั้น!</div>');
    }
});