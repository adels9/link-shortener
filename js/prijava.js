$(document).ready(function()
{
    $(".btn").click(function()
    {
        var nick = $("#email").val();
        var pass = $("#password").val();
        var podaci ="korisnik="+nick+"&lozinka="+pass;
        $.ajax
        ({  

                type: "POST",  
                url: "funkcije/prijava.php",  
                data: podaci,
                success: function(response)
                {  			
                    var odgovor = " ";
                    if(response == 1) odgovor = "Korisnički račun ne postoji";
                    if(response == 2) odgovor = "Unijeli ste pogrešnu lozinku";
                    if(response == 4) odgovor = "Korisnički račun nije odobren";
                    if(response == 3) window.location = 'index.php';
                    if(nick.length > 0 && pass.length > 0 && response != 3)
                    {
                        $(".alert").css("display", "block");
                        $(".alert").html(odgovor);
                    }
                } 
        });
    });
});