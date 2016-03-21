$(function () {
    var permitButtons = ycoa.SESSION.PERMIT.getPermitButtons();
    $(".permit_buttons").children().each(function () {
        var buttonId = $(this).attr('Class').match(/permit\_\d{5,10}/);
        if (buttonId) {
            buttonId = buttonId.toString().replace('permit_', '');
            if (permitButtons.indexOf(buttonId) === -1) {
                $(this).remove();
            }
        }
    });
    $('.modal').modal({backdrop: false, keyboard: false, show: false});
});