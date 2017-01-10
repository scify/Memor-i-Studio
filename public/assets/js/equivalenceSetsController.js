window.EquivalenceSetController = function () {

};

window.EquivalenceSetController.prototype = function () {
    var deleteEquivalenceSetBtnHandler = function () {
            $(".deleteSetBtn").on("click", function (e) {
                e.stopPropagation();
                var setId = $(this).attr("data-equivalenceSetId");
                console.log(setId);
                $('#deleteEquivalenceSetModal').modal('toggle');
                $('#deleteEquivalenceSetModal .submitLink').attr("href", "equivalenceSet/delete?id=" + setId);
            });
        },

        init = function () {
            deleteEquivalenceSetBtnHandler();
        };
    return {
        init: init
    }
}();