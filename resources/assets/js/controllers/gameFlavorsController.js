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

        init = function () {
            var instance = this;
            reportGameFlavorBtnHandler();
        };
    return {
        init: init
    }
}();