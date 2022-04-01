<?php    
    include_once("ucitavanje.php");
	$errors = [];

	if ($_SERVER['REQUEST_METHOD'] == "POST") 
	{
		$email = clean($_POST['korisnik']);
        $password = clean($_POST['lozinka']);
        if (empty($email)) {
            $errors[] = "Polje e-mail ne moze biti prazno.";
        }
        if (empty($password)) {
            $errors[] = "Polje lozinka ne moze biti prazno.";
        }
        if (empty($errors)) 
		{
        	$query = "SELECT * FROM korisnici WHERE email='$email'";
			$result = query($query);

			if ($result->num_rows == 1) 
			{
				$data = $result->fetch_assoc();
				if (password_verify($password, $data['password'])) {
					if($data['aktivan'] == 0)
				    {
						echo '4';
						return false;
					}
					$_SESSION['email'] = $email;
					echo '3';
					return true;
				} 
				else 
				{
					echo '2';
					return false;
				}
			} 
			else 
			{
				echo '1';
				return false;
			}
        }
    }
	
?>