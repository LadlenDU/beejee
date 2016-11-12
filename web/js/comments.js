var comments = {
    elements: {lengths: {username: {min: 1, max: 150}, email: {min: 5, max: 255}, text: {min: 1, max: 65535}}},
    verifyLength: function (type, length) {
        if (length < this.elements.lengths[type].min || length > this.elements.lengths[type].max) {
            return false;
        }
        return true;
    },
    verifyData: function (formData) {
        alert(formData);
    }
};

$(function () {

    $(".thumb_image").click(function (e) {
        alert($(this).attr('data-preview-src'));
    });

    $("#comment_preview").click(function (e) {
        var formData = new FormData($("#form_comment")[0]);
        e.preventDefault();

        //comments.verifyData(formData);

        $.ajax({
            url: '/comments/preview',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.success) {

                } else {
                    alert("Произошла ошибка: " + data.data.message);
                }
                //helper.logInfo("success");
                //helper.logInfo(data);
            },
            error: function (data) {
                alert("Произошла ошибка: " + data);
                helper.logInfo("Ошибка:");
                helper.logInfo(data);
            }
        });
    });
});