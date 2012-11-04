$(document).ready(function(){
    $("#pick-list tbody").sortable({
        cursor: 'move',
        update: function () {
            var children = $(this).children();
            var idx = children.length;
            children.each(function() {
                var confElement = $(this).find('.confidence');
                confElement.find('.confDisplay').html(idx);
                confElement.find('.confidenceValue').val(idx);
                idx--;
            });
        }
    });

    var checkPickStatus = function() {
        var setPicks = 0;
        var numPicks = 0;

        $(".pick").each(function() {
            numPicks++;
            if ($(this).find('.team').val().length) {
                setPicks++;
            }
        });

        if (setPicks == numPicks) {
            $("#pick-status span").html('Complete').addClass('label-success').removeClass('label-important');
        } else {
            $("#pick-status span").html('Incomplete').addClass('label-important');
        }
    }
    checkPickStatus();

    $('.pickForm').submit(function() {
        $(this).find('.makePick').each(function() {
            $(this).removeAttr('name');
        });
    });

    $('.makePick').change(function() {
        var row = $(this).closest('tr').find('.confidence').removeClass('unpicked').addClass('picked');
        row.find('.team').val($(this).val());
        checkPickStatus();
    });

    var popoverOptions = {
        html: true
    };
    $('a[rel="popover"]').popover(popoverOptions);
});
