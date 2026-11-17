<?php
    require_once "auth.php";

    if(isset($_POST['zaloguj']))
    {
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];

        require_once "dbconnect.php";

        $connection = mysqli_connect($host, $user, $password, $dbname);

        $query = "SELECT id_klasy FROM wychowawca WHERE imie='$login' AND nazwisko='$haslo'";
        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['login'] = $login;
            $_SESSION['id_klasy_wychowawcy'] = $row['id_klasy'];
            unset($_SESSION['blad']);
        }
        else
        {
            $query = "SELECT id FROM uczen WHERE Imie='$login' AND Nazwisko='$haslo'";
            $result = mysqli_query($connection, $query);

            if(mysqli_num_rows($result) > 0)
            {
                $_SESSION['login'] = $login;
                unset($_SESSION['id_klasy_wychowawcy']);
                unset($_SESSION['blad']);
            }
            else
            {
                $_SESSION['blad'] = "ZŁE DANE LOGOWANIA";
            }
        }

        mysqli_close($connection);
    }

    header("Location:index.php");
?>
