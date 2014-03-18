$(function() {
    var $bankroll = $("#bankroll");

    $("#pirc-portfolio").fixedHeaderTable({ height: 400 });

    var calcBankroll = function() {
        var remain = 100;
        $("form.pirc-portfolio input:checked").each(function() {
            remain -= $(this).data('cost');
        });

        $bankroll.html(remain);
        if (remain != 0) {
            $bankroll
                .addClass('text-danger')
                .removeClass('text-success');
        } else {
            $bankroll
                .addClass('text-success')
                .removeClass('text-danger');
        }
    };

    $("form.pirc-portfolio").on('change', 'input', function(e) {
        calcBankroll();
    });

    $("form.pirc-portfolio").on('submit', function(e) {
        var remain = $bankroll.html();

        if (remain > 0) {
            if (!confirm('You still have money left to spend!  Are you sure you want to submit?')) {
                e.preventDefault();
            }
        } else if (remain < 0) {
            alert('You cannot spend more than $100');
            e.preventDefault();
        }
    });

    calcBankroll();
});
