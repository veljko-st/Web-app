<?php
require_once("funkcije.php");
if (!$db = konekcija()) exit();
statistika($db);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylecontent.css">
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
            <!--<input type="search" placeholder="Type to search..." />-->
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
        <aside class="sidebar">
            <ul class="sidebar-list">
                <li>
                    <a style="color: whitesmoke;" href="login/logout.php">Log out</a>
                </li>
            </ul>
        </aside>
        <main class="content ">
            <?php
            if (login()) {
                if ($_SESSION['status'] == 'Administrator') {
                    echo "<h2 <spam style='font-size: 3vh;'>Administrator <spam style='font-size: 2vh;'> (Ovo moze koristiti samo administrator stranice)<spam></h2> <br><br>";
                    echo "<ul>";
                    echo "<li><a href='content/adduser.php'>-Dodaj korisnika</a></li><br>";
                    echo "<li><a href='content/deleteuser.php'>-Obriši korisnika</a></li><br>";
                    echo "<li><a href='content/statistika.php'>-Statistika</a></li><br>";
                    echo "<li><a href='content/comments.php'>-Pregled komentara</a></li><br>";
                    echo "</ul><br><br>";
                }
                if ($_SESSION['status'] == 'Urednik' or $_SESSION['status'] == 'Administrator') {
                    echo "<h2 <spam style='font-size: 3vh;'>Urednik <spam style='font-size: 2vh;'> (Ovo moze koristiti administrator i urednik stranice)<spam></h2> <br>";
                    echo "<ul><br>";
                    echo "<li><a href='content/addproduct.php'>-Dodaj proizvod</a></li><br>";
                    echo "<li><a href='content/deleteproduct.php'>-Obriši proizvod</a></li><br>";
                    echo "</ul>";
                }
            } else
                echo "Morate biti Administrator ili urednik da biste koristili ovu stranicu";

            ?>
        </main>
    </div>

</body>

</html>