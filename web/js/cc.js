$(document).ready(function(){
    // Symfony forms js
    $('.collection-container').on('click', 'a.collection-add', function(e) {
        e.preventDefault();
        $collection = $(this).closest('.collection-container').find('.collection');
        $collection.append($collection.data('prototype').replace(/__name__/g, $collection.find('li.collection-element').length));
    });
    $('.collection-container').on('click', 'a.collection-remove', function(e) {
        e.preventDefault();
        $(this).closest('li.collection-element').remove();
        return false;
    });

    var setPickConfidence = function () {
        var children = $("#pick-list tbody").children();
        var idx = children.length;
        children.each(function() {
            var confElement = $(this).find('.confidence');
            confElement.find('.confDisplay').html(idx);
            confElement.find('.confidenceValue').val(idx);
            idx--;
        });
    };

    $("#pick-list tbody .move-pick").click(function(e) {
        e.preventDefault();
        var row = $(this).parents('tr:first');

        if ($(this).is(".up")) {
            row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }

        setPickConfidence();
    });

    $("#pick-list tbody").sortable({
        cursor: 'move',
        update: setPickConfidence
    });

    var checkPickStatus = function() {
        var setPicks = 0;
        var numPicks = 0;
        //var homeTeamScore = $("#pickset_tiebreakerHomeTeamScore").val();
        //var awayTeamScore = $("#pickset_tiebreakerAwayTeamScore").val();
        var champ = $("#pickset_championshipWinner").val();

        $(".pick").each(function() {
            numPicks++;
            if ($(this).find('.team').val().length) {
                setPicks++;
            }
        });

        if (setPicks == numPicks) {
            if (champ.length) {
                $("#pick-status span").html('Complete').addClass('label-success').removeClass('label-danger').removeClass('label-warning');
                $("#incomplete-help").remove();
            } else {
                $("#pick-status span").html('Championship').addClass('label-danger').removeClass('label-warning');
                $("#incomplete-help").remove();
            }
        } else {
            var status = $("#pick-status span").html('Incomplete').addClass('label-danger');
            if (!$("#incomplete-help").length) {
                status.after('<a id="incomplete-help" href="#" rel="popover" data-trigger="hover" title="Help" data-content="Please be sure to pick a winning team for every game choose a championship winner."> <i class="icon-question-sign"></i></a>');
            }
        }
    }

    // Check the status only if the pick form is valid
    var pickFormState = null;
    var picksSubmitted = false;
    if ($('.pickForm').length) {
        checkPickStatus();

        // Save a snapshot of the form
        pickFormState = $('.pickForm').serialize();
        $('.pickForm').submit(function() {
            picksSubmitted = true;
        });

        // Set a listener for checking if picks haven't been saved
        window.onbeforeunload = function alertUnsavedPicks() {
            if (!picksSubmitted && pickFormState != $('.pickForm').serialize()) {
                return 'It looks like you have been editing your picks -- if you leave without submitting, your changes will be lost.';
            }
        };
    }

    $('.pickForm').submit(function() {
        $(this).find('.makePick').each(function() {
            $(this).removeAttr('name');
        });
    });

    // Set classes when a team is chosen
    $('.makePick').change(function() {
        var row = $(this)
            .closest('tr')
            .addClass('success')
            .removeClass('danger');

        row.find('.confidence')
            .removeClass('unpicked')
            .addClass('picked');

        row.find('.team')
            .val($(this).val());
        checkPickStatus();
    });
    $("#pickset_tiebreakerHomeTeamScore, #pickset_tiebreakerAwayTeamScore, #pickset_championshipWinner").change(function() {
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

    $('a[rel="tooltip"]').tooltip();
});

$(window).on('load resize', function() {
    $('body').css({"padding-top": $(".navbar").height() + 30 + "px"});
});
