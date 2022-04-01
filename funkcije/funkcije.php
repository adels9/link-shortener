<?php
function clean($str)
{
    return htmlentities($str);
}

function redirect($location)
{
    header("location: {$location}");
	//echo '<script>window.location.href = "'. $location .'"</script>';
}

function set_message($message)
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    } else {
        $message = "";
    }
}

function display_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}


function email_exists($email)
{
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $query = "SELECT id FROM korisnici WHERE email = '$email'";
    $result = query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function user_exists($user)
{
    $user = filter_var($user, FILTER_SANITIZE_STRING);
    $query = "SELECT id FROM korisnici WHERE username = '$user'";
    $result = query($query);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function validate_user_registration()
{
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $first_name = clean($_POST['ime']);
        $last_name = clean($_POST['prezime']);
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);
        if (strlen($first_name) < 3) {
            $errors[] = "Ime ne moze biti manje od 3 karaktera.";
        }
        if (strlen($last_name) < 3) {
            $errors[] = "Prezime ne moze biti manje od 3 karaktera.";
        }
        if (strlen($username) < 3) {
            $errors[] = "Korisnicko ime ne moze biti manje od 3 karaktera.";
        }
        if (strlen($username) > 20) {
            $errors[] = "Korisnicko ime ne moze biti vece od 20 karaktera.";
        }
        if (email_exists($email)) {
            $errors[] = "Zao nam je, e-mail koji ste unijeli je vec u upotrebi.";
        }
        if (user_exists($username)) {
            $errors[] = "Zao nam je, korisnicko ime koji ste unijeli je vec u upotrebi.";
        }
        if (strlen($password) < 8) {
            $errors[] = "Lozinka mora sadrzavati najmanje 8 karaktera.";
        }
        if ($password != $confirm_password) {
            $errors[] = "Lozinke koje ste unijeli nisu iste.";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
				echo '<script>document.getElementById("alert").style.display = "block";  var element = document.getElementById("alert"); element.innerHTML = "' . $error . '"; </script>';
            }
        } else {
            $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
            $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($password, FILTER_SANITIZE_STRING);
            create_user($first_name, $last_name, $username, $email, $password);
        }
    }
}

function create_user($first_name, $last_name, $username, $email, $password)
{
    $first_name = escape($first_name);
    $last_name = escape($last_name);
    $username = escape($username);
    $email = escape($email);
    $password = escape($password);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO korisnici(ime, prezime,username, slika_profila ,email,password) ";
    $sql .= "VALUES('$first_name','$last_name','$username','uploads/slika.jpg','$email','$password')";
    confirm(query($sql));
    echo '1';
}

function create_link($link, $korisnik)
{
	$link = escape($link);
	$korisnik = escape($korisnik);
	$gkod = substr(md5(uniqid(rand(), true)),0,6); 
	$datum = date("Y-m-d");
	$slink = "$_SERVER[HTTP_HOST]/{$gkod}";
	$sql = "INSERT INTO linkovi(generisani_kod, korisnik, datum, link, skraceni_link) ";
    $sql .= "VALUES('$gkod','$korisnik','$datum','$link','$slink')";
    confirm(query($sql));
	echo json_encode(array("datum"=>$datum, "link_1"=>$link, "link_2"=>$slink));
}

function obrisi_link($id)
{
	$id = escape($id);
	$korisnik = $_SESSION['email'];
	$sql = "DELETE FROM linkovi WHERE id = $id AND korisnik = '$korisnik'";
    confirm(query($sql));
	echo '1';
}

function provjeri_link($kod)
{
	$kod = escape($kod);
	$query = "SELECT * FROM linkovi WHERE generisani_kod='$kod'";
    $result = query($query);

    if ($result->num_rows == 1) 
	{
        $data = $result->fetch_assoc();
		header("Location: ".$data['link'],TRUE,301);
	}
	
}

function validate_user_login()
{
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = clean($_POST['korisnik']);
        $password = clean($_POST['lozinka']);
        if (empty($email)) {
            $errors[] = "Polje e-mail ne moze biti prazno.";
        }
        if (empty($password)) {
            $errors[] = "Polje lozinka ne moze biti prazno.";
        }
        if (empty($errors)) {
            if (user_login($email, $password)) {
                //redirect('index.php');
				echo '3';
            } else {
                $errors[] = "Unijeli ste pogresne podatke.";
				echo '2';
            }
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert" style = "display:block">' . $error . '</div>';
            }
        }
    }

}

