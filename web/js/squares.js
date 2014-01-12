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
                }
            },
        });
    });
});
