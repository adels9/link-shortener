<?php    
    include_once("ucitavanje.php");
	$errors = [];
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
        $ime = clean($_POST['ime']);
        $prezime = clean($_POST['prezime']);
		$user = clean($_POST['user']);
		$mail = clean($_POST['mail']);
		$pw = clean($_POST['pw']);
		$cpw = clean($_POST['cpw']);
		if (strlen($ime) < 3) {
			echo '2';
            $errors[] = "Ime ne moze biti manje od 3 karaktera.";
        }
        else if (strlen($prezime) < 3) {
			echo '3';
            $errors[] = "Prezime ne moze biti manje od 3 karaktera.";
        }
        else if (strlen($user) < 3) {
			echo '4';
            $errors[] = "Korisnicko ime ne moze biti manje od 3 karaktera.";
        }
        else if (strlen($user) > 20) {
			echo '5';
            $errors[] = "Korisnicko ime ne moze biti vece od 20 karaktera.";
        }
		else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			echo '10';
            $errors[] = "Unijeli ste neispravan e-mail";
        }
        else if (email_exists($mail)) {
			echo '6';
            $errors[] = "Zao nam je, e-mail koji ste unijeli je vec u upotrebi.";
        }
        else if (user_exists($user)) {
			echo '7';
            $errors[] = "Zao nam je, korisnicko ime koji ste unijeli je vec u upotrebi.";
        }
        else if (strlen($pw) < 6) {
			echo '8';
            $errors[] = "Lozinka mora sadrzavati najmanje 6 karaktera.";
        }
        else if ($pw != $cpw) {
			echo '9';
            $errors[] = "Lozinke koje ste unijeli nisu iste.";
        }
        if (empty($errors)) {

            $ime = filter_var($ime, FILTER_SANITIZE_STRING);
            $prezime = filter_var($prezime, FILTER_SANITIZE_STRING);
            $user = filter_var($user, FILTER_SANITIZE_STRING);
            $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
            $pw = filter_var($pw, FILTER_SANITIZE_STRING);
            create_user($ime, $prezime, $user, $mail, $pw);
        }
    }
?>