function user_login($email, $password)
{
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $query = "SELECT * FROM korisnici WHERE email='$email'";
    $result = query($query);

    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();

        if (password_verify($password, $data['password'])) {
            $_SESSION['email'] = $email;
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function login_check_pages()
{
    if (isset($_SESSION['email'])) {
        redirect('index.php');
    }
}

function user_restrictions()
{
    if (!isset($_SESSION['email'])) {
        redirect('login.php');
    }
}


function get_user($id = NULL)
{
    if ($id != NULL) {
        $query = "SELECT * FROM korisnici WHERE id=" . $id;
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "Korisnik nije pronadjen.";
        }
    } else {
        $query = "SELECT * FROM korisnici WHERE email='" . $_SESSION['email'] . "'";
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "Korisnik nije pronadjen.";
        }
    }
}

function user_profile_image_upload()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $target_dir = "uploads/";
        $user = get_user();
        $user_id = $user['id'];

        $ime = clean($_POST['ime']);
        $prezime = clean($_POST['prezime']);
        $mail = clean($_POST['mail']);
        $lozinka = clean($_POST['lozinka']);
        $trenutna_loz = clean($_POST['trenutnaloz']);

        $u_ime = $user['ime'];
        $u_prezime = $user['prezime'];
        $u_mail = $user['email'];
        $u_lozinka = $user['password'];

        if(!empty($ime)) 
        {
            if (!strcmp($u_ime, $ime) !== 0)  
            {
                $ime = filter_var($ime, FILTER_SANITIZE_STRING);
                $sql = "UPDATE korisnici SET ime='$ime' WHERE id=$user_id";
                confirm(query($sql));
            }
        }    
        if(!empty($prezime))
        {
            if (!strcmp($u_prezime, $prezime) !== 0)  
            {
                $prezime = filter_var($prezime, FILTER_SANITIZE_STRING);
                $sql = "UPDATE korisnici SET prezime='$prezime' WHERE id=$user_id";
                confirm(query($sql));
            }
        }
        if(!empty($mail))
        {
            if (!strcmp($u_mail, $mail) !== 0)  
            {
                $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
                $query = "SELECT * FROM korisnici WHERE email='$mail'";
                $result = query($query);
                if ($result->num_rows == 0)
                {
                    $sql = "UPDATE linkovi SET korisnik='$mail' WHERE korisnik='$u_mail'";
                    confirm(query($sql));
                    $sql = "UPDATE korisnici SET email='$mail' WHERE id=$user_id";
                    confirm(query($sql));
                    $_SESSION['email'] = $mail;
                }
            }
        }
        if(is_uploaded_file($_FILES["profile_image_file"]["tmp_name"]))
        {
            $target_file = $target_dir . $user_id . "." .pathinfo(basename($_FILES["profile_image_file"]["name"]), PATHINFO_EXTENSION);;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $error = "";
            $check = getimagesize($_FILES["profile_image_file"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $error = "Datoteka nije slika.";
                $uploadOk = 0;
            }

            if ($_FILES["profile_image_file"]["size"] > 5000000) {
                $error = "Zao nam je, datoteka je prevelika. ( Max: 5mb)";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $error = "Samo JPG, JPEG, PNG & GIF fajlovi su dozvoljeni.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                set_message('Greska prilikom uploada: '. $error);
            } else {
                $sql = "UPDATE korisnici SET slika_profila='$target_file' WHERE id=$user_id";
                confirm(query($sql));
                set_message('Profile Image uploaded!');

                if (!move_uploaded_file($_FILES["profile_image_file"]["tmp_name"], $target_file)) {
                    set_message('Greska prilikom uploada: '. $error);
                }
            }
        }
        if(!empty($lozinka))
        {
            if(!empty($trenutna_loz))
            {
                $lozinka = filter_var($lozinka, FILTER_SANITIZE_STRING);
                $trenutna_loz = filter_var($trenutna_loz, FILTER_SANITIZE_STRING);
                $query = "SELECT * FROM korisnici WHERE email='$email'";
                $result = query($query);
                if (password_verify($trenutna_loz, $u_lozinka)) 
                {
                    $password = password_hash($lozinka, PASSWORD_DEFAULT);
                    $sql = "UPDATE korisnici SET password='$password' WHERE id=$user_id";
                    confirm(query($sql));
                    redirect("odjava.php");
                }
            }
        }
        redirect('profil.php');
    }
}

function ucitaj_linkove($id = NULL)
{
    if ($id != NULL) {
        $query = "SELECT * FROM linkovi WHERE id=" . $id;
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "Link nije pronadjen.";
        }
    } else {
        $query = "SELECT * FROM linkovi WHERE korisnik='" . $_SESSION['email'] . "'";
		$result = query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
            return $row;
			}
		}
	}
	return null;
}