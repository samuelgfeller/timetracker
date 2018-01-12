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
                $('#msg').html('Service erfolgreich gelöscht');
            }
        });
    }
};