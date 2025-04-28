import {Pleasure} from "../../pleasure-admin-panel/js/pleasure";

(function () {
    let reportGameFlavorBtnHandler = function () {
        const body = $("body");
        body.on("click", ".reportGameFlavorBtn", function (e) {
            e.stopPropagation();
            const gameFlavorId = $(this).attr("data-gameFlavorId");
            console.log(gameFlavorId);
            console.log($('#createGameFlavorReportModal'));
            $('#createGameFlavorReportModal').modal('toggle');
            $('#reportGameFlavorForm #gameFlavorIdInput').val(gameFlavorId);
        });
    };

    let downloadBtnHandler = function () {
        const body = $("body");
        body.on("click", ".downloadBtnWindows", function (e) {
            downloadGame(e, $(this), 'Windows');
        });

        body.on("click", ".downloadBtnLinux", function (e) {
            downloadGame(e, $(this), 'Linux');
        });
    };
    let downloadGame = function (event, element, platformName) {
        event.preventDefault();
        const gameFlavorId = element.attr("data-gameFlavorId");
        if (typeof ga === "function")
            ga('send', {
                hitType: 'event',
                eventCategory: 'Games',
                eventAction: 'download',
                eventLabel: platformName + ' | game id: ' + gameFlavorId
            });
        window.location = element.attr('href');
    };
    let getGamesWithFiltersHandler = function () {
        const body = $("body");
        body.on("click", "#getGames", function (e) {
            getGamesWithFilters();
        });
    };
    let getGamesWithFilters = function () {
        const languageId = $('select[name=language] option').filter(':selected').attr('value');
        const userObj = user;
        const formLoader = $('#gamesLoader');
        const formButton = $('#getGames');
        const errorEl = $('#error');
        const _token = $('input[name$="_token"]').val();
        const resultsEl = $('#gameResults');
        $.ajax({
            method: "POST",
            url: gameFlavorsRoute,
            cache: false,
            data: {user: userObj, _token: _token, language_id: languageId},
            beforeSend: function () {
                errorEl.html('');
                errorEl.addClass('display-none');
                formLoader.removeClass('display-none');
                formButton.attr('disabled', true);
            },
            success: function (response) {
                formLoader.addClass('display-none');
                formButton.attr('disabled', false);
                resultsEl.html(response);
                Pleasure.init();
            },
            error: function (error) {
                formLoader.addClass('display-none');
                formButton.attr('disabled', false);
                errorEl.removeClass('display-none');
                errorEl.html(error.responseJSON.message);
                console.error(error);
            }
        });
    };
    let init = function () {
        reportGameFlavorBtnHandler();
        downloadBtnHandler();
        getGamesWithFiltersHandler();
        getGamesWithFilters();
    };
    $(document).ready(function () {
        init();
    });
})();
