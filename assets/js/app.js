var $ =require('jquery');
// require('bootstrap-sass');
/*function showResults(){
    $.ajax({ url: 'results.php',
        type: 'post',
        data: {
            "amount": amount,
        },
        success: function(output) {
            $('#showResults').html(output);
        }
    });
    $("#showResultsBtn").css('display',"none");
}*/
$( document ).ready(function(){


// Get the modal
    var modal = document.getElementById('myModal');

// Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            $('.modal-body').html("<br>Loading...<br><br>");
        }
    };
    closeModal=function(){
        document.getElementById('myModal').style.display = "none";
        $('.modal-body').html("<br>Loading...<br><br>");

    };
    display=function (url) {
        $.ajax({
            url: url,
            type: 'post',
            success: function (output) {
                $('.modal-body').html(output);
            }
        });

        modal.style.display = "block";
    };


    /*Delete*/
    delOrt=function(url,id,toDel){
        $.ajax({ url: url,
            type: 'post',
            data: {
                "id": id
            },
            success: function(output) {
                if(toDel==='ort'){
                    $('#ort'+id).remove();
                    $('#msg').html('Ort erfolgreich gelöscht');
                }else if(toDel==='company'){
                    $('#company'+id).remove();
                    $('#msg').html('Firma erfolgreich gelöscht');
                }else if(toDel==='service'){
                    $('#service'+id).remove();
                    $('#msg').html('Service erfolgreich gelöscht');
                }else if(toDel==='contact'){
                    $('#contact'+id).remove();
                    $('#msg').html('Kontakt erfolgreich gelöscht');
                }
                // window.location.replace("http://localhost:8000/timetracker/orte");
            }
        });

    }

});


