<?php
session_start();
require_once('../inc/obaveznifajlovi.php');
require_once("../funkcije.php");
require_once("../inc/dozvolavesti.php");
if (!$db = konekcija()) exit();
statistika($db);
$poruka = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../login/style2.css">
    <title>Add product</title>
</head>

<body>
    <header>
        <nav class="navigation">
            <div class="logotype">News Velja</div>
            <ul class="categories">
                <li class="category-item"><a href='../index.php'>Početna</a></li>
                <?php $upit = "SELECT * FROM kategorije";
                $rez = mysqli_query($db, $upit);
                while ($red = mysqli_fetch_assoc($rez)) {
                    echo "<li class='category-item'><a href='../index.php?kategorija={$red['id']}'>{$red['naziv']}</a></li> ";
                }  ?>
            </ul>
        </nav>
        <div class="search">
        </div>
    </header>
    <div class="wrapper">
        <aside class="sidebar">
            <ul class="sidebar-list">
            </ul>
        </aside>
        <main class="content">
            <h1>Kreiranje korisnika</h1>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="txt_field">
                    <input type="text" name="naslov">
                    <span></span>
                    <label>Unesite naslov</label>
                </div>
                <div class="txt_field">
                    <textarea name="tekst" id="" cols="30" rows="10" placeholder="Unesite tekst"></textarea>
                </div>
                <div class="txt_field">
                    <select name="kategorija" id="kategorija">
                        <option value="0">--Izaberite kategoriju--</option>
                        <?php
                        $upit = "SELECT * FROM kategorije ORDER BY naziv ASC";
                        $rez = mysqli_query($db, $upit);
                        while ($red = mysqli_fetch_object($rez))
                            echo "<option value='{$red->id}'>{$red->naziv}</option>";
                        ?>
                    </select>
                </div>
                <div class="txt_field">
                    <br>
                    <input type="file" name="slika" accept="image/*"><br><br>
                    <label>Unesite naslovnu sliku</label>
                </div>
                <div class="txt_field">
                    <br>
                    <input type="file" name="slike[]" accept="image/*" multiple><br><br>
                    <label>Unesite ostale slike</label>
                </div>
                <input style="width: 30vh;" type="submit" name="login" value="Dodaj novost">
            </form>

            <br>
            <?php
            if (isset($_POST['naslov']) and isset($_POST['tekst'])) {
                $naslov = trim($_POST['naslov']);
                $tekst = trim($_POST['tekst']);
                $kategorija = $_POST['kategorija'];
                if ($naslov != "" and $tekst != "" and $kategorija != "0") {
                    $naslov = mysqli_real_escape_string($db, $naslov);
                    $tekst = mysqli_real_escape_string($db, $tekst);
                    $upit = "INSERT INTO vesti (naslov, tekst, kategorija, autor, cena) VALUES ('{$naslov}','{$tekst}','{$kategorija}',{$_SESSION['id']}, '1')";
                    $rez = mysqli_query($db, $upit);
                    if (!mysqli_errno($db)) {
                        $idProizvoda = mysqli_insert_id($db); //umesto id proizvod treba idvesti
                        $poruka = Poruka::uspeh("Uspešno dodat proizvod sa id={$idProizvoda}");
                        Log::upisi("../logs/".date("Y-m-d")."_vesti.log", "Uspešno dodat proizvod sa id={$idProizvoda} od strane {$_SESSION['podaci']}");
                        if (isset($_FILES['slika'])) {
                            //$idProizvoda = mysqli_insert_id($db);
                            $imeSlike = microtime(true) . "_" . $_FILES['slika']['name'];
                            if (@move_uploaded_file($_FILES['slika']['tmp_name'], "slikeProizvoda/" . $imeSlike)) {
                                $upit = "INSERT INTO slikeproizvoda (idProizvoda, imeSlike, naslovna) VALUES ({$idProizvoda}, '{$imeSlike}', '1')";
                                mysqli_query($db, $upit);
                                if (mysqli_error($db))
                                    echo "Došlo je do greške!!!<br>" . mysqli_error($db) . "<br>" . $upit . "<br>";
                                $poruka .= Poruka::uspeh("<br>Uspešno dodata naslovna slika");
                            }
                            else {
                                $upit = "INSERT INTO slikeproizvoda (idProizvoda, imeSlike, naslovna) VALUES ({$idProizvoda}, 'nemaslike.jpg', '1')";
                                mysqli_query($db, $upit);
                                if (mysqli_error($db))
                                    echo "Došlo je do greške!!!<br>" . mysqli_error($db) . "<br>" . $upit . "<br>";
                                $poruka .= Poruka::uspeh("<br>Uspešno dodata naslovna slika");
                            }
                        } 
                        

                        if (isset($_FILES['slike'])) {
                            //$idProizvoda = mysqli_insert_id($db);
                            for ($i = 0; $i < count($_FILES['slike']['name']); $i++) { //name="slike[]" je dodato da ne bi napravilo gresku zato sto selektujemo vise fajla i racuna ga kao array
                                $imeSlike = microtime(true) . "_" . $_FILES['slike']['name'][$i];
                                if (@move_uploaded_file($_FILES['slike']['tmp_name'][$i], "slikeProizvoda/" . $imeSlike)) {
                                    $upit = "INSERT INTO slikeproizvoda (idProizvoda, imeSlike) VALUES ({$idProizvoda}, '{$imeSlike}')";
                                    mysqli_query($db, $upit);
                                    if (mysqli_error($db))
                                        echo "Došlo je do greške!!!<br>" . mysqli_error($db) . "<br>" . $upit . "<br>";
                                    $poruka .= Poruka::uspeh("<br>Uspešno dodate slike");
                                }
                            }
                        } else {
                            $poruka .= Poruka::uspeh("<br>Nije dodata slika");
                        }
                    } else {
                        $poruka = Poruka::greska("Greska pri prijavljivanju!");
                        Log::upisi("../logs/".date("Y-m-d")."_vesti.log", "Vest ne postoji");
                    }
                } else
                    $poruka = Poruka::greska("Naslov, tekst i kategorija moraju biti uneti");
            } else
                //$poruka = Poruka::info("Dobrodošli na stranicu za dodavanje vesti"); 
            ?>

            <div id="center"><?php echo $poruka ?></div>
            <a href='../content.php'>Nazad</a>
    </div>
    </div>
    </main>
    </div>

</body>

</html>