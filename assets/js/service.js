/*Delete*/
delService = function (id) {
    if (confirm('Are you sure you want to delete this?')) {
        $.ajax({
            url: '/services/del',
            type: 'post',
            data: {
                "id": id
            },
            success: function (output) {
                $('#service' + id).remove();
                $('#successMsg').html('Service erfolgreich gelöscht');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(ajaxOptions,thrownError,xhr);
                $('#errorMsg').html('<b>Fehler:</b> Service konnte nicht gelöscht werden ('+thrownError+')');
            }
        });
    }
};