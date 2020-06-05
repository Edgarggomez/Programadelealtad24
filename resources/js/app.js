/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./components/Example');

/**
 * Funcion para ocultar el input de ubicación para los administradores
 */

if ($('#role').val() == 'admin') {
    $("div > #id_ubicacion").val("");
    $("label[for='id_ubicacion']").fadeOut();
    $("div > #id_ubicacion").fadeOut();
}

$("#role").change(function() {
    if ($('#role').val() == 'admin') {
        $("div > #id_ubicacion").val("");
        $("label[for='id_ubicacion']").fadeOut();
        $("div > #id_ubicacion").fadeOut();
    } else {
        $("label[for='id_ubicacion']").fadeIn();
        $("div > #id_ubicacion").fadeIn();
    }
});

var btnDelete = $('.btn-delete');
$('.confirm-delete').each((i, e) => {
    $(e).change(() => {
        var checked = $(e).prop('checked');
        $(btnDelete[i]).prop("disabled", !checked);
    });
});
