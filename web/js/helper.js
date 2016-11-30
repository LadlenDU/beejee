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
    },
    /**
     * Вставка параметра URL
     */
    insertUrlParam: function (urlParams, key, value) {
        key = encodeURI(key);
        value = encodeURI(value);

        var kvp = urlParams.split('&');

        var i = kvp.length;
        var x;
        while (i--) {
            x = kvp[i].split('=');

            if (x[0] == key) {
                x[1] = value;
                kvp[i] = x.join('=');
                break;
            }
        }

        if (i < 0) {
            kvp[kvp.length] = [key, value].join('=');
        }

        if (kvp[0] == "") {
            kvp.shift();
        }

        return kvp.join('&');
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
    data[$('meta[name="csrf-param"]').attr("content")] = $('meta[name="csrf-token"]').attr("content");
    if (typeof globalDebug !== 'undefined' && globalDebug) {
        data["debug"] = 1;
    }
    $.ajaxSetup({
        cache: false,
        type: "POST",
        data: data
    });
});
