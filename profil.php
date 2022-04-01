<?php

include "funkcije/ucitavanje.php";

if (!isset($_SESSION['email'])) {
    redirect("prijava.html");
}

?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width:device-width, initial-scale=1.0">
        <title>Profil</title>
        <link rel = "stylesheet" href = "css/stil.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="js/jquery.min.js"></script>
        <script src="js/profil.js"></script>
    </head>
    <body>
        <div class="content">
            <div class="content-inside">
                <div class = "cover">
                    <div class="blur"></div>  
                    <img class = "logo" src="css/img/logo.png">
                </div>
                <div class = "navbar"> 
                    <div class="container flex">
                        <nav>
                            <ul>
                                <li><a href = "index.php">Početna</a></li>
                                <li><a href = "profil.php">Profil</a></li>
                                <?php
                                    if (isset($_SESSION['email'])) {
                                        $user = get_user();
                                        if($user['admin'] == 1) echo '<li><a href = "admin.php">Admin</a></li>';
                                    }
                                ?>
                                <li><a href = "odjava.php">Odjava</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>  
                <section class="main">
                    <div class="container flex">
                        <div class="main-window account">
                            <form method="POST" enctype="multipart/form-data" style = "display:flex;width:100%;align-items: center;justify-content: center;flex-direction: column;">
                                <?php
                                    $user = get_user();
                                    echo "<img class='profile-image' onclick='getFile()' src='" . $user['slika_profila'] . "'>
                                    <input type = 'text' name = 'ime' value = '" . $user['ime'] . "'> 
                                    <input type = 'text' name = 'prezime' value = '" . $user['prezime'] . "'> 
                                    <input type = 'text' name = 'mail' value = '" . $user['email'] . "'> 
                                    <input type = 'password' name = 'lozinka' placeholder = 'Unesite novu lozinku'> 
                                    <input type = 'password' name = 'trenutnaloz' placeholder = 'Unesite trenutnu lozinku'> 
                                    ";
                                    user_profile_image_upload();
                                ?>
                                <input type="submit" class = "btn accs" value="Spremi" name="submit">
                                <div style="height: 0px;width:0px; overflow:hidden;">
                                    <input id="upfile" type="file" name="profile_image_file">
                                </div>
                             </form>
                        </div>
                    </div>
                </section> 
            </div>
        </div>
        <footer class="footer">
            <p>Link Shortener 2022</p>
        </footer>
    </body>
</html>