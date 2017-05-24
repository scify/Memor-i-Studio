window.GameFlavorsController = function () {

};

window.GameFlavorsController.prototype = function () {
    var reportGameFlavorBtnHandler = function () {
            $(".reportGameFlavorBtn").on("click", function (e) {
                e.stopPropagation();
                var gameFlavorId = $(this).attr("data-gameFlavorId");
                console.log(gameFlavorId);
                $('#createGameFlavorReportModal').modal('toggle');
                $('#reportGameFlavorForm #gameFlavorIdInput').val(gameFlavorId);
            });
        },
        downloadBtnHandler = function () {
            console.log("downloadBtnHandler");
            $("body").on("click", ".downloadBtnWindows", function (e) {
                e.preventDefault();
                var gameFlavorId = $(this).attr("data-gameFlavorId");


                ga('send', {
                    hitType: 'event',
                    eventCategory: 'Games',
                    eventAction: 'download',
                    eventLabel: 'Windows | game id: ' + gameFlavorId
                });
                window.location = this.href;
            });

            $("body").on("click", ".downloadBtnLinux", function (e) {
                e.preventDefault();
                var gameFlavorId = $(this).attr("data-gameFlavorId");

                ga('send', {
                    hitType: 'event',
                    eventCategory: 'Games',
                    eventAction: 'download',
                    eventLabel: 'Linux | game id: ' + gameFlavorId
                });
                window.location = this.href;
            });
        },
        init = function () {
            var instance = this;
            reportGameFlavorBtnHandler();
            downloadBtnHandler();
        };
    return {
        init: init
    }
}();