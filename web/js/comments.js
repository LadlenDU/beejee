var comments = {};

comments.verifyLength = function (type, length) {
    if (length < this.elements.lengths[type].min || length > this.elements.lengths[type].max) {
        return false;
    }
    return true;
};

comments.setFormError = function (itemName, errorText) {
    $("#form_comment .form-group:has([name=" + itemName + "])").addClass("has-error");
    $("#form_comment [name=" + itemName + "] + p.help-block-error").text(errorText);
    $("#form_comment [name=" + itemName + "] + p.help-block-error").show();
};

comments.verifyFormData = function (formData) {

    var success = true;

    function setWrongLengthInput(itemName) {
        var item = $("#form_comment [name=" + itemName + "]");

        itemContent = item.val();

        if (itemContent.length < item.attr("data-minlength")
            || itemContent.length > item.attr("maxlength")) {

            this.setFormError(itemName, item.attr("data-range-alert"));

            /*$("#form_comment .form-group:has([name=" + itemName + "])").addClass("has-error");
             $("#form_comment [name=" + itemName + "] + p.help-block-error")
             .text(item.attr("data-range-alert"));
             $("#form_comment [name=" + itemName + "] + p.help-block-error").show();*/

            return false;
        }

        return true;
    }

    $("#form_comment .form-group").removeClass("has-error");
    $("#form_comment .help-block-error").html("");

    var usernameContent = formData.get("username").trim();
    $("#form_comment input[name=username]").val(usernameContent);

    var emailContent = formData.get("email").trim();
    $("#form_comment input[name=email]").val(emailContent);

    var textContent = formData.get("text");

    var expr = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!expr.test(emailContent)) {
        /*$("#form_comment .form-group:has(input[name=email])").addClass("has-error");
         $("#form_comment input[name=email] + p.help-block-error")
         .text($("#form_comment input[name=email]").attr("wrong_email_alert"));
         $("#form_comment input[name=email] + p.help-block-error").show();*/

        this.setFormError("email", $("#form_comment input[name=email]").attr("wrong_email_alert"));

        success = false;
    }

    success &= setWrongLengthInput("username");
    success &= setWrongLengthInput("email");
    success &= setWrongLengthInput("text");

    return success;
};

$(function () {

    $(".thumb_image").click(function (e) {
        alert($(this).attr('data-preview-src'));
    });

    $("#comment_preview").click(function (e) {
        var formData = new FormData($("#form_comment")[0]);
        e.preventDefault();

        //comments.verifyFormData(formData);
        //return false;

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
                    //if (typeof (((x || {}).responseJSON || {}).data || {}).errors !== 'undefined')
                    if ((((x || {}).responseJSON || {}).data || {}).errors) {
                        if (x.responseJSON.data.errors.input_data) {
                            var arrLength = x.responseJSON.data.errors.input_data.length;
                            for (var i = 0; i < arrLength; ++i) {
                                comments.setFormError(x.responseJSON.data.errors.input_data[i].field,
                                    x.responseJSON.data.errors.input_data[i].message);
                            }
                        }
                        if (x.responseJSON.data.errors.common) {
                            helper.showError(implode("\n", x.responseJSON.data.errors.common));
                        }
                    }
                }
                comments.verifyFormData(new FormData($("#form_comment")[0]));
            }
        });
    });
});