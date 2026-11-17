<?php
    require_once "auth.php";

    if(isset($_GET['id_ucznia']))
    {
        $id_ucznia = $_GET['id_ucznia'];

        require_once "dbconnect.php";

        $connection = mysqli_connect($host, $user, $password, $dbname);

        if(jest_wychowawca_klasy(klasa_ucznia($connection, $id_ucznia)))
        {
            $query = "DELETE FROM uczen WHERE id = $id_ucznia";

            mysqli_query($connection, $query);
        }

        mysqli_close($connection);
    }

    header("Location:index.php");


?>
