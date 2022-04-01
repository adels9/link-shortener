function Racun(id, funkcija)
{
    var podaci ="id="+id+"&funkcija="+funkcija;
    $.ajax
    ({  
        type: "POST",  
        url: "funkcije/admin_funkcije.php",  
        data: podaci,
        success: function(response)
        {
            if(response == 1 || response == 2)
            {
                location.reload();
            }
        }
    });
}