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
                <h1>Statistika</h1>
                    <form action="statistika.php" method="post">
                        <input type="date" name="datum"> <br><br>
                        <select name="log" id="log">
                            <option value="0">--Izaberite log datoteku--</option>
                            <option value="logovanja.log">Logovanja</option>
                            <option value="korisnici.log">Korisnici</option>
                            <option value="proizvodi.log">Proizvodi</option>
                        </select><br><br>   
                        <button>Pogledaj log</button>
                    </form>
                    <hr>
                    <?php 
                        if(isset($_POST['datum']) AND isset($_POST['log']))
                        {
                            $datum=$_POST['datum'];
                            $log=$_POST['log'];
                            if($datum!="" AND $log!='0' )
                            {
                                //echo $datum."<br>".$log;
                                $imeDatoteke="../logs/".$datum."_".$log;
                                if(file_exists($imeDatoteke))
                                    $poruka=nl2br(file_get_contents($imeDatoteke));
                                else
                                    $poruka="Nema ni jedan zapis u '{$imeDatoteke}'";
                            }
                            else
                                $poruka=Poruka::greska("Svi podaci su obavezni");
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