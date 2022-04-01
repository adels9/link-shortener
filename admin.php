<?php

include "funkcije/ucitavanje.php";

if (!isset($_SESSION['email'])) {
    redirect("prijava.html");
    return;
}

$user = get_user();
if($user['admin'] != 1) {
    redirect("index.php");
}

?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width:device-width, initial-scale=1.0">
        <title>Admin</title>
        <link rel = "stylesheet" href = "css/stil.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="js/jquery.min.js"></script>
        <script src="js/admin.js"></script>
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
                        <div class="main-window">
                            <table class="table">
                                <div class="row header">
                                    <div class="cell">
                                        E-mail
                                    </div>
                                    <div class="cell">
                                        Ime
                                    </div>
                                    <div class="cell">
                                        Prezime
                                    </div>
                                    <div class="cell">
                                        Opcije
                                    </div>
                                </div>
                                <?php
								  $query = "SELECT * FROM korisnici WHERE aktivan= 0 ORDER BY id DESC LIMIT 5";
								  $result = query($query);
								  if($result->num_rows > 0)
								  {
										while ($row = $result->fetch_assoc()) 
										{
											echo '<div class="row">
													<div class="cell" id = "link_len" data-title="E-mail">
														'.$row['email'].'
													</div>
													<div class="cell" data-title="Ime">
													'.$row['ime'].'
													</div>
													<div class="cell" data-title="Prezime">
														'.$row['prezime'].'
													</div>
													<div class="cell" data-title="Opcije">
														<button type="submit" class="searchButton" onclick="Racun(\''.$row['id'].'\', 1)">
															<i class="fa fa-check-square"></i>
														</button>
														<button type="submit" onclick="Racun(\''.$row['id'].'\', 2)" class="searchButton">
															<i class="fa fa-window-close" id = "id_izbrisi"></i>
														</button>
													</div>
												</div>';
										}
									}
								?>              
                            </table>
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