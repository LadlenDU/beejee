var helper = {
    logInfo: function (msg) {
        if (typeof console !== "undefined" && typeof console.log !== "undefined") {
            console.log(msg);
        }
    },
    ifJson: function (data) {
        try {
            jQuery.parseJSON(data)
            return true;
        } catch (e) {
            return false;
        }
    },
    showError: function (data) {
        alert("Произошла ошибка: " + data);
        this.logInfo("Error: " + data);
    },
    implode: function (glue, pieces) {
        return ((pieces instanceof Array) ? pieces.join(glue) : pieces);
    }
}

if (!String.prototype.trim) {
    (function () {
        // Вырезаем BOM и неразрывный пробел
        String.prototype.trim = function () {
            return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
        };
    })();
}

$(function () {
    var data = {ajax: 1};
    data[$('meta[name="csrf-param"]').attr("content")] = $('meta[name="csrf-param"]').attr("token");
    $.ajaxSetup({
        cache: false,
        type: "POST",
        data: data
    });
});
