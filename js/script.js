document.addEventListener("DOMContentLoaded", function() {
    var elements = document.getElementsByTagName("INPUT");
    for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function(e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
                e.target.setCustomValidity("See väli ei tohi tühjaks jääda!");
            }
        };
        elements[i].oninput = function(e) {
            e.target.setCustomValidity("");
        };
    }
});
$('#usersSelect').change(function(){
    var user = $(this).val();
    if (user=="") {
        document.getElementById("result").innerHTML="Sellist kasutajat ei eksisteeri!";
        return;
    }
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("result").innerHTML=this.responseText;
        }
    }
    xmlhttp.open("GET","ajaxrequests/scriptIfBanned.php?q="+user,true);
    xmlhttp.send();
});
