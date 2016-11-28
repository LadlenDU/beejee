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

    var that = this;

    function setWrongLengthInput(itemName) {
        var item = $("#form_comment [name=" + itemName + "]");

        itemContent = item.val();

        if (itemContent.length < item.attr("data-minlength")
            || itemContent.length > item.attr("maxlength")) {

            that.setFormError(itemName, item.attr("data-range-alert"));
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

    $("#preview_button").click(function (e) {
        $("#preview_messages_wrapper").hide();
        $("#messages").show(1000);
    });

    $("#comment_preview").click(function (e) {
        var formData = new FormData($("#form_comment")[0]);
        e.preventDefault();

        comments.verifyFormData(formData);

        $.ajax({
            url: '/comments/preview',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                comments.verifyFormData(new FormData($("#form_comment")[0]));
                $("#preview_messages").html(data);
                $("#messages").hide();
                $("#preview_messages_wrapper").show(1000);
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
                    comments.verifyFormData(new FormData($("#form_comment")[0]));
                    if ((((x || {}).responseJSON || {}).data || {}).errors) {
                        if (x.responseJSON.data.errors.input_data) {
                            var arrLength = x.responseJSON.data.errors.input_data.length;
                            for (var i = 0; i < arrLength; ++i) {
                                comments.setFormError(x.responseJSON.data.errors.input_data[i].field,
                                    x.responseJSON.data.errors.input_data[i].message);
                            }
                        }
                        if (x.responseJSON.data.errors.common) {
                            helper.showError(helper.implode("\n", x.responseJSON.data.errors.common));
                        }
                    }
                }
            }
        });
    });
});