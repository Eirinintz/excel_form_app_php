$(document).ready(function () {

    $(document).on("click", function (e) {
        // if click is NOT inside an autocomplete box or textarea
        if (!$(e.target).closest(".autocomplete-box, textarea").length) {
            $(".autocomplete-box").empty();
        }
    });



    $("#id_syggrafeas").on("keyup", function () {
        let query = $(this).val();
        if (query.length < 2) return;

        $.ajax({
            url: "/ajax/autocomplete/syggrafeas/", // or use a data attribute in template
            data: { q: query },
            success: function (data) {
                let box = $("#syggrafeas-suggestions");
                box.empty();
                data.results.forEach(item => {
                    box.append(`<div class="suggestion-item">${item}</div>`);
                });

                $(".suggestion-item").click(function () {
                    $("#id_syggrafeas").val($(this).text());
                    box.empty();
                });
            }
        });
    });

    $("#id_etosEkdoshs").on("keyup", function () {
        let query = $(this).val();
        if (query.length < 2) return;

        $.ajax({
            url: "/ajax/autocomplete/etosEkdoshs/", // or use a data attribute in template
            data: { q: query },
            success: function (data) {
                let box = $("#etosEkdoshs-suggestions");
                box.empty();
                data.results.forEach(item => {
                    box.append(`<div class="suggestion-item">${item}</div>`);
                });

                $(".suggestion-item").click(function () {
                    $("#id_etosEkdoshs").val($(this).text());
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
