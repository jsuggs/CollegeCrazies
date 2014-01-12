$(function() {
    $(".squares-claim").submit(function (e) {
        e.preventDefault();
        $form = $(this);
        console.log(e);
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
        });
    });
});
