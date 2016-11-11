var helper = {
    logInfo: function (msg) {
        if (typeof console !== "undefined" && typeof console.log !== "undefined") {
            console.log(msg);
        }
    }
}

$(function () {
    var data = {ajax: 1};
    //data[$('meta[name="csrf-param"]').attr("content")] = $('meta[name="csrf-param"]').attr("token");
    $.ajaxSetup({
        cache: false,
        type: "POST",
        data: data
    });
});
