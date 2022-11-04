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
        <button>Pretrazi</button>
      </form>
    </div>
  </header>
  <div class="wrapper">
    <aside class="sidebar">
      <ul class="sidebar-list">
        <li class="sidebar-item">
        </li>
        <li>
          <a style="color: whitesmoke;" href="login/logout.php">Log out</a>
        </li>
        <br>
        <li>
          <a style="color: whitesmoke;" href="content.php">Content</a>
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
        $upit = "SELECT * FROM pogledvesti WHERE obrisan=0 ORDER BY id DESC";
        if (isset($_GET['autor'])) $upit = "SELECT * FROM pogledvesti WHERE autor='{$_GET['autor']}' AND  obrisan=0  ORDER BY id DESC";
        if (isset($_GET['kategorija'])) $upit = "SELECT * FROM pogledvesti WHERE kategorija='{$_GET['kategorija']}' AND obrisan=0 ORDER BY id DESC";
        if (isset($_POST['termin'])) $upit = "SELECT * FROM pogledvesti WHERE naslov LIKE ('%{$_POST['termin']}%') OR tekst LIKE('%{$_POST['termin']}%')";
        $rez = mysqli_query($db, $upit);
        while ($red = mysqli_fetch_assoc($rez)) {
          $upit1 = "SELECT * FROM slikeProizvoda WHERE idProizvoda={$red['id']} and naslovna='1'";
          $red1 = mysqli_query($db, $upit1);
          echo "<div class='card-half wide'>";
          $rez1 = mysqli_fetch_assoc($red1);
          echo "<div class='card-img'><span class='label'> <i class='fa fa-star'></i></span><img src='content/slikeProizvoda/{$rez1['imeSlike']}' alt='img' /></div>";
          echo "<div class='card-text'>";
          echo "<div><a href='index.php?autor={$red['autor']}'>{$red['ime']} {$red['prezime']}</a></div>";
          echo "<h2><a href='vest.php?id={$red['id']}'>{$red['naslov']}</a></h2>";
          $tmp = explode(" ", $red['tekst']);
          $niz = array_slice($tmp, 0, 10);
          echo "<div>" . implode(" ", $niz) . ".....</div>";
          echo "<div><a href='index.php?kategorija={$red['kategorija']}'>{$red['naziv']}</a> | " . date("d.m.Y H:i", strtotime($red['vreme'])) . "</div>";
          echo "</div></div>";
        }
        ?>
        <div class="card-half wide">
          <div class="card-img"><span class="label"> <i class="fa fa-star"></i></span><img src="content/slikeProizvoda/nemaslike1.jpg" alt="img" /></div>
          <div class="card-text">
            <h4>Ovo je neka dosadna reklama</h4>
            <p>Sluzi cisto da pokvari sajt.</p>
          </div>
          <ul class="card-tools">
            <li class="tools-item"><i class="fa fa-heart like"></i><span class="tools-count">543</span></li>
            <li class="tools-item"><i class="fa fa-share share"></i></li>
          </ul>
        </div>
      </div>
    </main>
  </div>
</body>

</html>