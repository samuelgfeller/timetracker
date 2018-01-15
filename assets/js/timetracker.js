// $( document ).ready(function(){

$('#timeForm').submit( function(event) {
// testAjax=function() {

    event.preventDefault();

    var contact = document.getElementById("timetracker_contact").value;
    var url = '/ajax/checkLog/'+contact;
    $.ajax({ url: url,
        type: 'post',
/*        data: {
            "contact": contact,
        },*/
        async: false,
        success: function(output) {
            if (output.status === true) {
                var r = confirm("Zeiterfassung für diesen Kontakt läuft schon.\nFortsetzen mit den alten Einstellungen?");
                if (r == true) {
                    var input = $("<input>", {
                        type: "hidden",
                        name: "exists",
                        value: "true"
                    });
                    //insert a hidden input w. name "exists"
                    $(function() { $('#timeForm').append( input ); });
                    $('#timeForm').append($(input));
                    $('#timeForm').unbind('submit').submit(); //make submit event continue
                }else{
                    return false; //breaks down the submit event
                }
            }else{
                $('#timeForm').unbind('submit').submit();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(ajaxOptions,thrownError,xhr);
            $('#errorMsg').html('<b>Fehler:</b> es konnte nicht geprüft werden ob ein Eintrag schon am laufen ist ('+thrownError+')');
        }
    });
    });
// });