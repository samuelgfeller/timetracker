/*

// Get the modal
    var modal = document.getElementById('myModal');

// Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            $('.modal-body').html("<br>Loading...<br><br>");
        }
    };
    closeModal = function () {
        document.getElementById('myModal').style.display = "none";
        $('.modal-body').html("<br>Loading...<br><br>");

    };
    display = function (url) {
        $.ajax({
            url: url,
            type: 'post',
            success: function (output) {
                $('.modal-body').html(output);
            }
        });

        modal.style.display = "block";
    };
*/
/*Delete*/
delOrt = function (id) {
    if (confirm('Are you sure you want to delete this?')) {
        $.ajax({
            url: '/orte/del',
            type: 'post',
            data: {
                "id": id
            },
            success: function (output) {
                $('#ort' + id).remove();
                $('#msg').html('Ort erfolgreich gelöscht');

            }
        });
    }
};
