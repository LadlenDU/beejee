comments.verifyLength = function (type, length) {
    if (length < this.elements.lengths[type].min || length > this.elements.lengths[type].max) {
        return false;
    }
    return true;
};
comments.verifyData = function (formData) {
    var errors = [];

    /*function VVV()
    {
        alert('sdfsdflj');
    }

    VVV();*/

    with (comments.elements.lengths) {

        $("#form_comment .form-group").removeClass("has-error");

        var fUsername = formData.get("username").trim();
        $("#form_comment input[name=username]").val(fUsername);

        if (fUsername.length < username.min) {
            $("#form_comment .form-group:has(input[name=username])").addClass("has-error");
            $("#form_comment input[name=username] + p.help-block-error").html(username.min_alert);
            $("#form_comment input[name=username] + p.help-block-error").show();
        } else if (fUsername.length > username.max) {
            errors.push(username.max_alert);
        }
        if (formData.email.length < email.min) {
            errors.push(email.min_alert);
        } else if (formData.email.length > email.max) {
            errors.push(email.max_alert);
        }
        if (formData.text.length < text.min) {
            errors.push(text.min_alert);
            //$("")
        } else if (formData.text.length > text.max) {
            errors.push(text.max_alert);
        }

        if (errors) {
            alert(implode('\n', errors));
            return false;
        }

    }

    return true;
};

$(function () {

    $(".thumb_image").click(function (e) {
        alert($(this).attr('data-preview-src'));
    });

    $("#comment_preview").click(function (e) {
        var formData = new FormData($("#form_comment")[0]);
        e.preventDefault();

        comments.verifyData(formData);
        //comments.verifyData.VVV();
        return false;

        $.ajax({
            url: '/comments/preview',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#preview_messages").html(data);
                $(".messages").hide();
                $("#preview_messages").show(1000);
                /*if (helper.ifJson(data)) {
                 if (data.success) {
                 $("#preview_messages").html(data.data);
                 $(".messages").hide();
                 $("#preview_messages").show(500);
                 } else {
                 helper.showError(data.data);
                 }
                 }
                 else {
                 helper.showError(data);
                 }*/
            },
            error: function (x) {
                if (x.status == 500) {
                    helper.showError(x.responseJSON.data);
                }
            }
        });
    });
});