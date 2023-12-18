$(document).ready(function() {
    $('#post-content').summernote({
        height: 200
    });

    $('#select-all-boxes').click(function () {
        if ($(this).prop('checked')) {
            $('.checkBoxes').each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $('.checkBoxes').each(function () {
                $(this).prop('checked', false);
            });
        }
    });
});