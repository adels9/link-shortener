$(document).ready(function()
{
    $(".btn").click(function()
    {
        var ime = $("#reg_ime").val();
        var prezime = $("#reg_prezime").val();
        var user = $("#reg_user").val();
        var mail = $("#reg_mail").val();
        var pw = $("#reg_pw").val();
        var pw2 = $("#reg_cpw").val();
        var podaci ="ime="+ime+"&prezime="+prezime+"&user="+user+"&mail="+mail+"&pw="+pw+"&cpw="+pw2;
        $.ajax
        ({  

                type: "POST",  
                url: "funkcije/registruj.php",  
                data: podaci,
                success: function(response)
                {  			
                    var odgovor = " ";
                    if(response == 1) { odgovor = "Registracija uspjesna,<br>prebacujemo vas na prijavu"; setInterval(function () {window.location = 'prijava.html';}, 2000); }
                    else if(response == 2) odgovor = "Ime mora biti vece od 2 znaka";
                    else if(response == 3) odgovor = "Prezime mora biti vece od 2 znaka";
                    else if(response == 4) odgovor = "Korisnicko ime mora biti vece od 2 znaka";
                    else if(response == 5) odgovor = "Korisnicko ime mora biti do 20 znakova";
                    else if(response == 6) odgovor = "E-mail je vec u upotrebi";
                    else if(response == 7) odgovor = "Korisnicko ime je vec u upotrebi";
                    else if(response == 8) odgovor = "Lozinka mora sadrzavati najmanje 6 znakova";
                    else if(response == 9) odgovor = "Lozinke koje ste unijeli nisu iste";
                    else if(response == 10) odgovor = "Unijeli ste neispravan e-mail";
                    if(ime.length > 0 || prezime.length > 0 || user.length > 0 || mail.length > 0 || pw.length > 0 || pw2.length > 0)
                    {
                        $(".alert").css("display", "block");
                        $(".alert").html(odgovor);
                    }
                } 
        });
    });
});