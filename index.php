<?php
    require_once "auth.php";
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Dziennik szkolny</title>
	
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <link rel="stylesheet" href="main.css">

</head>
<body>
<?php
    if(!zalogowany())
    {
echo<<<END
    <form action="logowanie.php" method="post">
        <input type="text" name="login" placeholder="LOGIN" required><br>
        <input type="password" name="haslo" placeholder="HASŁO" required><br>
        <input type="submit" name="zaloguj" value="ZALOGUJ">
    </form>
END;
        if(isset($_SESSION['blad']))
        {
            echo '<span style="color:red;">' . $_SESSION['blad'] . '</span>';
            unset($_SESSION['blad']);
        }
    }
    else
    {
echo<<<END
    <form action="wyloguj.php" method="post">
        <input type="submit" value="WYLOGUJ">
    </form>

    <form action="index.php" method="post">
        <select name="klasa" onchange="this.form.submit();">
            <option>WYBIERZ KLASĘ</option>
            <option>1a</option>
            <option>1b</option>
            <option>2a</option>
            <option>2b</option>
        </select>
    </form>
END;

	if(isset($_POST['klasa']) || isset($_COOKIE['klasa']))
    {
        require_once "dbconnect.php";

        $connection = mysqli_connect($host, $user, $password, $dbname);
        
        if(isset($_POST['klasa'])) $klasa = $_POST['klasa'];
        else $klasa = $_COOKIE['klasa'];

        $query = "SELECT uczen.id, Imie, Nazwisko, Srednia_ocen, id_klasy FROM uczen, klasa WHERE nazwa='" . $klasa . "' AND klasa.id=uczen.id_klasy";
        $result = mysqli_query($connection, $query);
        
        $liczba_wierszy = mysqli_num_rows($result);
        if($liczba_wierszy==0)
        {
            echo '<span style="color:red;">Nie ma takiej klasy w szkole!</span>';
        }
        else
        {
            $query = "SELECT wychowawca.imie, wychowawca.nazwisko FROM wychowawca, klasa WHERE nazwa='" . $klasa . "' AND klasa.id=wychowawca.id_klasy";
            $result_wych = mysqli_query($connection, $query);
            $wych = mysqli_fetch_assoc($result_wych);

            echo "<p><strong>KLASA $klasa - " . $wych['imie'] . " " . $wych['nazwisko'] . "</strong></p>";
echo<<<END
            <table>
                <thead>
                    <tr><th>Imię</th><th>Nazwisko</th><th>Średnia ocen</th><th></th><th></th></tr>
                </thead>
                <tbody>             
END;
            $srednia_ocen = 0;
            while($row = mysqli_fetch_assoc($result))
            {
                $id_klasy = $row['id_klasy'];

                echo "<tr>";
                    echo "<td>".$row['Imie']."</td>";
                    echo "<td>".$row['Nazwisko']."</td>";
                    echo "<td>".$row['Srednia_ocen']."</td>";
                    
                    $id = $row['id'];
                    
                    if(jest_wychowawca_klasy($id_klasy))
                    {
                        echo '<td><button onclick="window.location.href=\'edytuj_ucznia.php?id_ucznia=' . $id . '\'">EDYTUJ</button></td>';
                        
                        echo '<td><button onclick="if(confirm(\'Czy chcesz usunąć ucznia ' . $row['Imie'] . ' ' . $row['Nazwisko'] . '?\')) window.location.href=\'usun_ucznia.php?id_ucznia=' . $id . '\'">KASUJ</button></td>';
                    }
                    else
                    {
                        echo "<td></td><td></td>";
                    }
                    
                echo "</tr>";
                $srednia_ocen += $row['Srednia_ocen'];
            }
echo<<<END
                </tbody>
            </table>
            
END;
            echo "<p>Średnia ocen klasy: " . round($srednia_ocen/$liczba_wierszy, 2) . "</p><hr>";

            if(jest_wychowawca_klasy($id_klasy))
            {
                echo "<p><strong>DODAWANIE NOWEGO UCZNIA</strong><p>";
echo<<<END
            <form action="dodaj_ucznia.php" method="post">
                <input type="text" name="imie" placeholder="IMIE" required><br>
                <input type="text" name="nazwisko" placeholder="NAZWISKO" required><br>
                <input type="number" name="srednia_ocen" placeholder="ŚREDNIA OCEN" required><br>
                <input type="hidden" name="id_klasy" value="$id_klasy"><br>
                <input type="submit" name="dodaj_ucznia" value="ZAPISZ"><br>
            <form>
END;
            }
            setcookie("klasa", $klasa);
        }
        mysqli_close($connection);
    }
    }
?>

</body>
</html>
