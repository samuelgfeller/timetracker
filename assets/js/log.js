/*Delete*/
delLog = function (id) {
    if (confirm('Are you sure you want to delete this?')) {
        $.ajax({
            url: '/history/del',
            type: 'post',
            data: {
                "id": id
            },
            success: function (output) {
                $('#log' + id).remove();
                $('#successMsg').html('Log erfolgreich gelöscht');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(ajaxOptions,thrownError,xhr);
                $('#errorMsg').html('<b>Fehler:</b> Log konnte nicht gelöscht werden ('+thrownError+')');
            }
        });
    }
};