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
                    <h1>Brisanje vesti</h1>
                    <form action="#" method="post">
                        <div class="txt_field">
                            <select name="idVesti" id="idVesti">
                                <option value="0">--Izaberite vest za brisanje--</option>
                                <?php
                                $upit = "SELECT * FROM vesti WHERE obrisan=0 ORDER BY id DESC";
                                $rez = mysqli_query($db, $upit);
                                while ($red = mysqli_fetch_assoc($rez)) {
                                    echo "<option value='{$red["id"]}'>{$red["id"]}: {$red["naslov"]} </option>";
                                }
                                ?>
                            </select><br><br>
                        </div>
                        <input type="submit" name="delete" value="Obriši vest">
                    </form>

                    <br>
                    <?php
                    if (isset($_POST['idVesti'])) {
                        $idVesti = $_POST['idVesti'];

                        if ($idVesti != "0") {
                            $upit = "UPDATE vesti SET obrisan=1 WHERE id={$idVesti}";
                            $rez = mysqli_query($db, $upit);
                            if (!mysqli_errno($db)) {
                                $id = mysqli_insert_id($db);
                                $poruka = Poruka::uspeh("Uspešno obrisana vest sa id-jem: {$id}");
                                Log::upisi("../logs/" . date("Y-m-d") . "_vesti.log", "Uspešno izbrisan korisnik sa id={$idVesti}  od strane {$_SESSION['podaci']}");
                                
                            } else {
                                $poruka = Poruka::greska("Neuspesno brisanje korisnika");
                                Log::upisi("../logs/".date("Y-m-d")."_vesti.log", "Korisnik sa email adresom '{$email}' nije registrovan");
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