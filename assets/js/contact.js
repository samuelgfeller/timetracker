/*Delete*/
delContact = function (id) {
    if (confirm('Are you sure you want to delete this?')) {
        $.ajax({
            url: '/contacts/del',
            type: 'post',
            data: {
                "id": id
            },
            success: function (output) {
                $('#contact' + id).remove();
                $('#msg').html('Kontakt erfolgreich gelöscht');
            }
        });
    }
};