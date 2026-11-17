<?php
    session_start();

    function zalogowany()
    {
        return isset($_SESSION['login']);
    }

    function jest_wychowawca_klasy($id_klasy)
    {
        return isset($_SESSION['login'], $_SESSION['id_klasy_wychowawcy']) && $_SESSION['id_klasy_wychowawcy'] == $id_klasy;
    }

    function klasa_ucznia($connection, $id_ucznia)
    {
        $result = mysqli_query($connection, "SELECT id_klasy FROM uczen WHERE id=$id_ucznia");
        $row = mysqli_fetch_assoc($result);
        return $row ? $row['id_klasy'] : null;
    }
?>
