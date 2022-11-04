<?php
require_once("funkcije.php");
if (!$db = konekcija()) exit();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>NEWS</title>
</head>

<body>

    <header>
        <nav class="navigation">
            <div class="logotype">News Velja</div>
            <ul class="categories">
                <li class="category-item"><a href='index.php'>Početna</a></li>
                <?php include("inc/navigation.php"); ?>
            </ul>
        </nav>
        <div class="search">
            <?php
            if (login())
                echo "{$_SESSION['podaci']} ({$_SESSION['status']})";
            else
                echo "<button><a href='login/login.php'>Prijavi se</a></button>";
            ?>
            <form action="index.php" method="POST">
                <input type="text" name="termin" placeholder="Unesite termin za pretragu" />
                <button>Pretraži</button>
            </form>
        </div>
    </header>
    <div class="wrapper">
        <aside style="color: white ;" class="sidebar">
            <ul class="sidebar-list">
                <li>
                    <a style="color: whitesmoke;" href="login/logout.php">Log out</a>
                </li>
                <br>
                <li>
                    <a style="color: whitesmoke;" href="usercom/usercomments.php">Vasi komentari</a>
                </li>
            </ul>
        </aside>
        <main class="content">
            <div class="feed-grid">
                <?php
                if (isset($_GET['id'])) {
                    //upit za naslovnu sliku
                    $upit1 = "SELECT * FROM slikeProizvoda WHERE idProizvoda={$_GET['id']} and naslovna='1'";
                    $red1 = mysqli_query($db, $upit1);
                    echo "<div class='card-half wide'>";
                    while ($rez = mysqli_fetch_object($red1)) {
                        echo "<div class='card-img'><span class='label'> <i class='fa fa-star'></i></span><img src='content/slikeProizvoda/{$rez->imeSlike}' alt='img' /></div>";
                    }


                    $upit = "SELECT * FROM pogledvesti WHERE obrisan=0 AND id={$_GET['id']}";
                    $rez = mysqli_query($db, $upit);
                    while ($red = mysqli_fetch_assoc($rez)) {
                        echo "<div><a href='index.php?autor={$red['autor']}'>{$red['ime']} {$red['prezime']}</a></div>";
                        echo "<h2>{$red['naslov']}</h2>";
                        echo "<div>{$red['tekst']}</div><br>";
                        echo "<div><a href='index.php?kategorija={$red['kategorija']}'>{$red['naziv']}</a> | " . date("d.m.Y H:i", strtotime($red['vreme'])) . "</div>";
                        echo "</div>";
                    }
                    //prikaz slika proizvoda
                    $upit = "SELECT * FROM slikeProizvoda WHERE idProizvoda={$_GET['id']} and naslovna='0'";
                    $rez = mysqli_query($db, $upit);
                    if (mysqli_num_rows($rez) > 0) {
                        echo "<div class='maleSlike'>";
                        $rez = $db->query($upit);
                        while ($redSlika = mysqli_fetch_object($rez))
                            echo "<div style=' display: flex;
                            max-width: 1160px;
                            margin: 0 auto;
                            flex-flow: row wrap;
                            justify-content: center;
                            align-content: flex-start;'><a href='content/slikeProizvoda/{$redSlika->imeSlike}'><img width = 720px; src='content/slikeProizvoda/{$redSlika->imeSlike}' ></a></div>";
                        echo "</div>";
                    }
                } else
                    echo "Niste izabrali vest!!!!!"; ?>


            </div>
            <div style="max-width: 1160px; padding-left: 45px; justify-content: left;">
                <?php
                if (login()) {
                ?>
                    <form action="#" method="post">
                        <p><?= $_SESSION['podaci'] ?></p>
                        <textarea name="komentar" id="komentar" cols="30" rows="10" placeholder="Unesite komentar" required></textarea><br><br>
                        <button>Snimi komentar</button>
                    </form>
                <?php
                } else { ?>
                    <p>Zelite i vi da ostavite komentar <a style="color: red;" href="login/login.php">prijavi se</a></p><br>
                <?php    }
                ?>
                <hr>
                <?php
                //Upis komentara u bazu
                if (isset($_POST['komentar'])) {
                    $ime = $_SESSION['podaci'];
                    $komentar = $_POST['komentar'];
                    $idKorisnika = $_SESSION['id'];
                    $komentar = filter_var($komentar, FILTER_SANITIZE_STRING);
                    if ($komentar != "") {
                        $upit = "INSERT INTO komentari (idProizvoda, ime, komentar, idKorisnika) VALUES ('{$_GET['id']}', '{$ime}', '{$komentar}', '{$idKorisnika}' )";
                        mysqli_query($db, $upit);
                        if (mysqli_error($db)) echo Poruka::greska("Došlo je do greške!!!<br>" . mysqli_error($db));
                        else echo "<p style='color: red;'>Uspešno dodat komentar. Da bi bio vidljiv on prvo mora biti dozvoljen od strane administratora.<p><br>";
                    } else
                        echo "Svi podaci su obavezni";
                }
                ?>

                <?php
                //Prikaz komentara
                $upit = "SELECT * FROM komentari WHERE idProizvoda={$_GET['id']} AND dozvoljen=1 ORDER BY vreme DESC";
                $rez = $db->query($upit);
                if (mysqli_num_rows($rez) == 0) echo ("Nema ni jedan komentar, budite prvi");
                else {
                    while ($red = mysqli_fetch_object($rez)) {
                        echo "<div>";
                        echo "<b>{$red->ime}:</b> <i>$red->vreme</i><br>";
                        echo "$red->komentar";
                        echo "</div><br>";
                    }
                }
                ?>
            </div>
        </main>
    </div>

</body>

</html>