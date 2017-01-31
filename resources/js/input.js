$(document).on('ajaxComplete ready', function () {

    // Initialize file inputs
    $('.upload-field_type [data-dismiss="fileinput"]:not([data-initialized])').each(function () {

        $(this).attr('data-initialized', '').on('click', function () {
            $(this).closest('.fileinput').find('input').val('');
            $(this).closest('.upload-field_type').find('.view-file').remove();
        });
    });
});
