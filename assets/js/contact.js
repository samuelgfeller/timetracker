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
                $('#successMsg').html('Kontakt erfolgreich gelöscht');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(ajaxOptions,thrownError,xhr);
                $('#errorMsg').html('<b>Fehler:</b> Kontakt konnte nicht gelöscht werden ('+thrownError+')');
            }
        });
    }
};
searchContact=function(inputVal) {
    $.ajax({
        url: '/contacts/find',
        type: 'post',
        data: {
            "inputVal": inputVal
        },
        success: function (data) {
            // console.log(data);
            // $('#test').html(JSON.stringify(data, null, 4));
// $('#orteTable').html(JSON.parse(data));
            if(data.length>0) {
                // console.log(data);
                // data = JSON.parse(data);
                var table = document.getElementById("contactsTable");

                $(".contacts tr").not(':first').remove();
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
                        '<button class="btn upd" id="myBtn" onclick="display(\'/contacts/edit/' + id + '\')">Edit</button>'
                    );
                    td.insertAdjacentHTML(
                        'beforeend',
                        '<button class="btn del" id="myBtn" onclick="delContact(' + id + ')">Delete</button>'
                    );
                }
                $('.pagination').css("display", "none");
                $('.noResults').css("display", "none");
            }else{
                $(".contacts tr").not(':first').remove();
                $('.pagination').css("display", "none");
                $('.noResults').css("display", "inline");
            }
        }
    })
};