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
        var homeTeamScore = $("#pickset_tiebreakerHomeTeamScore").val();
        var awayTeamScore = $("#pickset_tiebreakerAwayTeamScore").val();

        $(".pick").each(function() {
            numPicks++;
            if ($(this).find('.team').val().length) {
                setPicks++;
            }
        });

        if (setPicks == numPicks) {
            if (homeTeamScore.length && awayTeamScore.length) {
                $("#pick-status span").html('Complete').addClass('label-success').removeClass('label-important').removeClass('label-warning');
                $("#incomplete-help").remove();
            } else {
                $("#pick-status span").html('Tiebreakers').addClass('label-warning').removeClass('label-important');
                $("#incomplete-help").remove();
            }
        } else {
            var status = $("#pick-status span").html('Incomplete').addClass('label-important');
            if (!$("#incomplete-help").length) {
                status.after('<a id="incomplete-help" href="#" rel="popover" data-trigger="hover" title="Help" data-content="Please be sure to pick a winning team for every game and enter a tiebreaker score."> <i class="icon-question-sign"></i></a>');
            }
        }
    }

    // Check the status only if the pick form is valid
    if ($('.pickForm').length) {
        checkPickStatus();
    }

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
    $("#pickset_tiebreakerHomeTeamScore, #pickset_tiebreakerAwayTeamScore").change(function() {
        checkPickStatus();
    });

    var popoverVisible = false;
    var popoverClickedAway = false;
    var currentPopover;
    var popoverOptions = {
        html: true,
        trigger: 'manual'
    };
    $('a[rel="popover"]').popover(popoverOptions).click(function(e) {
        if(popoverVisible) {
            currentPopover.popover('hide');
        }
        currentPopover = $(this).popover('show');
        popoverClickedAway = false;
        popoverVisible = true;
        e.preventDefault();
    });

    // Close popover when clicked away
    $(document).click(function(e) {
        if(popoverVisible & popoverClickedAway) {
            currentPopover.popover('hide');
            popoverVisible = popoverClickedAway = false;
        } else {
            popoverClickedAway = true;
        }
    });
});
