<?php

	include_once("ucitavanje.php");
	
	if(isset($_SESSION['email']))
	{
		if ($_SERVER['REQUEST_METHOD'] == "POST") 
		{
			if(isset($_POST['link'])) 
			{
				$link = clean($_POST['link']);
				$korisnik = clean($_POST['korisnik']);
				$query = "SELECT * FROM linkovi WHERE link='$link'";
				$result = query($query);
				if ($result->num_rows == 0) 
				{
					create_link($link, $korisnik);
				}
				else echo '2';
			}
			
			if(isset($_POST['id']))
			{
				$id = clean($_POST['id']);
				obrisi_link($id);
			}
		}
	}
?>