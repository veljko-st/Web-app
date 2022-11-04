<?php
session_start();
require_once('../inc/obaveznifajlovi.php');
require_once("../funkcije.php");
if (!$db = konekcija()) exit();
statistika($db);
if (isset($_GET['id']) and isset($_GET['akcija'])) {
    $id = $_GET['id'];
    $akcija = $_GET['akcija'];
    if ($akcija == "obrisi") $upit = "DELETE FROM komentari WHERE id={$id}";
    if ($akcija == "dozvoli") $upit = "UPDATE komentari SET dozvoljen=1 WHERE id={$id}";
    $db->query($upit);
}
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
                } ?>
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
            <h1>Pregled komentara</h1>
            <hr>
            <?php
            $idKorisnika = $_SESSION['id'];
            $upit = "SELECT * FROM komentari WHERE dozvoljen=1 and idKorisnika= '$idKorisnika' ORDER BY vreme DESC";
            $rez = mysqli_query($db, $upit);
            while ($red = mysqli_fetch_object($rez)) {
                echo "<div>";
                echo "<div><b>{$red->ime}:</b> <i>{$red->vreme}</i></div>";
                echo "<p>$red->komentar</p>";
                echo "<a style='color:red;' href='usercomments.php?id={$red->id}&akcija=obrisi'>Obriši</a> ";
                echo "</div><br>";
            }
            ?>
            <div><?= $poruka ?></div>
    </div>
    </div>
    </main>
    </div>