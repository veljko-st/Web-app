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
                    <a href="login/logout.php">Log out</a>
                </li>
            </ul>
        </aside>
        <main class="content">
            
            <?php
        if(login())
        {
            if($_SESSION['status']=='Administrator')
            {
                echo "<h2>Administrator</h2>";
                echo "<ul class='lista'";
                echo "<li><a href='adduser.php'>Dodaj korisnika</a></li>";
                echo "<li><a href='deleteuser.php'>Obriši korisnika</a></li>";
                echo "<li><a href='statistika.php'>Statistika</a></li>";
                echo "</ul>";
            }
            if($_SESSION['status']=='Urednik' OR $_SESSION['status']=='Administrator')
            {
                echo "<h2>Urednik</h2>";
                echo "<ul>";
                echo "<li><a href='addproduct.php'>Dodaj proizvod</a></li>";
                echo "<li><a href='deleteproduct.php'>Obriši proizvod</a></li>";
                echo "</ul>";
            }

            echo "<h2>Korisnik</h2>";
            echo "<ul>";
            echo "<li><a href='profil.php'>Profil</a></li>";
            echo "<li><a href='logout.php'>Odjavite se</a></li>";
            echo "</ul>";
        }
        else
            echo "Morate biti Administrator ili urednik da biste koristili ovu stranicu";
        
    ?>
            </div>
        </main>
    </div>

</body>

</html>