$(document).ready(function() {
    $("#refresh").click(function () {
        $.ajax({
            url: location.href,
            type: "get",
            data: {
                type_answer: 'json'
            },
            success: function (itemsIblock) {
                itemsIblock = $.parseJSON(itemsIblock);
                var domElements='';
                var lastUpdate = new Date(1000*itemsIblock.lastUpdate);

                $.each(itemsIblock.elements, function (number, elementDom) {

                    var link = elementDom.link;
                    var title = elementDom.title;

                    domElements += `<p class="news-item" style="background: #f5f5f5;">
                                           <a href="${link}">
                                                <b>${title}</b>
                                            </a>
                                   </p>`;
                });

                $(".news-list").prepend(domElements);
                $("#last-update").html(lastUpdate.toLocaleString());
            },
            error: function(xhr) {
             alert(xhr);
            }
        });
    });
});