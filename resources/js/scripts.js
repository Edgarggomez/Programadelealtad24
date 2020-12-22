var btnDelete = $('.btn-delete');
$('.confirm-delete').each((i, e) => {
    $(e).on('change', () => {
        var checked = $(e).prop('checked');
        $(btnDelete[i]).prop("disabled", !checked);
    });
});
