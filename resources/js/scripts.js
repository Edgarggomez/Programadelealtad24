var btnDelete = $('.btn-delete');
$('.confirm-delete').each((i, e) => {
    $(e).on('change', () => {
        var checked = $(e).prop('checked');
        $(btnDelete[i]).prop("disabled", !checked);
    });
});


if ($('#role').val() == 'admin') {
    $("div > #id_ubicacion").val("");
    $("label[for='id_ubicacion']").fadeOut();
    $("div > #id_ubicacion").fadeOut();
}

$("#role").on("change", function() {
    if ($('#role').val() == 'admin') {
        $("div > #id_ubicacion").val("");
        $("label[for='id_ubicacion']").fadeOut();
        $("div > #id_ubicacion").fadeOut();
    } else {
        $("label[for='id_ubicacion']").fadeIn();
        $("div > #id_ubicacion").fadeIn();
    }
});
