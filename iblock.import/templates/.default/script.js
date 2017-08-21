$(document).ready(function() {
    $("#refresh").click(function () {
        $.ajax({
            url: location.href,
            type: "get",
            data: {
                type_answer: 'json'
            },
            success: function (itemsIblock ) {
                var domElements='';
                var lastUpdate = new Date(itemsIblock.lastUpdate);
                console.log(lastUpdate,itemsIblock.lastUpdate);
                $.each(itemsIblock.elements, function (number, elementDom) {
                    //console.log(elementDom, elementDom.link);
                    var link = elementDom.link;
                    var title = elementDom.title;

                    domElements += `<p class="news-item" style="background: cornflowerblue;">
                                           <a href="//${link}">
                                                <b>${title}</b>
                                            </a>
                                   </p>`;
                });

                $(".news-list").prepend(domElements);
                //$("#last-update").html(lasUpdate.toString());
            },
            error: function(xhr) {
             alert(xhr);
            }
        });
    });
});