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

comments.prepareEventsForComments = function () {
    $(".fancybox").fancybox();

    $("[name='comment_status']").change(function (e) {
        var data = {status: $(this).val(), id: $(this).closest(".comment_panel").attr("data-id")};
        $.ajax({
            url: '/admin/comments/changestatus',
            data: data,
            success: function (msg) {
                alert(msg);
            },
            error: function (x) {
                comments.handleCommonJqueryAjaxErrors(x);
            }
        });
    });

    $(".edit_text_wrapper .edit").click(function (e) {
        var currPanel = $(this).closest(".comment_panel");

        $(".text .value", currPanel).hide();
        $(".text_mod", currPanel).show();

        $(".edit_text_wrapper .edit", currPanel).hide();
        $(".edit_text_wrapper .cancel", currPanel).show();
        $(".edit_text_wrapper .save", currPanel).show();
    });

    $(".edit_text_wrapper .cancel").click(function (e) {
        var currPanel = $(this).closest(".comment_panel");

        $(".text_mod", currPanel).hide();
        $(".text .value", currPanel).show();

        $(".edit_text_wrapper .cancel", currPanel).hide();
        $(".edit_text_wrapper .save", currPanel).hide();
        $(".edit_text_wrapper .edit", currPanel).show();
    });

    $(".edit_text_wrapper .save").click(function (e) {
        var currPanel = $(this).closest(".comment_panel");
        var data = {text: $(".text_mod", currPanel).val(), id: currPanel.attr("data-id")};
        $.ajax({
            url: '/admin/comments/changetext',
            data: data,
            success: function (msg) {
                alert(msg);
                comments.getComments();
            },
            error: function (x) {
                comments.handleCommonJqueryAjaxErrors(x);
            }
        });
    });
}

comments.onLoadErrorHandling = function (errors) {
    comments.verifyFormData(new FormData($("#form_comment")[0]));
    if (errors.input_data) {
        var arrLength = errors.input_data.length;
        for (var i = 0; i < arrLength; ++i) {
            comments.setFormError(errors.input_data[i].field, errors.input_data[i].message);
        }
    }
    if (errors.common) {
        helper.showError(helper.implode("\n", errors.common));
    }
}

comments.handleJqueryFormError = function (x) {
    if (x.status == 500) {
        if ((((x || {}).responseJSON || {}).data || {}).errors) {
            comments.onLoadErrorHandling(x.responseJSON.data.errors);
        }
    }
}

comments.prepareDataForGet = function () {
    var data = {};

    if (comments.checkAdmin) {
        data.checkAdmin = 1;
    }
    data.order_by = $("#order_by").val();
    data.order_direction = $("#order_direction").val();

    return data;
}

comments.getComments = function () {
    var data = comments.prepareDataForGet();
    $.ajax({
        url: "/comments/get",
        type: "GET",
        data: data,
        success: function (html) {
            $("#messages").html(html);
            $("#preview_messages_wrapper").hide();
            $("#messages").show(1000);
            comments.prepareEventsForComments();

            window.history.replaceState('sort', '', '?order_by=' + data.order_by + '&order_direction=' + data.order_direction);

        }
    });
}

comments.handleCommonJqueryAjaxErrors = function (x) {
    if (x.status == 500) {
        if ((((x || {}).responseJSON || {}).data || {}).errors) {
            helper.showError(helper.implode("\n", x.responseJSON.data.errors));
        }
    }
}

$(function () {

    $("#preview_button").click(function (e) {
        $("#preview_messages_wrapper").hide();
        $("#messages").show(1000);
    });

    $("#comment_preview").click(function (e) {
        var formData = new FormData($("#form_comment")[0]);
        e.preventDefault();

        if (!comments.verifyFormData(formData)) {
            return false;
        }

        $.ajax({
            url: '/comments/new?preview=1',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#preview_messages").html(data);
                $("#messages").hide();
                $("#preview_messages_wrapper").show(1000);
                comments.prepareEventsForComments();
            },
            error: function (x) {
                comments.handleJqueryFormError(x);
            }
        });
    });

    $("#send_comment").click(function (e) {
        var formData = new FormData($("#form_comment")[0]);
        e.preventDefault();

        if (!comments.verifyFormData(formData)) {
            return false;
        }

        $.ajax({
            url: '/comments/new',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                comments.getComments();
                alert('Спасибо! Ваш комментарий станет виден после проверки его администратором.');
            },
            error: function (x) {
                comments.handleJqueryFormError(x);
            }
        });
    });

    $("#order_by, #order_direction").change(function (e) {
        comments.getComments();
    });

    comments.getComments();
});