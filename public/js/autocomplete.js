$(document).ready(function () {
    $("#id_titlos").on("keyup", function () {
        let query = $(this).val();
        if (query.length < 2) return;

        $.ajax({
            url: "/ajax/autocomplete/title/", // or use a data attribute in template
            data: { q: query },
            success: function (data) {
                let box = $("#title-suggestions");
                box.empty();
                data.results.forEach(item => {
                    box.append(`<div class="suggestion-item">${item}</div>`);
                });

                $(".suggestion-item").click(function () {
                    $("#id_titlos").val($(this).text());
                    box.empty();
                });
            }
        });
    });



  $("#id_ekdoths").on("keyup", function () {
        let query = $(this).val();
        if (query.length < 2) return;

        $.ajax({
            url: "/ajax/autocomplete/ekdoths/", // or use a data attribute in template
            data: { q: query },
            success: function (data) {
                let box = $("#ekdoths-suggestions");
                box.empty();
                data.results.forEach(item => {
                    box.append(`<div class="suggestion-item">${item}</div>`);
                });

                $(".suggestion-item").click(function () {
                    $("#id_ekdoths").val($(this).text());
                    box.empty();
                });
            }
        });
    });
});
