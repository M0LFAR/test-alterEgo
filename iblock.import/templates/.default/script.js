$(document).ready(function() {
    $("#refresh").click(function () {
        $.ajax({
            url: location.href,
            type: "get",
            data: {
                type_answer: 'json'
            },
            success: function (result) {
                console.log($.parseJSON(result));
                //$("body").html(result);
            },
            error: function(xhr) {
             alert(xhr);
            }
        });
    });
});