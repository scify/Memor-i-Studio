window.EquivalenceSetsController = function (cards, editCardRoute) {
    this.cards = JSON.parse(cards);
    this.editCardRoute = editCardRoute;
};

window.EquivalenceSetsController.prototype = function () {
    var deleteEquivalenceSetBtnHandler = function () {
            $(".deleteSetBtn").on("click", function (e) {
                e.stopPropagation();
                var setId = $(this).attr("data-equivalenceSetId");
                console.log(setId);
                $('#deleteEquivalenceSetModal').modal('toggle');
                $('#deleteEquivalenceSetModal .submitLink').attr("href", "equivalenceSet/delete?id=" + setId);
            });
        },
        editCardBtnHandler = function (cards, editCardRoute) {
            $(".editCardBtn").on("click", function (e) {
                e.stopPropagation();
                var cardId = $(this).attr("data-cardId");
                console.log(editCardRoute);
                var card = findCardById(cards, cardId);
                populateCardForm(card);
                $('#cardSimpleModal').modal('toggle');
                $('#simpleCardForm').attr("action", editCardRoute + "?cardId=" +cardId);
            });
        },
        findCardById = function(cards, cardId) {
            for(var i = 0; i < cards.length; i++) {
                if(cards[i].id == cardId)
                    return cards[i];
            }
            return null;
        },
        populateCardForm = function(card) {
            console.log(card);
        },
        init = function () {
            var instance = this;
            console.log(instance.cards);
            console.log("here");
            deleteEquivalenceSetBtnHandler();
            editCardBtnHandler(instance.cards, instance.editCardRoute);
        };
    return {
        init: init
    }
}();