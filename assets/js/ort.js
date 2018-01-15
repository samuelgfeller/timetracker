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
                $('#successMsg').html('Ort erfolgreich gelöscht');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(ajaxOptions, thrownError, xhr);
                $('#errorMsg').html('<b>Fehler:</b> Ort konnte nicht gelöscht werden (' + thrownError + ')');
            }
        });
    }
};

 searchOrt=function(inputVal) {
    $.ajax({
        url: '/orte/find',
        type: 'post',
        data: {
            "inputVal": inputVal
        },
        success: function (data) {
            // console.log(data);
            // $('#test').html(JSON.stringify(data, null, 4));
// $('#orteTable').html(JSON.parse(data));
            if(data != '[]') {
                data = JSON.parse(data);
                var table = document.getElementById("orteTable");

                $(".orte tr").not(':first').remove();
                //Gets all key like Ort:, PLZ, Id
                var col = [];
                for (var i = 0; i < data.length; i++) {
                    for (var key in data[i]) {
                        if (col.indexOf(key) === -1) {
                            col.push(key);
                        }
                    }
                }
//Set values inside table
                for (var i = 0; i < data.length; i++) {

                    var tr = table.insertRow();
                    var id = null;
                    for (var j = 0; j < col.length; j++) {

                        var tabCell = tr.insertCell(-1);
                        //get data like Basel, 4000, 5
                        tabCell.innerHTML = data[i][col[j]];
                        if (col[j] == 'id') {
                            id = data[i][col[j]];
                        }
                    }
                    tr.setAttribute("id", "ort" + id);
                    var td = tr.insertCell(-1);
                    td.insertAdjacentHTML(
                        'beforeend',
                        '<button class="btn upd" id="myBtn" onclick="display(\'/orte/edit/' + id + '\')">Edit</button>'
                    );
                    td.insertAdjacentHTML(
                        'beforeend',
                        '<button class="btn del" id="myBtn" onclick="delOrt(' + id + ')">Delete</button>'
                    );
                }
                $('.pagination').css("display", "none");
                $('.noResults').css("display", "none");
            }else{
                $(".orte tr").not(':first').remove();
                $('.pagination').css("display", "none");
                $('.noResults').css("display", "inline");
            }
        }
    })
};