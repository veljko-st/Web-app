<?php
session_start();
require_once('../inc/obaveznifajlovi.php');
require_once("../funkcije.php");
require_once("../inc/dozvolauser.php");
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
    <title>NEWS</title>
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
            <div class="feed-grid">
                <div class="center">
                    <h1>Brisanje korisnika</h1>
                    <form action="#" method="post">
                        <div class="txt_field">
                            <select name="idKorisnika" id="idKorisnika">
                                <option value="0">--Izaberite korisnika za brisanje--</option>
                                <?php
                                $upit = "SELECT * FROM korisnici WHERE obrisan=0 ORDER BY id DESC";
                                $rez = mysqli_query($db, $upit);
                                while ($red = mysqli_fetch_assoc($rez)) {
                                    echo "<option value='{$red["id"]}'>{$red["id"]}: {$red["ime"]} {$red["prezime"]}</option>";
                                }
                                ?>
                            </select><br><br>
                        </div>
                        <input type="submit" name="delete" value="Obriši korisnika">
                    </form>
                    <br>
                    <?php
                    if (isset($_POST['idKorisnika'])) {
                        $idKorisnika = $_POST['idKorisnika'];

                        if ($idKorisnika != "0") {
                            $upit = "UPDATE korisnici SET obrisan=1 WHERE id={$idKorisnika}";
                            $rez = mysqli_query($db, $upit);
                            if (!mysqli_errno($db)) {
                                $id = mysqli_insert_id($db);
                                $poruka = Poruka::uspeh("Uspešno obrisan korisnik sa id: {$id}");
                                Log::upisi("../logs/" . date("Y-m-d") . "_korisnici.log", "Uspešno izbrisan korisnik sa id={$idKorisnika}  od strane {$_SESSION['podaci']}");
                            } else {
                                $poruka = Poruka::greska("Neuspesno brisanje korisnika");
                                Log::upisi("../logs/".date("Y-m-d")."_logovanja.log", "Korisnik sa email adresom '{$email}' nije registrovan");
                            }
                        } else
                            $poruka = Poruka::greska("Niste izabrali korisnika");
                    }
                    ?>
                    <div id="center"><?php echo $poruka ?></div>
                    <a href='../content.php'>Nazad</a>
                </div>
            </div>
    </div>
    </div>
    </main>
    </div>

</body>

</html>