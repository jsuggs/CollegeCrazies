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
        $tbody.children("tr").each(function(idx, val) {
            $(this).find('.sequence').val(idx + 1);
        });
    };
    var calcPercentage = function() {
        var $total = $("#squares-payout-percentages");
        var sum = 0;
        $("table.squares-payouts tbody tr .percentage").each(function() {
            sum += Number($(this).val());
        });

        $total.html(sum);
        if (sum != 100) {
            $total
                .addClass('text-danger')
                .removeClass('text-success');
        } else {
            $total
                .addClass('text-success')
                .removeClass('text-danger');
        }
    };

    $("table.squares-payouts tbody").sortable({
        cursor: 'move',
        stop: function (event, ui) {
            reorderPayouts($(event.target));
        }
    });
    $("#squares-payout-add").on('click', function(e) {
        e.preventDefault();
        var $table = $(this).siblings('table.squares-payouts');
        var $tbody = $table.find('tbody');
        $tbody.append($table.data('prototype').replace(/__name__/g, $tbody.find('tr').length));

        reorderPayouts($tbody);
    });

    $("table.squares-payouts tbody").on('blur', '.percentage', function(e) {
        calcPercentage();
    });

    calcPercentage();
});
