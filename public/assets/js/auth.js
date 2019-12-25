$(document).ready(function() {
    $('form.login').submit(function(event) {
        var formData = {'method':0};
        (function(){
            $('form.login').find(":input").not("[type='submit']").not("[type='reset']").each(function(){
                var thisInput = $(this);
                formData[thisInput.attr("name")] = thisInput.val();
            });
        })();
        console.log(formData);
        // process the form
        $.ajax({
            type        : 'POST',
            url         : 'public/api/api.authentication.php',
            data        : formData,
            dataType : "json",
            encode:true
        }).done(function(data) {
            if(data.error==false){
                $("#box").html('<div class="uk-alert-success" uk-alert><p><span uk-icon="check"></span> ' + data.message + '</p></div>').show(500);
                setInterval(function(){window.location="./";},1000);
            } else{
                $("#box").html('<div class="uk-alert-danger" uk-alert><p><span uk-icon="warning"></span> ' + data.message + '</p></div>').show(500);
            }
        }).fail(function(data){
            console.log(data);
            alert("สมัครสมาชิกไม่สำเร็จ. กรุณาติดต่อผู้ดูแลระบบ");
        });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

    $('form.register').submit(function(event) {
        var formData = {'method':1};
        (function(){
            $('form.register').find(":input").not("[type='submit']").not("[type='reset']").each(function(){
                var thisInput = $(this);
                formData[thisInput.attr("name")] = thisInput.val();
            });
            //Manual fix
            formData["gender"] = $("input[name='gender']:checked").val();
        })();
        // process the form
        console.log(formData);
        $.ajax({
            type        : 'POST',
            url         : 'public/api/api.authentication.php',
            data        : formData,
            dataType : "json",
            encode:true
        }).done(function(data) {
            if(data.error==false){
                $("#box").html('<div class="uk-alert-success" uk-alert><p><span uk-icon="check"></span> ' + data.message + '</p></div>').show(500);
                setInterval(function(){window.location="./";},1000);
            } else{
                $("#box").html('<div class="uk-alert-danger" uk-alert><p><span uk-icon="warning"></span> ' + data.message + '</p></div>').show(500);
            }
        }).fail(function(data){
            console.log(data);
            alert("สมัครสมาชิกไม่สำเร็จ. กรุณาติดต่อผู้ดูแลระบบ");
        });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });
});