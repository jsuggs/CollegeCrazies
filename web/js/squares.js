$(function() {
    $(".squares-claim").submit(function (e) {
        e.preventDefault();
        var $form = $(this);
        var $td = $form.parent();

        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            success: function(data) {
                if (data.success) {
                    $td.replaceWith(data.html);

                    var $claimedSquares = $("#claimed-squares");
                    var $unclaimedSquares = $("#unclaimed-squares");

                    $claimedSquares.html(parseInt($claimedSquares.html()) + 1);
                    $unclaimedSquares.html(parseInt($unclaimedSquares.html()) - 1);
                }
            },
        });
    });
    var reorderPayouts = function ($tbody) {
        //
        console.log($tbody);
        $tbody.children("tr").each(function(idx, val) {
            console.log(val);
            $(this).find('.sequence').val(idx + 1);
        });
    };
    $("table.squares-payouts tbody").sortable({
        stop: function (event, ui) {
            reorderPayouts($(event.target));
        }
    });
});
