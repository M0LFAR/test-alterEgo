$(document).ready(function() {
    $("#refresh").click(function () {
        $.ajax({
            url: location.href,
            success: function (result) {
                console.log(result);
                //$("body").html(result);
            }
        });
    });
});