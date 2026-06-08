$(document).ready(function () {


    $(document).on("click", ".fav-toggle", function () {
        const btn = $(this);
        const typ = btn.data("typ");
        const id = btn.data("id");

        $.post("toggle_fav.php", { typ: typ, id_obiektu: id }, function (resp) {

            if (resp === "dodano") {
                btn.text("Usuń z ulubionych ❤️");
                btn.data("liked", 1);

            } else if (resp === "usunieto") {
                btn.text("Dodaj do ulubionych 🤍");
                btn.data("liked", 0);

            } else {
                console.log("Błąd toggle_fav.php:", resp);
            }
        });
    });



    $(document).on("click", ".delete-review", function () {
        const id = $(this).data("review-id");

        if (!confirm("Czy na pewno chcesz usunąć recenzję?")) return;

        $.post("delete_review_ajax.php", { id: id }, function (resp) {

            if (resp === "ok") {
                $("#review-" + id).fadeOut(300, function () {
                    $(this).remove();
                });

            } else if (resp === "brak_uprawnien") {
                alert("Brak uprawnień.");

            } else {
                alert("Wystąpił błąd podczas usuwania recenzji.");
                console.log("delete_review_ajax.php:", resp);
            }
        });
    });



    let timer = null;

    $(document).on("input", "#live-search", function () {
        const fraza = $(this).val().trim();

        clearTimeout(timer);

        if (fraza.length < 2) {
            $("#live-results").html("");
            return;
        }

        timer = setTimeout(() => {
            $.get("search_ajax.php", { fraza: fraza }, function (resp) {
                $("#live-results").html(resp);
            });
        }, 300);
    });

});


