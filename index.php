<?php
include "funkcije/ucitavanje.php";

if(isset($_GET['link']))
{
	$kod = clean($_GET['link']);
	provjeri_link($kod);
}
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width:device-width, initial-scale=1.0">
        <title>Početna</title>
        <link rel = "stylesheet" href = "css/stil.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="js/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <script>
            $(document).ready(function(){
                $("#id_potvrdi").click(function(){
                var unos = $('#id_link').val();
                if(isValidURL(unos) && unos.length > 5)
                {
                    var user = "<?php echo $_SESSION['email'] ?>";
                    var podaci ="korisnik="+user+"&link="+unos;
                    var row, col1, col2, col3, col4;
                    $.ajax
                    ({  
                            type: "POST",  
                            url: "funkcije/link_funkcije.php",  
                            data: podaci,
                            dataType:'JSON', 
                            success: function(response)
                            {  			
                                if(response == 2) { alert("Link vec postoji u bazi"); return; }
                                location.reload();
                            } 
                    });
                } else alert('Unijeli ste neispravan link');
            });
        });
        </script>
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
                        <?php if (isset($_SESSION['email'])): ?>
                        <div class="main-window">
                            <table class="table">
                                <div class="rowh header">
                                    <div class="cell">
                                        Link
                                    </div>
                                    <div class="cell">
                                        Datum
                                    </div>
                                    <div class="cell">
                                        Skraćeni link
                                    </div>
                                    <div class="cell">
                                        Opcije
                                    </div>
                                </div>
                                <?php
								  $query = "SELECT * FROM linkovi WHERE korisnik='" . $_SESSION['email'] . "' ORDER BY id DESC LIMIT 5";
								  $result = query($query);
								  if($result->num_rows > 0)
								  {
										while ($row = $result->fetch_assoc()) 
										{
											echo '<div class="row">
													<div class="cell" id = "link_len" data-title="Link">
														'.$row['link'].'
													</div>
													<div class="cell" data-title="Datum">
													    '.$row['datum'].'
													</div>
													<div class="cell" data-title="SLink">
														'.$row['skraceni_link'].'
													</div>
													<div class="cell" data-title="Opcije">
														<button type="submit" class="searchButton" onclick="Kopiraj(\''.$row['skraceni_link'].'\')">
															<i class="fa fa-copy"></i>
														</button>
														<button type="submit" onclick="izbrisiLink(\''.$row['id'].'\')" class="searchButton">
															<i class="fa fa-trash" id = "id_izbrisi"></i>
														</button>
													</div>
												</div>';
										}
									}
								?>              
                            </table>
                            <div class = "dno">
                                <input type="text" class="maininp" id = "id_pretraga" placeholder="Pretraživanje">
                                <button type="submit" class="searchButton">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="maininp" id = "id_link" placeholder="Unesite link">
                                <button type="submit" class="searchButton" id = "id_potvrdi">
                                    <i class="fa fa-check-circle-o"></i>
                                </button>
                            </div>
                        </div>
                        <?php 
                            else:                  
                            redirect("/prijava.html");
                            endif;                        
                        ?>
                    </div>
                </section> 
            </div>
        </div>
        <footer class="footer">
            <p>Link Shortener 2022</p>
        </footer>
    </body>
</html>