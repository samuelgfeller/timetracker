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
                $('#msg').html('Log erfolgreich gelöscht');
            }
        });
    }
};