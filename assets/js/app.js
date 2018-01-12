// require('bootstrap-sass');

// var $ = require('jquery');

// Get the modal
var modal = document.getElementById('myModal');
// Get the button that opens the modal
var btn = document.getElementById("myBtn");

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

closeModal = function () {
    document.getElementById('myModal').style.display = "none";
    $('.modal-body').html("<br>Loading...<br><br>");

};

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
        $('.modal-body').html("<br>Loading...<br><br>");
    }
    removeHash();
};

getContactsForCompany = function (company_field) {
    var id = company_field.value;
    var url = '/ajax/get_contacts/' + id;

    $('#timetracker_contact').empty();
    $('#log_contact').empty();

    $.ajax({
        url: url,
        type: 'get',
        success: function (output) {
            $.each(output, function (key, value) {
                $('#timetracker_contact')
                    .append($('<option>', {value: key})
                        .text(value));
                $('#log_contact')
                    .append($('<option>', {value: key})
                        .text(value));
            });
        }
    });
};

hashAdd = function (url) {
    if (window.location.hash === '#add') {
        display(url);
    }
};

function removeHash() {
    history.pushState("", document.title, window.location.pathname
        + window.location.search);
}



