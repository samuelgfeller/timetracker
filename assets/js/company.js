/*Delete*/
delCompany = function (id) {
    if (confirm('Are you sure you want to delete this?')) {
    $.ajax({
        url: '/companies/del',
        type: 'post',
        data: {
            "id": id
        },
        success: function (output) {
            $('#company' + id).remove();
            $('#msg').html('Firma erfolgreich gelöscht');
        }
    });
    }
};