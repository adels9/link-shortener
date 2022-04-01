<?php

    include_once("ucitavanje.php");

    if(isset($_SESSION['email']))
	{
        if ($_SERVER['REQUEST_METHOD'] == "POST") 
		{
			if(isset($_POST['id']) && isset($_POST['funkcija'])) 
			{
                $user = get_user();
                if($user['admin'] == 1)
                {
                    $id = clean($_POST['id']);
                    $id = escape($id);
                    $funkcija = clean($_POST['funkcija']);
                    $query = "SELECT * FROM korisnici WHERE id = $id AND aktivan = 0";
                    $result = query($query);
                    if ($result->num_rows == 1) 
                    {
                        if($funkcija == 1)
                        {
                            $sql = "UPDATE korisnici SET aktivan = 1 WHERE id = $id";
                            confirm(query($sql));
                            echo '1';
                        }
                        else if($funkcija == 2)
                        {
                            $sql = "DELETE FROM korisnici WHERE id = $id AND aktivan = 0";
                            confirm(query($sql));
                            echo '2';
                        }
                        
                    }
                }
            }
        }
    }

?>